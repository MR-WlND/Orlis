@extends('layouts.client')
@section('title', 'Chi tiết đơn hàng - Orlis')
@section('styles')
<style>
    .customer-wrap { max-width: 1060px; margin: 0 auto; padding: 100px 20px 60px; display: grid; grid-template-columns: 220px 1fr; gap: 32px; }
    .sidebar-nav { position: sticky; top: 90px; height: fit-content; }
    .sidebar-nav .user-info { padding-bottom: 20px; border-bottom: 1px solid #e8e8e8; margin-bottom: 16px; }
    .avatar-circle { width: 56px; height: 56px; border-radius: 50%; background: #f0ece6; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 20px; color: var(--primary); margin-bottom: 10px; overflow: hidden; }
    .user-name { font-weight: 600; font-size: 15px; margin-bottom: 2px; }
    .user-level { font-size: 12px; color: #999; }
    .nav-link { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 6px; text-decoration: none; color: #555; font-size: 14px; transition: all 0.15s; margin-bottom: 2px; }
    .nav-link:hover, .nav-link.active { background: #f5f0ea; color: var(--primary); font-weight: 500; }
    .nav-link svg { width: 16px; height: 16px; stroke: currentColor; fill: none; stroke-width: 2; flex-shrink: 0; }
    .back-link { display: inline-flex; align-items: center; gap: 6px; color: #999; font-size: 13px; text-decoration: none; margin-bottom: 16px; }
    .back-link:hover { color: var(--primary); }
    .card { background: white; border-radius: 10px; border: 1px solid #efefef; padding: 22px; margin-bottom: 16px; }
    .card-title { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #aaa; margin-bottom: 16px; font-weight: 600; }
    .order-item { display: flex; gap: 14px; align-items: center; padding: 10px 0; border-bottom: 1px solid #f5f5f5; }
    .order-item:last-child { border-bottom: none; }
    .item-img { width: 52px; height: 68px; object-fit: cover; border-radius: 4px; background: #f0f0f0; flex-shrink: 0; }
    .item-info { flex: 1; font-size: 13px; }
    .item-name { font-weight: 600; }
    .item-variant { color: #aaa; font-size: 12px; margin-top: 2px; }
    .item-total { font-weight: 700; font-size: 14px; }
    .info-row { display: flex; justify-content: space-between; font-size: 13px; padding: 7px 0; border-bottom: 1px solid #f5f5f5; }
    .info-row:last-child { border-bottom: none; }
    .info-label { color: #aaa; }
    .summary-row { display: flex; justify-content: space-between; font-size: 14px; padding: 5px 0; }
    .summary-row.grand { font-weight: 700; font-size: 16px; padding-top: 12px; margin-top: 6px; border-top: 1px solid #efefef; }
    .status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .steps { display: flex; gap: 0; margin: 16px 0; }
    .step { flex: 1; text-align: center; position: relative; }
    .step::before { content: ''; position: absolute; top: 12px; right: -50%; width: 100%; height: 2px; background: #e0e0e0; z-index: 0; }
    .step:last-child::before { display: none; }
    .step-dot { width: 24px; height: 24px; border-radius: 50%; background: #e0e0e0; margin: 0 auto 6px; display: flex; align-items: center; justify-content: center; position: relative; z-index: 1; font-size: 10px; }
    .step-dot.done { background: #52c41a; color: white; }
    .step-dot.active { background: var(--primary); color: white; }
    .step-label { font-size: 11px; color: #aaa; }
    .step-label.done, .step-label.active { color: #333; }
    @media(max-width: 768px) { .customer-wrap { grid-template-columns: 1fr; } .sidebar-nav { position: static; } }
</style>
@endsection
@section('content')
<div style="background: #f8f7f5; min-height: 100vh;">
<div class="customer-wrap">
    @include('client.customer._sidebar')
    <div>
        <a href="{{ route('customer.orders') }}" class="back-link">← Quay lại đơn hàng</a>

        @php
            $statusFlow = ['pending', 'confirmed', 'processing', 'shipping', 'delivered'];
            $currentIdx = array_search($order->order_status, $statusFlow);
        @endphp

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <div>
                <div style="font-family:var(--font-serif);font-size:22px;font-weight:500;">{{ $order->order_code }}</div>
                <div style="font-size:12px;color:#aaa;margin-top:3px;">Đặt ngày {{ $order->created_at->format('H:i, d/m/Y') }}</div>
            </div>
            <span class="status-badge" style="color:{{ $order->status_color }};background:{{ $order->status_color }}22;font-size:13px;padding:6px 14px;">
                {{ $order->status_label }}
            </span>
        </div>

        {{-- Progress steps (chỉ hiện nếu không phải cancelled/refunded) --}}
        @if(!in_array($order->order_status, ['cancelled', 'refunded']))
        <div class="card" style="padding:20px 28px;">
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
                    <img src="{{ Storage::url($product->thumbnail) }}" class="item-img" alt="{{ $item->product_name }}">
                @else
                    <div class="item-img" style="display:flex;align-items:center;justify-content:center;color:#ccc;font-size:22px;">🧴</div>
                @endif
                <div class="item-info">
                    <div class="item-name">{{ $item->product_name }}</div>
                    @if($item->variant_info)<div class="item-variant">{{ $item->variant_info }}</div>@endif
                    <div style="font-size:12px;color:#aaa;margin-top:2px;">Số lượng: {{ $item->quantity }}</div>
                </div>
                <div class="item-total">{{ number_format($item->subtotal, 0, ',', '.') }}₫</div>
            </div>
            @endforeach
            <div style="padding-top:14px;border-top:1px solid #f5f5f5;margin-top:6px;">
                <div class="summary-row"><span>Tạm tính</span><span>{{ number_format($order->subtotal, 0, ',', '.') }}₫</span></div>
                @if($order->discount_amount > 0)
                <div class="summary-row" style="color:#52c41a;"><span>Giảm giá</span><span>-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</span></div>
                @endif
                <div class="summary-row"><span>Phí vận chuyển</span><span style="color:#52c41a;">Miễn phí</span></div>
                <div class="summary-row grand"><span>Tổng thanh toán</span><span style="color:var(--primary);">{{ number_format($order->grand_total, 0, ',', '.') }}₫</span></div>
            </div>
        </div>

        {{-- Địa chỉ giao hàng --}}
        <div class="card">
            <div class="card-title">Địa chỉ giao hàng</div>
            <div class="info-row"><span class="info-label">Người nhận</span><span style="font-weight:500;">{{ $order->recipient_name }}</span></div>
            <div class="info-row"><span class="info-label">SĐT</span><span>{{ $order->recipient_phone }}</span></div>
            @if($order->shipping_address_snapshot)
            <div class="info-row">
                <span class="info-label">Địa chỉ</span>
                <span style="text-align:right;max-width:60%;">
                    {{ $order->shipping_address_snapshot['detail_address'] ?? '' }},
                    {{ $order->shipping_address_snapshot['ward'] ?? '' }},
                    {{ $order->shipping_address_snapshot['district'] ?? '' }},
                    {{ $order->shipping_address_snapshot['province'] ?? '' }}
                </span>
            </div>
            @endif
        </div>
    </div>
</div>
</div>
@endsection
