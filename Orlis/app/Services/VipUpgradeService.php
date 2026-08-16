<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;

class VipUpgradeService
{
    public function calculateAndUpgrade(User $user)
    {
        // Tính tổng tiền các đơn hàng 'delivered'
        $totalSpent = Order::where('user_id', $user->id)
            ->where('order_status', 'delivered')
            ->sum('grand_total');

        // Chốt 5 mốc chi tiêu: Classic < 10M, Silver >= 10M, Gold >= 50M, Diamond >= 100M, VIP >= 500M.
        $newLevel = 'classic';
        if ($totalSpent >= 500000000) {
            $newLevel = 'vip';
        } elseif ($totalSpent >= 100000000) {
            $newLevel = 'diamond';
        } elseif ($totalSpent >= 50000000) {
            $newLevel = 'gold';
        } elseif ($totalSpent >= 10000000) {
            $newLevel = 'silver';
        }

        if ($user->membership_level !== $newLevel) {
            $user->update(['membership_level' => $newLevel]);
        }
    }
}
