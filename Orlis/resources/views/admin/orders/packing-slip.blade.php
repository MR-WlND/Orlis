<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Phiếu Đóng Gói - {{ $order->order_code }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0; padding: 0; color: #000; }
        .page { width: 105mm; /* A6 width roughly */ min-height: 148mm; margin: 0 auto; padding: 15mm; background: white; border: 1px solid #ddd; box-sizing: border-box; }
        @media print {
            body { background: none; }
            .page { border: none; margin: 0; width: 100%; height: 100%; padding: 5mm; }
            .no-print { display: none; }
        }
        .header { text-align: center; border-bottom: 2px dashed #000; padding-bottom: 10px; margin-bottom: 15px; }
        .logo { font-size: 24px; font-weight: 700; letter-spacing: 2px; font-family: Georgia, serif; text-transform: uppercase; }
        .barcode { margin-top: 10px; font-family: 'Courier New', Courier, monospace; font-size: 16px; font-weight: bold; }
        .info-block { margin-bottom: 15px; }
        .info-title { font-weight: bold; border-bottom: 1px solid #000; margin-bottom: 5px; padding-bottom: 2px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border-bottom: 1px dotted #999; padding: 5px 0; text-align: left; }
        th { font-weight: bold; border-bottom: 1px solid #000; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { text-align: center; font-size: 12px; margin-top: 20px; border-top: 2px dashed #000; padding-top: 10px; }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="text-align:center; padding: 20px; background: #f0f0f0;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">🖨️ IN PHIẾU ĐÓNG GÓI</button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">Đóng</button>
    </div>

    <div class="page">
        <div class="header">
            <div class="logo">ORLIS PRIVÉ</div>
            <div>Phiếu Giao Hàng (Packing Slip)</div>
            <div class="barcode">*{{ $order->order_code }}*</div>
            <div>Ngày tạo: {{ date('d/m/Y H:i') }}</div>
        </div>

        <div class="info-block">
            <div class="info-title">Người nhận (Khách hàng)</div>
            <div><strong>Tên:</strong> {{ $order->customer_name ?: $order->user->name }}</div>
            <div><strong>SĐT:</strong> {{ $order->customer_phone ?: $order->user->phone }}</div>
            <div><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</div>
        </div>

        <div class="info-block">
            <div class="info-title">Chi tiết đơn hàng</div>
            <table>
                <thead>
                    <tr>
                        <th width="70%">Sản phẩm</th>
                        <th width="30%" class="text-center">SL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->productVariant->product->name }}<br><small>{{ $item->productVariant->size }} - {{ $item->productVariant->color }}</small></td>
                        <td class="text-center"><strong>{{ $item->quantity }}</strong></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="info-block" style="border: 2px solid #000; padding: 10px; text-align: center;">
            <div style="font-size: 12px; margin-bottom: 5px;">Số tiền thu hộ (COD):</div>
            <div style="font-size: 20px; font-weight: bold;">
                @if($order->payment_method == 'cod' && $order->payment_status == 'unpaid')
                    {{ number_format($order->grand_total, 0, ',', '.') }} ₫
                @else
                    0 ₫ (Đã thanh toán)
                @endif
            </div>
        </div>

        <div class="footer">
            Cảm ơn Quý khách đã mua sắm tại Orlis.<br>
            Hotline: 1800 6868 - Website: orlis.com
        </div>
    </div>
</body>
</html>
