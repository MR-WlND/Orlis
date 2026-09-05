<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class TrackOrderController extends Controller
{
    public function index()
    {
        return view('client.track-order');
    }

    public function track(Request $request)
    {
        $request->validate([
            'order_code' => 'required|string',
            'phone' => 'required|string'
        ]);

        $order = Order::with(['items.variant.product'])->where('order_code', $request->order_code)->first();

        if (!$order) {
            return back()->with('error', 'Không tìm thấy đơn hàng với mã này.');
        }

        // Validate phone number inside JSON
        $shippingPhone = $order->shipping_address['recipient_phone'] ?? '';
        if ($shippingPhone !== $request->phone) {
            return back()->with('error', 'Số điện thoại không khớp với thông tin đơn hàng.');
        }

        return view('client.track-order', compact('order'));
    }
}
