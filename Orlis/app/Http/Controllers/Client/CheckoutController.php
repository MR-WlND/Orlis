<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\Order;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected CheckoutService $checkoutService,
    ) {}

    /**
     * Trang thanh toán.
     */
    public function index()
    {
        $cart = $this->cartService->getCartWithItems(userId: Auth::id());

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Giỏ hàng trống, vui lòng thêm sản phẩm.');
        }

        $addresses = Address::where('user_id', Auth::id())->orderByDesc('is_default')->get();

        return view('client.checkout', compact('cart', 'addresses'));
    }

    /**
     * Xử lý đặt hàng.
     */
    public function store(Request $request)
    {
        $request->validate([
            'recipient_name' => ['required', 'string', 'max:100'],
            'recipient_phone' => ['required', 'string', 'max:20'],
            'province' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'ward' => ['required', 'string', 'max:100'],
            'detail_address' => ['required', 'string', 'max:500'],
            'payment_method' => ['required', 'in:cod,vnpay'],
            'coupon_code' => ['nullable', 'string'],
            'gift_note' => ['nullable', 'string', 'max:500'],
            'save_address' => ['nullable', 'boolean'],
        ], [
            'recipient_name.required' => 'Vui lòng nhập tên người nhận.',
            'recipient_phone.required' => 'Vui lòng nhập số điện thoại.',
            'province.required' => 'Vui lòng chọn tỉnh/thành.',
            'district.required' => 'Vui lòng chọn quận/huyện.',
            'ward.required' => 'Vui lòng chọn phường/xã.',
            'detail_address.required' => 'Vui lòng nhập địa chỉ chi tiết.',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán.',
        ]);

        $cart = $this->cartService->getCartWithItems(userId: Auth::id());

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Giỏ hàng trống.');
        }

        // Xử lý mã coupon
        $couponId = null;
        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', $request->coupon_code)
                ->where('is_active', true)
                ->where(function ($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
                })
                ->first();

            if ($coupon) {
                $couponId = $coupon->id;
            }
        }

        // Lưu địa chỉ nếu khách chọn
        $shippingAddress = [
            'recipient_name' => $request->recipient_name,
            'recipient_phone' => $request->recipient_phone,
            'province' => $request->province,
            'district' => $request->district,
            'ward' => $request->ward,
            'detail_address' => $request->detail_address,
        ];

        if ($request->boolean('save_address')) {
            $existsDefault = Address::where('user_id', Auth::id())->where('is_default', true)->exists();
            Address::create([
                ...$shippingAddress,
                'phone' => $request->recipient_phone,
                'user_id' => Auth::id(),
                'is_default' => ! $existsDefault,
            ]);
        }

        // Build cartData cho CheckoutService
        $cartData = $cart->items->map(function ($item) {
            $price = $item->variant?->price_override
                ?? $item->variant?->product?->sale_price
                ?? $item->variant?->product?->price
                ?? 0;

            return [
                'variant_id' => $item->variant_id,
                'quantity' => $item->quantity,
                'price' => $price,
                'product_name' => ($item->variant?->product?->name ?? 'Sản phẩm')
                    .($item->variant?->display_name ? ' – '.$item->variant->display_name : ''),
            ];
        })->toArray();

        try {
            $orderId = $this->checkoutService->checkout(
                cartData: $cartData,
                userId: Auth::id(),
                shippingAddress: array_merge($shippingAddress, [
                    'gift_note' => $request->gift_note,
                    'payment_method' => $request->payment_method,
                ]),
                couponId: $couponId,
            );
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

        if ($request->payment_method === 'vnpay') {
            $order = Order::find($orderId);
            $vnpayService = new \App\Services\VnpayService();
            $url = $vnpayService->createPaymentUrl($order, $order->grand_total);
            return redirect($url);
        }

        return redirect()->route('checkout.confirm', ['orderId' => $orderId])
            ->with('success', 'Đặt hàng thành công!');
    }

    /**
     * Trang xác nhận đơn hàng thành công.
     */
    public function confirm(Request $request)
    {
        $orderId = $request->query('orderId');

        if (! $orderId) {
            return redirect()->route('home');
        }

        $order = Order::with(['items.variant.product'])
            ->where('user_id', Auth::id())
            ->findOrFail($orderId);

        return view('client.order-confirm', compact('order'));
    }

    public function vnpayReturn(Request $request)
    {
        $vnpayService = new \App\Services\VnpayService();
        $result = $vnpayService->handleIPN($request->all());

        $orderCode = explode('_', $request->input('vnp_TxnRef'))[0];
        $order = Order::where('order_code', $orderCode)->first();

        if ($result['RspCode'] == '00') {
            return redirect()->route('checkout.confirm', ['orderId' => $order->id])
                ->with('success', 'Thanh toán VNPay thành công!');
        }

        return redirect()->route('checkout.confirm', ['orderId' => $order->id])
            ->with('error', 'Thanh toán thất bại hoặc đã bị hủy. Vui lòng thử lại sau.');
    }

    public function vnpayIpn(Request $request)
    {
        $vnpayService = new \App\Services\VnpayService();
        $result = $vnpayService->handleIPN($request->all());
        
        return response()->json($result);
    }
}
