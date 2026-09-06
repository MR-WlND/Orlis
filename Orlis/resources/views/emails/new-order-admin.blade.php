<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đơn hàng mới - Orlis Admin</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f4;font-family:Georgia,serif;">
<div style="max-width:640px;margin:30px auto;background:#fff;border:1px solid #e0d8d0;">

    <!-- Header -->
    <div style="background:#1a1a1a;padding:24px 32px;text-align:center;">
        <span style="color:#c9a96e;font-size:22px;letter-spacing:4px;font-family:Georgia,serif;">ORLIS</span>
        <p style="color:#888;font-size:12px;letter-spacing:2px;margin:4px 0 0;">ADMIN NOTIFICATION</p>
    </div>

    <!-- Body -->
    <div style="padding:32px;">
        <h2 style="font-size:20px;color:#1a1a1a;margin-top:0;">🛍️ Có đơn hàng mới cần xác nhận</h2>

        <div style="background:#faf8f5;border-left:3px solid #c9a96e;padding:16px 20px;margin:20px 0;">
            <table style="width:100%;font-size:14px;color:#444;">
                <tr>
                    <td style="padding:5px 0;width:40%;color:#888;">Mã đơn hàng</td>
                    <td style="padding:5px 0;font-weight:bold;color:#1a1a1a;">#{{ $order->order_code }}</td>
                </tr>
                <tr>
                    <td style="padding:5px 0;color:#888;">Khách hàng</td>
                    <td style="padding:5px 0;">{{ $order->user->name ?? 'Khách vãng lai' }}</td>
                </tr>
                <tr>
                    <td style="padding:5px 0;color:#888;">Số điện thoại</td>
                    <td style="padding:5px 0;">{{ $order->recipient_phone }}</td>
                </tr>
                <tr>
                    <td style="padding:5px 0;color:#888;">Phương thức TT</td>
                    @php
                        $snap = is_array($order->shipping_address_snapshot)
                            ? $order->shipping_address_snapshot
                            : json_decode($order->shipping_address_snapshot, true);
                    @endphp
                    <td style="padding:5px 0;">{{ strtoupper($snap['payment_method'] ?? 'cod') }}</td>
                </tr>
                <tr>
                    <td style="padding:5px 0;color:#888;">Tổng tiền</td>
                    <td style="padding:5px 0;font-size:16px;font-weight:bold;color:#c9a96e;">{{ number_format($order->grand_total, 0, ',', '.') }}₫</td>
                </tr>
                <tr>
                    <td style="padding:5px 0;color:#888;">Thời gian</td>
                    <td style="padding:5px 0;">{{ $order->created_at->format('H:i — d/m/Y') }}</td>
                </tr>
            </table>
        </div>

        <h3 style="font-size:15px;color:#1a1a1a;">Sản phẩm trong đơn:</h3>
        <table style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead>
                <tr style="background:#f4f4f4;">
                    <th style="padding:8px 10px;text-align:left;color:#666;font-weight:normal;">Sản phẩm</th>
                    <th style="padding:8px 10px;text-align:center;color:#666;font-weight:normal;">SL</th>
                    <th style="padding:8px 10px;text-align:right;color:#666;font-weight:normal;">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:10px;">
                        {{ $item->product_name ?? ($item->variant->product->name ?? 'Sản phẩm') }}
                    </td>
                    <td style="padding:10px;text-align:center;">{{ $item->quantity }}</td>
                    <td style="padding:10px;text-align:right;">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="text-align:right;margin-top:16px;font-size:14px;">
            <p style="margin:4px 0;">Tổng phụ: <strong>{{ number_format($order->subtotal, 0, ',', '.') }}₫</strong></p>
            @if($order->discount_amount > 0)
            <p style="margin:4px 0;color:#c0392b;">Giảm giá: <strong>-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</strong></p>
            @endif
            <p style="font-size:16px;margin:8px 0;color:#c9a96e;">Tổng thanh toán: <strong>{{ number_format($order->grand_total, 0, ',', '.') }}₫</strong></p>
        </div>

        <div style="margin-top:28px;text-align:center;">
            <a href="{{ url('/admin/orders/' . $order->id) }}"
               style="display:inline-block;background:#1a1a1a;color:#c9a96e;text-decoration:none;padding:13px 32px;letter-spacing:2px;font-size:13px;">
                XEM & XÁC NHẬN ĐƠN HÀNG →
            </a>
        </div>
    </div>

    <!-- Footer -->
    <div style="background:#f9f9f9;padding:16px 32px;text-align:center;border-top:1px solid #eee;">
        <p style="color:#aaa;font-size:12px;margin:0;">Orlis Luxury Fashion · orlis.com</p>
    </div>
</div>
</body>
</html>
