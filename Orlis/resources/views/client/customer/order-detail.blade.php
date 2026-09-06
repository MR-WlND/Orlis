@extends('layouts.customer')
@section('customer_title', 'Chi tiết đơn hàng - Orlis')
@section('customer_styles')
<style>
    .back-link { display: inline-flex; align-items: center; gap: 6px; color: #888; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; text-decoration: none; margin-bottom: 25px; transition: 0.3s; }
    .back-link:hover { color: #111; }
    .card { background: white; border: 1px solid #eee; padding: 30px; margin-bottom: 20px; }
    .card-title { font-family: var(--font-serif); font-size: 18px; color: #111; margin-bottom: 20px; font-weight: 400; border-bottom: 1px solid #f9f9f9; padding-bottom: 15px; }
    .order-item { display: flex; gap: 15px; align-items: center; padding: 15px 0; border-bottom: 1px solid #f9f9f9; }
    .order-item:last-child { border-bottom: none; }
    .item-img { width: 60px; height: 80px; object-fit: cover; background: #f9f9f9; flex-shrink: 0; }
    .item-info { flex: 1; font-size: 13px; }
    .item-name { font-weight: 600; font-size: 14px; margin-bottom: 4px; }
    .item-variant { color: #888; font-size: 12px; margin-bottom: 4px; }
    .item-total { font-weight: 600; font-size: 14px; }
    .info-row { display: flex; justify-content: space-between; font-size: 13px; padding: 10px 0; border-bottom: 1px solid #f9f9f9; }
    .info-row:last-child { border-bottom: none; }
    .info-label { color: #888; text-transform: uppercase; letter-spacing: 1px; font-size: 11px; }
    .summary-row { display: flex; justify-content: space-between; font-size: 13px; padding: 8px 0; }
    .summary-row.grand { font-weight: 600; font-size: 16px; padding-top: 15px; margin-top: 10px; border-top: 1px solid #eee; text-transform: uppercase; letter-spacing: 1px; }
    .status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 6px 14px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
    .steps { display: flex; gap: 0; margin: 20px 0; }
    .step { flex: 1; text-align: center; position: relative; }
    .step::before { content: ''; position: absolute; top: 12px; right: -50%; width: 100%; height: 1px; background: #eee; z-index: 0; }
    .step:last-child::before { display: none; }
    .step-dot { width: 24px; height: 24px; border-radius: 50%; background: #eee; margin: 0 auto 10px; display: flex; align-items: center; justify-content: center; position: relative; z-index: 1; font-size: 10px; font-weight: 600; color: #aaa; }
    .step-dot.done { background: #111; color: white; }
    .step-dot.active { background: #d4af37; color: white; }
    .step-label { font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: #aaa; }
    .step-label.done { color: #111; font-weight: 600; }
    .step-label.active { color: #d4af37; font-weight: 600; }
</style>
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
