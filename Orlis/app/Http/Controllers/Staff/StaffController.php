<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function dashboard()
    {
        $orders = Order::whereIn('order_status', ['pending', 'processing', 'shipped'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('staff.dashboard', compact('orders'));
    }

    public function processOrder(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $newStatus = 'processing';
        if ($order->order_status === 'pending') {
            $newStatus = 'processing';
        } elseif ($order->order_status === 'processing') {
            $newStatus = 'shipped'; // Chuyển cho kho hoặc vận chuyển
        }

        $order->update(['order_status' => $newStatus]);
        
        $order->statusLogs()->create([
            'status' => $newStatus,
            'note' => 'Xác nhận xử lý bởi NV Bán Hàng: ' . auth()->user()->name,
            'created_by' => auth()->id()
        ]);

        return back()->with('success', 'Đã cập nhật trạng thái đơn hàng thành công.');
    }
}
