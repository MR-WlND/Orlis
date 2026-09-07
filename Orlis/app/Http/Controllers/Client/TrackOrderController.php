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
            'phone'      => 'required|string'
        ]);

        $order = Order::with([
            'items.variant.product',
            'statusLogs',
            'shippingMethod',
        ])->where('order_code', $request->order_code)->first();

        if (!$order) {
            return back()->with('error', 'Không tìm thấy đơn hàng với mã này. Vui lòng kiểm tra lại.');
        }

        // Validate phone number
        $shippingPhone = $order->recipient_phone
            ?? ($order->shipping_address_snapshot['recipient_phone'] ?? '');

        if ($shippingPhone !== $request->phone) {
            return back()->with('error', 'Số điện thoại không khớp với thông tin đơn hàng.');
        }

        return view('client.track-order', compact('order'));
    }
}
