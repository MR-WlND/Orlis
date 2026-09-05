@extends('layouts.client')

@section('title', 'Đặt hàng thành công - Orlis')

@section('styles')
<style>
    .confirm-wrap {
        max-width: 680px;
        margin: 0 auto;
        padding: 120px 20px 80px;
        text-align: center;
    }
    .success-icon {
        width: 72px;
        height: 72px;
        background: #f0fff4;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
    }
    .success-icon svg { width: 36px; height: 36px; stroke: #52c41a; fill: none; stroke-width: 2; }
    .confirm-title {
        font-family: var(--font-serif);
        font-size: 30px;
        font-weight: 500;
        margin-bottom: 10px;
    }
    .confirm-subtitle { font-size: 14px; color: #666; margin-bottom: 40px; line-height: 1.6; }
    .order-card {
        background: white;
        border-radius: 12px;
        padding: 28px;
        text-align: left;
        margin-bottom: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    }
    .order-card-title {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #999;
        margin-bottom: 16px;
        font-weight: 600;
    }
    .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f0f0f0; font-size: 14px; }
    .info-row:last-child { border-bottom: none; }
    .info-label { color: #888; }
    .info-value { font-weight: 500; }
    .order-item { display: flex; gap: 14px; align-items: center; padding: 10px 0; border-bottom: 1px solid #f5f5f5; }
    .order-item:last-child { border-bottom: none; }
    .order-item-img { width: 52px; height: 68px; object-fit: cover; border-radius: 4px; background: #eee; flex-shrink: 0; }
    .order-item-info { flex: 1; font-size: 13px; }
    .order-item-name { font-weight: 600; }
    .order-item-variant { color: #888; margin-top: 2px; }
    .order-item-price { font-weight: 700; font-size: 14px; }
    .summary-row { display: flex; justify-content: space-between; font-size: 14px; padding: 6px 0; }
    .summary-row.grand { font-weight: 700; font-size: 17px; padding-top: 12px; margin-top: 6px; border-top: 1px solid #e0e0e0; }
    .action-btns { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
    .btn-primary-outline { padding: 13px 28px; border: 2px solid #333; background: transparent; color: #333; border-radius: 4px; font-size: 14px; font-weight: 600; cursor: pointer; text-decoration: none; letter-spacing: 0.3px; transition: all 0.2s; }
    .btn-primary-outline:hover { background: #333; color: white; }
    .btn-solid { padding: 13px 28px; background: #333; color: white; border: 2px solid #333; border-radius: 4px; font-size: 14px; font-weight: 600; cursor: pointer; text-decoration: none; letter-spacing: 0.3px; transition: all 0.2s; }
    .btn-solid:hover { background: #111; border-color: #111; }
</style>
@endsection

@section('content')
<div style="background: #f5f5f3; min-height: 100vh;">
<div class="confirm-wrap">

    <div class="success-icon">
        <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
    </div>

    <div class="confirm-title">Đặt hàng thành công!</div>
    <p class="confirm-subtitle">
        Cảm ơn bạn đã tin tưởng Orlis. Chúng tôi sẽ xác nhận và xử lý đơn hàng của bạn sớm nhất có thể.<br>
        Mã đơn hàng của bạn: <strong>{{ $order->order_code }}</strong>
    </p>

    {{-- Order Info --}}
    <div class="order-card">
        <div class="order-card-title">Thông tin đơn hàng</div>
        <div class="info-row">
            <span class="info-label">Mã đơn hàng</span>
            <span class="info-value">{{ $order->order_code }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Ngày đặt</span>
            <span class="info-value">{{ $order->created_at->format('H:i, d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Người nhận</span>
            <span class="info-value">{{ $order->recipient_name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Số điện thoại</span>
            <span class="info-value">{{ $order->recipient_phone }}</span>
        </div>
        @if($order->shipping_address_snapshot)
        <div class="info-row">
            <span class="info-label">Địa chỉ giao hàng</span>
            <span class="info-value" style="text-align:right;max-width:60%;">
                {{ $order->shipping_address_snapshot['detail_address'] ?? '' }},
                {{ $order->shipping_address_snapshot['ward'] ?? '' }},
                {{ $order->shipping_address_snapshot['district'] ?? '' }},
                {{ $order->shipping_address_snapshot['province'] ?? '' }}
            </span>
        </div>
        @endif
        <div class="info-row">
            <span class="info-label">Trạng thái</span>
            <span class="info-value" style="color: {{ $order->status_color }};">{{ $order->status_label }}</span>
        </div>
    </div>

    {{-- Products --}}
    <div class="order-card">
        <div class="order-card-title">Sản phẩm đã đặt</div>
        @foreach($order->items as $item)
        @php $product = $item->variant?->product; @endphp
        <div class="order-item">
            @if($product?->thumbnail)
                <img class="order-item-img" src="{{ Storage::url($product->thumbnail) }}" alt="{{ $item->product_name }}">
            @else
                <div class="order-item-img" style="display:flex;align-items:center;justify-content:center;color:#ccc;font-size:22px;">🧴</div>
            @endif
            <div class="order-item-info">
                <div class="order-item-name">{{ $item->product_name }}</div>
                @if($item->variant_info)
                    <div class="order-item-variant">{{ $item->variant_info }}</div>
                @endif
                <div style="font-size:12px;color:#aaa;margin-top:2px;">× {{ $item->quantity }}</div>
            </div>
            <div class="order-item-price">{{ number_format($item->subtotal, 0, ',', '.') }}₫</div>
        </div>
        @endforeach

        <div style="margin-top: 14px; padding-top: 14px; border-top: 1px solid #f0f0f0;">
            <div class="summary-row">
                <span>Tạm tính</span>
                <span>{{ number_format($order->subtotal, 0, ',', '.') }}₫</span>
            </div>
            @if($order->discount_amount > 0)
            <div class="summary-row" style="color: #52c41a;">
                <span>Giảm giá</span>
                <span>-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</span>
            </div>
            @endif
            <div class="summary-row">
                <span>Phí vận chuyển</span>
                <span style="color: #52c41a;">Miễn phí</span>
            </div>
            <div class="summary-row grand">
                <span>Tổng thanh toán</span>
                <span>{{ number_format($order->grand_total, 0, ',', '.') }}₫</span>
            </div>
        </div>
    </div>

    <div class="action-btns">
        <a href="{{ route('catalog') }}" class="btn-primary-outline">Tiếp tục mua sắm</a>
        <a href="{{ route('customer.orders') }}" class="btn-solid">Xem đơn hàng của tôi</a>
    </div>
</div>
</div>
@endsection
