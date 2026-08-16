<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\DB;

class CartService
{
    /**
     * Gộp giỏ hàng vãng lai vào tài khoản. Trả về các sản phẩm bị điều chỉnh do thiếu hàng.
     */
    public function mergeGuestCartToUser(int $userId, string $sessionId): array
    {
        // Sử dụng DB table trực tiếp nếu các Models chưa được gen đầy đủ
        $guestCart = DB::table('carts')->where('session_id', $sessionId)->first();
        
        if (!$guestCart) {
            return [];
        }

        $userCart = DB::table('carts')->where('user_id', $userId)->first();
        if (!$userCart) {
            $userCartId = DB::table('carts')->insertGetId([
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $userCart = DB::table('carts')->where('id', $userCartId)->first();
        }

        $guestItems = DB::table('cart_items')->where('cart_id', $guestCart->id)->get();
        $adjustedItems = [];

        DB::transaction(function () use ($userCart, $guestItems, &$adjustedItems) {
            foreach ($guestItems as $item) {
                $existingItem = DB::table('cart_items')
                    ->where('cart_id', $userCart->id)
                    ->where('variant_id', $item->variant_id)
                    ->first();

                $mergedQty = $existingItem ? $existingItem->quantity + $item->quantity : $item->quantity;

                // Check kho khả dụng
                $availableQty = DB::table('store_inventory')
                    ->where('variant_id', $item->variant_id)
                    ->selectRaw('SUM(stock_qty - reserved_qty) as available')
                    ->value('available') ?? 0;
                
                $finalQty = min($mergedQty, $availableQty);

                // Nếu số lượng gộp bị bóp lại do thiếu hàng (Silent Clamping Prevention)
                if ($finalQty < $mergedQty) {
                    $adjustedItems[] = [
                        'variant_id' => $item->variant_id,
                        'requested_qty' => $mergedQty,
                        'adjusted_qty' => $finalQty
                    ];
                }

                if ($finalQty > 0) {
                    if ($existingItem) {
                        DB::table('cart_items')->where('id', $existingItem->id)->update(['quantity' => $finalQty]);
                    } else {
                        DB::table('cart_items')->insert([
                            'cart_id' => $userCart->id,
                            'variant_id' => $item->variant_id,
                            'quantity' => $finalQty,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            // Hủy giỏ Guest
            DB::table('cart_items')->where('cart_id', $guestCart->id)->delete();
            DB::table('carts')->where('id', $guestCart->id)->delete();
        });

        return $adjustedItems;
    }
}
