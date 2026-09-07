<?php

namespace App\Observers;

use App\Models\Order;
use App\Services\VipUpgradeService;

class OrderObserver
{
    protected $vipService;

    public function __construct(VipUpgradeService $vipService)
    {
        $this->vipService = $vipService;
    }

    public function updated(Order $order)
    {
        if ($order->isDirty('order_status')) {
            $newStatus = $order->order_status;
            
            if ($order->user_id) {
                $user = \App\Models\User::find($order->user_id);
                if ($user) {
                    // Reward points when delivered
                    if ($newStatus === 'delivered' && $order->points_earned == 0) {
                        $pointsToEarn = (int) floor($order->grand_total / 10000);
                        
                        // Update order
                        $order->points_earned = $pointsToEarn;
                        $order->saveQuietly(); // Use saveQuietly to prevent infinite loops in observer
                        
                        // Update user points
                        $user->points += $pointsToEarn;
                        $user->accumulated_points += $pointsToEarn;
                        $user->save();
                        
                        $user->recalculateMembership();
                    }
                    // Refund points if order is refunded
                    elseif ($newStatus === 'refunded' && $order->points_earned > 0) {
                        $pointsEarned = $order->points_earned;
                        
                        $order->points_earned = 0;
                        $order->saveQuietly();
                        
                        $user->points = max(0, $user->points - $pointsEarned);
                        $user->accumulated_points = max(0, $user->accumulated_points - $pointsEarned);
                        $user->save();
                        
                        $user->recalculateMembership();
                    }

                    // We also keep the old VIP service call just in case it handles other side effects
                    $this->vipService->calculateAndUpgrade($user);
                }
            }
        }
    }
}
