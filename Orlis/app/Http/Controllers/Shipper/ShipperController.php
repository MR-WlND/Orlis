<?php

namespace App\Http\Controllers\Shipper;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class ShipperController extends Controller
{
    public function dashboard()
    {
        \Log::info('Shipper accessed dashboard: '.auth()->id());
        $orders = Order::whereIn('order_status', ['delivering', 'completed'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('shipper.dashboard', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:delivering,completed,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'order_status' => $request->status,
            'payment_status' => ($request->status === 'completed' && $order->payment_method === 'cod') ? 'paid' : $order->payment_status,
        ]);

        $order->statusLogs()->create([
            'status' => $request->status,
            'note' => 'Cập nhật bởi Shipper: '.auth()->user()->name,
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Đã cập nhật trạng thái đơn hàng thành công.');
    }
}
