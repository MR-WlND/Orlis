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
}
