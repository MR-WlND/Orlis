<!DOCTYPE html>
<html>
<head>
    <title>Xác nhận đơn hàng</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <div style="max-width: 600px; margin: 0 auto; border: 1px solid #ddd; padding: 20px; border-radius: 5px;">
        <h2 style="text-align: center; color: #333;">Cảm ơn bạn đã mua hàng tại Orlis!</h2>
        <p>Chào <strong>{{ $order->user->name }}</strong>,</p>
        <p>Đơn hàng <strong>#{{ $order->order_code }}</strong> của bạn đã được xác nhận và đang được xử lý.</p>
        
        <div style="background-color: #f9f9f9; padding: 15px; border-radius: 4px; margin: 20px 0;">
            <h3 style="margin-top: 0;">Thông tin giao hàng:</h3>
            <p style="margin-bottom: 5px;">Người nhận: {{ $order->shipping_address['recipient_name'] }} - {{ $order->shipping_address['recipient_phone'] }}</p>
            <p style="margin-bottom: 0;">Địa chỉ: {{ $order->shipping_address['detail_address'] }}, {{ $order->shipping_address['ward'] }}, {{ $order->shipping_address['district'] }}, {{ $order->shipping_address['province'] }}</p>
        </div>

        <h3>Chi tiết đơn hàng:</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="border-bottom: 1px solid #ddd; padding: 8px; text-align: left;">Sản phẩm</th>
                    <th style="border-bottom: 1px solid #ddd; padding: 8px; text-align: center;">SL</th>
                    <th style="border-bottom: 1px solid #ddd; padding: 8px; text-align: right;">Giá</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td style="border-bottom: 1px solid #eee; padding: 8px;">
                        {{ $item->variant->product->name ?? 'Sản phẩm' }}<br>
                        <small style="color: #666;">{{ $item->variant->display_name }}</small>
                    </td>
                    <td style="border-bottom: 1px solid #eee; padding: 8px; text-align: center;">{{ $item->quantity }}</td>
                    <td style="border-bottom: 1px solid #eee; padding: 8px; text-align: right;">{{ number_format($item->subtotal, 0, ',', '.') }}₫</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="text-align: right; margin-top: 20px;">
            <p><strong>Tổng phụ:</strong> {{ number_format($order->subtotal, 0, ',', '.') }}₫</p>
            @if($order->discount_amount > 0)
                <p style="color: #dc3545;"><strong>Giảm giá:</strong> -{{ number_format($order->discount_amount, 0, ',', '.') }}₫</p>
            @endif
            <p style="font-size: 18px;"><strong>Tổng thanh toán:</strong> {{ number_format($order->grand_total, 0, ',', '.') }}₫</p>
        </div>

        <p style="margin-top: 30px; text-align: center; color: #777; font-size: 13px;">
            Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi qua email support@orlis.com.
        </p>
    </div>
</body>
</html>
