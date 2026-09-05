<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService) {}

    /**
     * Hiển thị trang giỏ hàng.
     */
    public function index()
    {
        $cart = $this->cartService->getCartWithItems(
            userId: auth()->id(),
            sessionId: session()->getId()
        );

        return view('client.cart', compact('cart'));
    }

    /**
     * Thêm sản phẩm vào giỏ (AJAX hoặc form POST).
     */
    public function addItem(Request $request)
    {
        $request->validate([
            'variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = $this->cartService->getOrCreateCart(
            userId: auth()->id(),
            sessionId: session()->getId()
        );

        $result = $this->cartService->addItem($cart, $request->variant_id, $request->quantity);

        $message = $result['adjusted']
            ? 'Đã thêm vào giỏ hàng (số lượng điều chỉnh do tồn kho có hạn).'
            : 'Đã thêm vào giỏ hàng thành công.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'count' => $this->cartService->countItems(auth()->id(), session()->getId()),
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Cập nhật số lượng item.
     */
    public function updateItem(Request $request, int $variantId)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        $cart = $this->cartService->getOrCreateCart(
            userId: auth()->id(),
            sessionId: session()->getId()
        );

        $this->cartService->updateItem($cart, $variantId, $request->quantity);

        if ($request->expectsJson()) {
            $updatedCart = $this->cartService->getCartWithItems(auth()->id(), session()->getId());

            return response()->json([
                'success' => true,
                'total' => $updatedCart ? number_format($updatedCart->total, 0, ',', '.').'₫' : '0₫',
            ]);
        }

        return back()->with('success', 'Đã cập nhật giỏ hàng.');
    }

    /**
     * Xóa một item khỏi giỏ.
     */
    public function removeItem(int $variantId)
    {
        $cart = $this->cartService->getOrCreateCart(
            userId: auth()->id(),
            sessionId: session()->getId()
        );

        $this->cartService->removeItem($cart, $variantId);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    /**
     * Làm trống giỏ hàng.
     */
    public function clear()
    {
        $cart = $this->cartService->getOrCreateCart(
            userId: auth()->id(),
            sessionId: session()->getId()
        );

        $this->cartService->clear($cart);

        return back()->with('success', 'Đã làm trống giỏ hàng.');
    }

    /**
     * Áp dụng mã giảm giá
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => ['required', 'string']
        ]);

        $cart = $this->cartService->getCartWithItems(
            userId: auth()->id(),
            sessionId: session()->getId()
        );

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Giỏ hàng trống.']);
        }

        $coupon = \App\Models\Coupon::where('code', $request->coupon_code)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            })
            ->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.']);
        }

        if ($coupon->min_order_value && $cart->total < $coupon->min_order_value) {
            return response()->json(['success' => false, 'message' => 'Đơn hàng chưa đạt giá trị tối thiểu ' . number_format($coupon->min_order_value, 0, ',', '.') . '₫']);
        }

        if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá đã hết lượt sử dụng.']);
        }

        // Tính toán số tiền giảm
        $discountAmount = 0;
        if ($coupon->type === 'fixed') {
            $discountAmount = $coupon->value;
        } else {
            $discountAmount = $cart->total * ($coupon->value / 100);
            if ($coupon->max_discount && $discountAmount > $coupon->max_discount) {
                $discountAmount = $coupon->max_discount;
            }
        }

        // Store coupon in session
        session()->put('applied_coupon', [
            'code' => $coupon->code,
            'discount_amount' => $discountAmount,
            'id' => $coupon->id
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Áp dụng mã thành công!',
            'discount_amount' => $discountAmount,
            'discount_formatted' => '-' . number_format($discountAmount, 0, ',', '.') . '₫',
            'new_total_formatted' => number_format(max(0, $cart->total - $discountAmount), 0, ',', '.') . '₫'
        ]);
    }

    /**
     * Gỡ mã giảm giá
     */
    public function removeCoupon()
    {
        session()->forget('applied_coupon');
        return response()->json(['success' => true, 'message' => 'Đã gỡ mã giảm giá.']);
    }
}
