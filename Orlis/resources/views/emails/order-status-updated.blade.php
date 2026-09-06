<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Cập nhật đơn hàng - Orlis</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f4;font-family:Georgia,serif;">
<div style="max-width:640px;margin:30px auto;background:#fff;border:1px solid #e0d8d0;">

    <!-- Header -->
    <div style="background:#1a1a1a;padding:32px;text-align:center;">
        <span style="color:#c9a96e;font-size:26px;letter-spacing:6px;font-family:Georgia,serif;">ORLIS</span>
        <p style="color:#888;font-size:11px;letter-spacing:3px;margin:6px 0 0;">LUXURY FASHION</p>
    </div>

    <!-- Status Banner -->
    @php
        $statusConfig = [
            'confirmed'  => ['icon' => '✅', 'label' => 'Đơn hàng đã được xác nhận', 'color' => '#2e7d32', 'bg' => '#e8f5e9'],
            'processing' => ['icon' => '📦', 'label' => 'Đơn hàng đang được chuẩn bị', 'color' => '#e65100', 'bg' => '#fff3e0'],
            'shipping'   => ['icon' => '🚚', 'label' => 'Đơn hàng đang được giao', 'color' => '#1565c0', 'bg' => '#e3f2fd'],
            'delivered'  => ['icon' => '🎁', 'label' => 'Đơn hàng đã được giao thành công', 'color' => '#6a1b9a', 'bg' => '#f3e5f5'],
            'cancelled'  => ['icon' => '❌', 'label' => 'Đơn hàng đã bị hủy', 'color' => '#c62828', 'bg' => '#ffebee'],
        ];
        $config = $statusConfig[$newStatus] ?? ['icon' => 'ℹ️', 'label' => 'Cập nhật đơn hàng', 'color' => '#333', 'bg' => '#f5f5f5'];
    @endphp
    <div style="background:{{ $config['bg'] }};padding:20px 32px;text-align:center;">
        <p style="margin:0;font-size:22px;">{{ $config['icon'] }}</p>
        <p style="margin:6px 0 0;color:{{ $config['color'] }};font-size:16px;font-weight:bold;">{{ $config['label'] }}</p>
    </div>

    <!-- Body -->
    <div style="padding:32px;">
        <p style="color:#333;font-size:15px;margin-top:0;">
            Kính gửi <strong>{{ $order->user->name ?? $order->recipient_name }}</strong>,
        </p>
        <p style="color:#555;line-height:1.8;">
            Chúng tôi muốn thông báo rằng đơn hàng <strong>#{{ $order->order_code }}</strong> của bạn
            đã có cập nhật mới.
        </p>

        @if($note)
        <div style="background:#faf8f5;border-left:3px solid #c9a96e;padding:14px 18px;margin:20px 0;color:#555;font-size:14px;font-style:italic;">
            💬 Ghi chú từ Orlis: "{{ $note }}"
        </div>
        @endif

        <!-- Order Summary -->
        <div style="background:#faf8f5;padding:20px;margin:24px 0;">
            <h3 style="font-size:14px;letter-spacing:2px;color:#888;margin:0 0 16px;text-transform:uppercase;">Thông tin đơn hàng</h3>
            <table style="width:100%;font-size:14px;color:#444;">
                <tr>
                    <td style="padding:5px 0;color:#888;width:40%;">Mã đơn hàng</td>
                    <td style="padding:5px 0;font-weight:bold;">#{{ $order->order_code }}</td>
                </tr>
                <tr>
                    <td style="padding:5px 0;color:#888;">Ngày đặt hàng</td>
                    <td style="padding:5px 0;">{{ $order->created_at->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td style="padding:5px 0;color:#888;">Địa chỉ giao hàng</td>
                    @php
                        $snap = is_array($order->shipping_address_snapshot)
                            ? $order->shipping_address_snapshot
                            : json_decode($order->shipping_address_snapshot, true);
                    @endphp
                    <td style="padding:5px 0;">
                        {{ $snap['detail_address'] ?? '' }}, {{ $snap['ward'] ?? '' }},<br>
                        {{ $snap['district'] ?? '' }}, {{ $snap['province'] ?? '' }}
                    </td>
                </tr>
                <tr>
                    <td style="padding:5px 0;color:#888;">Tổng thanh toán</td>
                    <td style="padding:5px 0;font-size:15px;font-weight:bold;color:#c9a96e;">{{ number_format($order->grand_total, 0, ',', '.') }}₫</td>
                </tr>
            </table>
        </div>

        <!-- Products -->
        <h3 style="font-size:14px;letter-spacing:2px;color:#888;text-transform:uppercase;">Sản phẩm</h3>
        @foreach($order->items as $item)
        <div style="display:flex;justify-content:space-between;border-bottom:1px solid #eee;padding:12px 0;font-size:14px;color:#444;">
            <div>
                <strong>{{ $item->product_name ?? ($item->variant->product->name ?? 'Sản phẩm') }}</strong><br>
                <span style="color:#888;font-size:12px;">SL: {{ $item->quantity }}</span>
            </div>
            <div style="text-align:right;">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫</div>
        </div>
        @endforeach

        <!-- CTA -->
        <div style="margin-top:32px;text-align:center;">
            <a href="{{ url('/customer/orders/' . $order->id) }}"
               style="display:inline-block;background:#1a1a1a;color:#c9a96e;text-decoration:none;padding:14px 36px;letter-spacing:2px;font-size:13px;">
                XEM CHI TIẾT ĐƠN HÀNG →
            </a>
        </div>

        <p style="color:#888;font-size:13px;margin-top:32px;line-height:1.8;text-align:center;">
            Nếu bạn cần hỗ trợ, vui lòng liên hệ chúng tôi qua email<br>
            <a href="mailto:support@orlis.com" style="color:#c9a96e;text-decoration:none;">support@orlis.com</a>
        </p>
    </div>

    <!-- Footer -->
    <div style="background:#1a1a1a;padding:20px 32px;text-align:center;">
        <p style="color:#c9a96e;font-size:13px;letter-spacing:2px;margin:0;">ORLIS LUXURY FASHION</p>
        <p style="color:#555;font-size:11px;margin:6px 0 0;">© {{ date('Y') }} Orlis. All rights reserved.</p>
    </div>
</div>
</body>
</html>
