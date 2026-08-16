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
            $oldStatus = $order->getOriginal('order_status');
            $newStatus = $order->order_status;

            if ($newStatus === 'delivered' || $oldStatus === 'delivered') {
                if ($order->user_id) {
                    $user = \App\Models\User::find($order->user_id);
                    if ($user) {
                        $this->vipService->calculateAndUpgrade($user);
                    }
                }
            }
        }
    }
}
