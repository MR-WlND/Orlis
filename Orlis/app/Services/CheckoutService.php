<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Mail\OrderConfirmedMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Jobs\ReleaseExpiredOrderJob;
use App\Models\ProductVariant;

class CheckoutService
{
    protected $inventoryService;
    protected $couponService;

    public function __construct(InventoryService $inventoryService, CouponService $couponService)
    {
        $this->inventoryService = $inventoryService;
        $this->couponService = $couponService;
    }

    /**
     * Tạo đơn hàng. Ngăn chặn Deadlock bằng cách Sorting mảng biến thể.
     */
    public function checkout(array $cartData, int $userId, array $shippingAddress, ?int $couponId)
    {
        return DB::transaction(function () use ($cartData, $userId, $shippingAddress, $couponId) {
            // [DEADLOCK PREVENTION] Sort cart items theo variant_id tăng dần 
            // trước khi đẩy vào vòng lặp giữ kho bằng lockForUpdate().
            usort($cartData, function ($a, $b) {
                return $a['variant_id'] <=> $b['variant_id'];
            });

            // Giữ kho tuần tự
            $allocationsData = [];
            foreach ($cartData as $item) {
                $allocationsData[$item['variant_id']] = $this->inventoryService->reserveStock($item['variant_id'], $item['quantity']);
            }

            // Xử lý Coupon nếu có
            $discountAmount = 0;
            if ($couponId) {
                $this->couponService->applyCoupon($couponId, $userId);
                // Giả lập discount (thực tế lấy rule từ bảng coupons)
                $discountAmount = DB::table('coupons')->where('id', $couponId)->value('discount_amount') ?? 0;
            }

            // Tính subtotal
            $subtotal = collect($cartData)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            });
            $grandTotal = max(0, $subtotal - $discountAmount);

            // Tạo Order
            $orderId = DB::table('orders')->insertGetId([
                'order_code' => 'ORD-' . strtoupper(uniqid()),
                'user_id' => $userId,
                'coupon_id' => $couponId,
                'shipping_address_snapshot' => json_encode($shippingAddress),
                'recipient_name' => $shippingAddress['recipient_name'],
                'recipient_phone' => $shippingAddress['recipient_phone'],
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'grand_total' => $grandTotal,
                'order_status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Lưu Order Items
            foreach ($cartData as $item) {
                DB::table('order_items')->insert([
                    'order_id' => $orderId,
                    'variant_id' => $item['variant_id'],
                    'product_name' => $item['product_name'] ?? 'Sản phẩm',
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    // Mảng $allocationsData sẽ được lưu JSON nếu thiết kế bảng cho phép
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Dọn dẹp giỏ hàng
            $cart = DB::table('carts')->where('user_id', $userId)->first();
            if ($cart) {
                DB::table('cart_items')->where('cart_id', $cart->id)->delete();
                DB::table('carts')->where('id', $cart->id)->delete();
            }

            // Trigger Timeout Worker: Hủy đơn nếu sau 15p chưa thanh toán
            dispatch(new ReleaseExpiredOrderJob($orderId))->delay(now()->addMinutes(15));

            return Order::find($orderId);
        });

        // Gửi email xác nhận đơn hàng
        try {
            $user = User::find($userId);
            if ($user && $user->email) {
                Mail::to($user->email)->send(new OrderConfirmedMail($order));
            }
        } catch (\Exception $e) {
            // Log the error but don't fail the checkout
            Log::error('Lỗi gửi email xác nhận đơn hàng: ' . $e->getMessage());
        }

        return $order->id;
    }
}
