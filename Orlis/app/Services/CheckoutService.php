<?php

namespace App\Services;

use App\Jobs\ReleaseExpiredOrderJob;
use App\Mail\NewOrderAdminMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
    public function checkout(array $cartData, int $userId, array $shippingAddress, ?int $couponId, int $shippingMethodId, int $pointsUsed = 0)
    {
        $order = DB::transaction(function () use ($cartData, $userId, $shippingAddress, $couponId, $shippingMethodId, $pointsUsed) {
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

            // Tính subtotal
            $subtotal = collect($cartData)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            });

            // Xử lý Coupon nếu có
            $discountAmount = 0;
            if ($couponId) {
                $this->couponService->applyCoupon($couponId, $userId);
                $coupon = DB::table('coupons')->where('id', $couponId)->first();
                if ($coupon) {
                    if ($coupon->discount_amount) {
                        $discountAmount = $coupon->discount_amount;
                    } elseif ($coupon->discount_percent) {
                        $discountAmount = $subtotal * ($coupon->discount_percent / 100);
                    }
                }
            }
            // Xử lý phí giao hàng
            $shippingMethod = DB::table('shipping_methods')->where('id', $shippingMethodId)->first();
            $shippingFee = $shippingMethod->cost;
            if ($shippingMethod->min_order_amount_for_free_shipping !== null && $subtotal >= $shippingMethod->min_order_amount_for_free_shipping) {
                $shippingFee = 0;
            }

            // Xử lý điểm thưởng (1 điểm = 1000đ)
            $user = User::lockForUpdate()->find($userId);
            if ($pointsUsed > 0 && $pointsUsed <= $user->points) {
                $pointsDiscount = $pointsUsed * 1000;
                $user->points -= $pointsUsed;
                $user->save();
            } else {
                $pointsUsed = 0;
                $pointsDiscount = 0;
            }

            $grandTotal = max(0, $subtotal + $shippingFee - $discountAmount - $pointsDiscount);

            // Tạo Order
            $orderId = DB::table('orders')->insertGetId([
                'order_code' => 'ORD-' . strtoupper(uniqid()),
                'user_id' => $userId,
                'coupon_id' => $couponId,
                'shipping_method_id' => $shippingMethodId,
                'shipping_address_snapshot' => json_encode($shippingAddress),
                'recipient_name' => $shippingAddress['recipient_name'],
                'recipient_phone' => $shippingAddress['recipient_phone'],
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'discount_amount' => $discountAmount,
                'points_used' => $pointsUsed,
                'points_discount' => $pointsDiscount,
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

        // Gửi email thông báo cho Admin
        try {
            $adminEmail = config('mail.admin_email', env('ADMIN_EMAIL', 'admin@orlis.com'));
            Mail::to($adminEmail)->send(new NewOrderAdminMail($order));
        } catch (\Exception $e) {
            Log::error('Lỗi gửi email thông báo admin: ' . $e->getMessage());
        }

        return $order->id;
    }
}
