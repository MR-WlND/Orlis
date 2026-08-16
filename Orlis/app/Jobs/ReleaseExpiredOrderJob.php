<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ReleaseExpiredOrderJob implements ShouldQueue
{
    use Queueable;

    protected $orderId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Execute the job.
     */
    public function handle(\App\Services\InventoryService $inventoryService): void
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($inventoryService) {
            $order = \App\Models\Order::find($this->orderId);

            if ($order && $order->order_status === 'pending') {
                $hasPayment = \Illuminate\Support\Facades\DB::table('transactions')
                    ->where('order_id', $order->id)
                    ->where('status', 'success')
                    ->exists();

                if (!$hasPayment) {
                    $order->update(['order_status' => 'cancelled']);

                // Lấy chi tiết đơn hàng (Giả định OrderItem model tồn tại)
                $orderItems = \Illuminate\Support\Facades\DB::table('order_items')->where('order_id', $order->id)->get();
                
                foreach ($orderItems as $item) {
                    // Logic giải phóng tồn kho thực tế sẽ đọc chuỗi JSON store_allocations.
                    // Nếu project có cột này: $allocations = json_decode($item->store_allocations, true);
                    // $inventoryService->releaseStock($item->variant_id, $allocations ?? []);
                }
                
                // Ghi log trạng thái hủy do hết hạn
                \Illuminate\Support\Facades\DB::table('order_status_logs')->insert([
                    'order_id' => $order->id,
                    'from_status' => 'pending',
                    'to_status' => 'cancelled',
                    'reason' => 'System: Quá hạn thanh toán 15 phút (Timeout Mechanism). Đã tự động nhả kho.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                }
            }
        });
    }
}
