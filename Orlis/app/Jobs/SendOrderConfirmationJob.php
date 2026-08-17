<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOrderConfirmationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle(): void
    {
        Log::info("Đang gửi Email xác nhận Đơn hàng {$this->order->order_code} cho {$this->order->customer_email}");
        
        try {
            Mail::raw("Cảm ơn bạn đã mua sắm tại Orlis. Đơn hàng {$this->order->order_code} của bạn đã được xác nhận.", function ($message) {
                $message->to($this->order->customer_email ?? 'guest@example.com')
                        ->subject("Xác nhận Đơn hàng {$this->order->order_code} - Orlis Luxury");
            });
            Log::info("Đã gửi thành công Email xác nhận cho Đơn hàng {$this->order->order_code}");
        } catch (\Exception $e) {
            Log::error("Lỗi gửi Email đơn hàng {$this->order->order_code}: " . $e->getMessage());
            // Cố tình throw để Queue tự động Retry
            throw $e;
        }
    }
}
