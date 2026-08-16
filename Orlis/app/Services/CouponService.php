<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class CouponService
{
    /**
     * Áp dụng mã giảm giá. Dùng subquery để bao phủ cả trường hợp max_uses = NULL.
     */
    public function applyCoupon(int $couponId, int $userId): void
    {
        DB::transaction(function () use ($couponId, $userId) {
            // Atomic update an toàn cho Coupon Concurrency
            $updated = DB::table('coupons')->where('id', $couponId)
                ->where(function ($query) {
                    $query->whereNull('max_uses')
                          ->orWhereColumn('used_count', '<', 'max_uses');
                })
                ->increment('used_count');

            if (!$updated) {
                throw new \Exception('Mã giảm giá không tồn tại hoặc đã hết lượt sử dụng.');
            }

            // Ghi nhận log người dùng xài mã
            DB::table('coupon_users')->insert([
                'coupon_id' => $couponId,
                'user_id' => $userId,
                'used_count' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }
}
