<?php

namespace App\Http\Controllers\Shipper;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class ShipperController extends Controller
{
    public function dashboard()
    {
        $shippingMethodId = auth()->user()->shipping_method_id;

        $baseQuery = Order::query();
        if ($shippingMethodId) {
            $baseQuery->where('shipping_method_id', $shippingMethodId);
        }

        $totalCodHeld = (clone $baseQuery)
            ->where('order_status', 'delivered')
            ->where('remaining_amount', '>', 0)
            ->sum('remaining_amount');

        $countShipping = (clone $baseQuery)->where('order_status', 'shipping')->count();
        $countDelivered = (clone $baseQuery)->where('order_status', 'delivered')->count();
        $countFailed = (clone $baseQuery)->where('order_status', 'cancelled')->count();

        $stats = [
            'cod_held' => $totalCodHeld,
            'shipping' => $countShipping,
            'delivered' => $countDelivered,
            'failed' => $countFailed,
        ];

        return view('shipper.dashboard', compact('stats'));
    }

    public function orders()
    {
        $query = Order::whereIn('order_status', ['shipping', 'delivered']);

        if (auth()->user()->shipping_method_id) {
            $query->where('shipping_method_id', auth()->user()->shipping_method_id);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('shipper.orders', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:shipping,delivered,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'order_status' => $request->status,
        ]);

        $order->statusLogs()->create([
            'from_status' => $order->getOriginal('order_status'),
            'to_status'   => $request->status,
            'reason'      => 'Cập nhật bởi Shipper: '.auth()->user()->name,
            'admin_id'    => auth()->id(),
        ]);

        return back()->with('success', 'Đã cập nhật trạng thái đơn hàng thành công.');
    }
}
