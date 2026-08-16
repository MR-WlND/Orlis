<?php

namespace App\Services;

use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Kiểm tra xem một biến thể có đủ tồn kho trên toàn chuỗi không.
     */
    public function checkAvailability(int $variantId, int $qty): bool
    {
        $availableQty = DB::table('store_inventory')
            ->where('variant_id', $variantId)
            ->selectRaw('SUM(stock_qty - reserved_qty) as available')
            ->value('available');

        return $availableQty >= $qty;
    }

    /**
     * Phân bổ và Giữ kho (Reserve Stock) khi khách hàng đặt đơn.
     * Thuật toán lấy hàng từ Store có tồn lớn nhất trước.
     */
    public function reserveStock(int $variantId, int $qty): array
    {
        return DB::transaction(function () use ($variantId, $qty) {
            $stores = DB::table('store_inventory')
                ->where('variant_id', $variantId)
                ->whereRaw('(stock_qty - reserved_qty) > 0')
                ->orderByRaw('(stock_qty - reserved_qty) DESC')
                ->lockForUpdate()
                ->get();

            $allocations = [];
            $remainingToReserve = $qty;

            foreach ($stores as $store) {
                if ($remainingToReserve <= 0) break;

                $availableAtStore = $store->stock_qty - $store->reserved_qty;
                $toReserve = min($availableAtStore, $remainingToReserve);

                DB::table('store_inventory')
                    ->where('id', $store->id)
                    ->increment('reserved_qty', $toReserve);

                $allocations[$store->store_id] = $toReserve;
                $remainingToReserve -= $toReserve;
            }

            if ($remainingToReserve > 0) {
                throw new \Exception("Không đủ tồn kho khả dụng cho sản phẩm này.");
            }

            return $allocations; // Ví dụ: [store_id_1 => 2, store_id_3 => 1]
        });
    }

    /**
     * Nhả kho (Release Stock) khi đơn hàng bị hủy hoặc quá hạn.
     */
    public function releaseStock(int $variantId, array $allocations): void
    {
        DB::transaction(function () use ($variantId, $allocations) {
            foreach ($allocations as $storeId => $qty) {
                DB::table('store_inventory')
                    ->where('variant_id', $variantId)
                    ->where('store_id', $storeId)
                    ->decrement('reserved_qty', $qty);
            }
        });
    }

    /**
     * Trừ kho cứng (Commit Stock) khi đơn hàng giao thành công.
     */
    public function commitStock(int $variantId, array $allocations): void
    {
        DB::transaction(function () use ($variantId, $allocations) {
            foreach ($allocations as $storeId => $qty) {
                DB::table('store_inventory')
                    ->where('variant_id', $variantId)
                    ->where('store_id', $storeId)
                    ->update([
                        'stock_qty' => DB::raw("stock_qty - $qty"),
                        'reserved_qty' => DB::raw("reserved_qty - $qty"),
                    ]);
            }
        });
    }
}
