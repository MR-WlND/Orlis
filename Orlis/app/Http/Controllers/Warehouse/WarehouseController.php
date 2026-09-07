<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function dashboard()
    {
        $orders = Order::whereIn('order_status', ['shipped', 'delivering'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('warehouse.dashboard', compact('orders'));
    }

    public function markAsDelivering(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $order->update(['order_status' => 'delivering']);
        
        $order->statusLogs()->create([
            'status' => 'delivering',
            'note' => 'Xuất kho và bàn giao cho ĐVVC bởi NV Kho: ' . auth()->user()->name,
            'created_by' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Đơn hàng đã được chuyển cho đơn vị vận chuyển.');
    }

    public function printPackingSlip($id)
    {
        $order = Order::with(['items.productVariant.product', 'user'])->findOrFail($id);
        return view('admin.orders.packing-slip', compact('order'));
    }
}
