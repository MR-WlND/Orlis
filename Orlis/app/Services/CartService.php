<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class CartService
{
    /**
     * Lấy hoặc tạo giỏ hàng cho user hoặc session.
     */
    public function getOrCreateCart(?int $userId = null, ?string $sessionId = null): Cart
    {
        $query = Cart::query();

        if ($userId) {
            $cart = $query->firstOrCreate(['user_id' => $userId], ['session_id' => null]);
        } else {
            $cart = $query->firstOrCreate(['session_id' => $sessionId], ['user_id' => null]);
        }

        return $cart;
    }

    /**
     * Lấy giỏ hàng kèm items đầy đủ thông tin product.
     */
    public function getCartWithItems(?int $userId = null, ?string $sessionId = null): ?Cart
    {
        $query = Cart::with(['items.variant.product']);

        if ($userId) {
            return $query->where('user_id', $userId)->first();
        }

        return $query->where('session_id', $sessionId)->first();
    }

    /**
     * Thêm hoặc tăng số lượng sản phẩm vào giỏ.
     */
    public function addItem(Cart $cart, int $variantId, int $quantity = 1): array
    {
        $variant = ProductVariant::with('product')->findOrFail($variantId);

        // Kiểm tra tồn kho khả dụng
        $availableQty = DB::table('store_inventory')
            ->where('variant_id', $variantId)
            ->selectRaw('COALESCE(SUM(stock_qty - reserved_qty), 0) as available')
            ->value('available') ?? 0;

        $existingItem = CartItem::where('cart_id', $cart->id)
            ->where('variant_id', $variantId)
            ->first();

        $currentQty = $existingItem?->quantity ?? 0;
        $newQty = $currentQty + $quantity;

        if ($availableQty > 0 && $newQty > $availableQty) {
            $newQty = $availableQty;
            $adjusted = true;
        } else {
            $adjusted = false;
        }

        if ($existingItem) {
            $existingItem->update(['quantity' => $newQty]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'variant_id' => $variantId,
                'quantity' => $newQty,
            ]);
        }

        return [
            'success' => true,
            'adjusted' => $adjusted,
            'qty' => $newQty,
        ];
    }

    /**
     * Cập nhật số lượng một item trong giỏ.
     */
    public function updateItem(Cart $cart, int $variantId, int $quantity): bool
    {
        if ($quantity <= 0) {
            return $this->removeItem($cart, $variantId);
        }

        $updated = CartItem::where('cart_id', $cart->id)
            ->where('variant_id', $variantId)
            ->update(['quantity' => $quantity]);

        return $updated > 0;
    }

    /**
     * Xóa một item khỏi giỏ.
     */
    public function removeItem(Cart $cart, int $variantId): bool
    {
        return CartItem::where('cart_id', $cart->id)
            ->where('variant_id', $variantId)
            ->delete() > 0;
    }

    /**
     * Làm trống giỏ hàng.
     */
    public function clear(Cart $cart): void
    {
        CartItem::where('cart_id', $cart->id)->delete();
    }

    /**
     * Đếm tổng số lượng sản phẩm trong giỏ.
     */
    public function countItems(?int $userId = null, ?string $sessionId = null): int
    {
        $query = Cart::query();

        if ($userId) {
            $cart = $query->where('user_id', $userId)->first();
        } else {
            $cart = $query->where('session_id', $sessionId)->first();
        }

        if (! $cart) {
            return 0;
        }

        return CartItem::where('cart_id', $cart->id)->sum('quantity');
    }

    /**
     * Gộp giỏ hàng vãng lai vào tài khoản. Trả về các sản phẩm bị điều chỉnh do thiếu hàng.
     */
    public function mergeGuestCartToUser(int $userId, string $sessionId): array
    {
        $guestCart = Cart::where('session_id', $sessionId)->first();

        if (! $guestCart) {
            return [];
        }

        $userCart = Cart::firstOrCreate(['user_id' => $userId], ['session_id' => null]);
        $guestItems = CartItem::where('cart_id', $guestCart->id)->get();
        $adjustedItems = [];

        DB::transaction(function () use ($userCart, $guestItems, &$adjustedItems) {
            foreach ($guestItems as $item) {
                $existingItem = CartItem::where('cart_id', $userCart->id)
                    ->where('variant_id', $item->variant_id)
                    ->first();

                $mergedQty = $existingItem ? $existingItem->quantity + $item->quantity : $item->quantity;

                $availableQty = DB::table('store_inventory')
                    ->where('variant_id', $item->variant_id)
                    ->selectRaw('COALESCE(SUM(stock_qty - reserved_qty), 0) as available')
                    ->value('available') ?? PHP_INT_MAX;

                $finalQty = min($mergedQty, $availableQty);

                if ($finalQty < $mergedQty) {
                    $adjustedItems[] = [
                        'variant_id' => $item->variant_id,
                        'requested' => $mergedQty,
                        'adjusted' => $finalQty,
                    ];
                }

                if ($finalQty > 0) {
                    if ($existingItem) {
                        $existingItem->update(['quantity' => $finalQty]);
                    } else {
                        CartItem::create([
                            'cart_id' => $userCart->id,
                            'variant_id' => $item->variant_id,
                            'quantity' => $finalQty,
                        ]);
                    }
                }
            }

            CartItem::where('cart_id', $guestCart->id)->delete();
            $guestCart->delete();
        });

        return $adjustedItems;
    }
}
