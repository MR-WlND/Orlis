@extends('layouts.customer')
@section('customer_title', 'Chi tiết đơn hàng - Orlis')
@section('customer_styles')
@endsection
@section('customer_content')
<a href="{{ route('customer.orders') }}" class="back-link">← Quay lại danh sách đơn hàng</a>

@php
    $statusFlow = ['pending', 'confirmed', 'processing', 'shipping', 'delivered'];
    $currentIdx = array_search($order->order_status, $statusFlow);
@endphp

<div class="section-header">
    <div>
        <div class="subtitle">CHI TIẾT ĐƠN HÀNG</div>
        <h2 class="section-title">{{ $order->order_code }}</h2>
        <div style="font-size:12px;color:#888;margin-top:5px;">Ngày đặt: {{ $order->created_at->format('H:i, d/m/Y') }}</div>
    </div>
    <span class="status-badge" style="color:{{ $order->status_color }};background:{{ $order->status_color }}15;border: 1px solid {{ $order->status_color }}33;">
        {{ $order->status_label }}
    </span>
</div>

{{-- Progress steps (chỉ hiện nếu không phải cancelled/refunded) --}}
@if(!in_array($order->order_status, ['cancelled', 'refunded']))
<div class="card" style="padding: 30px 40px;">
    <div class="steps">
        @foreach($statusFlow as $i => $step)
        @php
            $isDone = $currentIdx !== false && $i < $currentIdx;
            $isActive = $currentIdx !== false && $i === $currentIdx;
        @endphp
        <div class="step">
            <div class="step-dot {{ $isDone ? 'done' : ($isActive ? 'active' : '') }}">
                @if($isDone) ✓ @else {{ $i + 1 }} @endif
            </div>
            <div class="step-label {{ $isDone ? 'done' : ($isActive ? 'active' : '') }}">
                {{ \App\Models\Order::STATUSES[$step] ?? $step }}
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Sản phẩm --}}
<div class="card">
    <div class="card-title">Sản phẩm đặt mua</div>
    @foreach($order->items as $item)
    @php $product = $item->variant?->product; @endphp
    <div class="order-item">
        @if($product?->thumbnail)
            <a href="{{ route('product', $product->id) }}">
                <img src="{{ Storage::url($product->thumbnail) }}" class="item-img" alt="{{ $item->product_name }}">
            </a>
        @else
            <div class="item-img" style="display:flex;align-items:center;justify-content:center;color:#ccc;font-size:22px;">🧴</div>
        @endif
        <div class="item-info">
            @if($product)
                <a href="{{ route('product', $product->id) }}" class="item-name" style="text-decoration: none; color: inherit; display: block;">{{ $item->product_name }}</a>
            @else
                <div class="item-name">{{ $item->product_name }}</div>
            @endif
            @if($item->variant_info)<div class="item-variant">{{ $item->variant_info }}</div>@endif
            <div style="font-size:11px;color:#888;text-transform:uppercase;letter-spacing:1px;margin-top:4px;">Số lượng: {{ $item->quantity }}</div>
        </div>
        <div class="item-total">{{ number_format($item->subtotal, 0, ',', '.') }}₫</div>
    </div>
    @endforeach
    <div style="padding-top:20px;margin-top:10px;">
        <div class="summary-row"><span class="info-label">Tạm tính</span><span>{{ number_format($order->subtotal, 0, ',', '.') }}₫</span></div>
        @if($order->discount_amount > 0)
        <div class="summary-row" style="color:#28a745;"><span class="info-label">Giảm giá</span><span>-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</span></div>
        @endif
        <div class="summary-row"><span class="info-label">Phí vận chuyển</span><span>Miễn phí</span></div>
        <div class="summary-row grand"><span>Tổng thanh toán</span><span>{{ number_format($order->grand_total, 0, ',', '.') }}₫</span></div>
    </div>
</div>

{{-- Địa chỉ giao hàng --}}
<div class="card">
    <div class="card-title">Thông tin giao hàng</div>
    <div class="info-row"><span class="info-label">Người nhận</span><span style="font-weight:600;">{{ $order->recipient_name }}</span></div>
    <div class="info-row"><span class="info-label">SĐT liên hệ</span><span>{{ $order->recipient_phone }}</span></div>
    @if($order->shipping_address_snapshot)
    <div class="info-row" style="align-items:flex-start;">
        <span class="info-label">Địa chỉ nhận hàng</span>
        <span style="text-align:right;max-width:60%;line-height:1.5;">
            {{ $order->shipping_address_snapshot['detail_address'] ?? '' }}<br>
            {{ $order->shipping_address_snapshot['ward'] ?? '' }}, {{ $order->shipping_address_snapshot['district'] ?? '' }}<br>
            {{ $order->shipping_address_snapshot['province'] ?? '' }}
        </span>
    </div>
    @endif
</div>
@endsection
