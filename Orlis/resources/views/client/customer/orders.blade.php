@extends('layouts.customer')
@section('customer_title', 'Đơn hàng của tôi - Orlis')
@section('customer_styles')
@endsection
@section('customer_content')
<div class="section-header">
    <div>
        <div class="subtitle">NHẬT KÝ MUA SẮM</div>
        <h2 class="section-title">Đơn hàng của tôi</h2>
    </div>
</div>

<form method="GET" action="{{ route('customer.orders') }}" class="filter-bar">
    <select name="status">
        <option value="">-- Tất cả trạng thái --</option>
        @foreach($statuses as $key => $label)
            <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
        @endforeach
    </select>
    <button type="submit">Lọc</button>
    @if(request('status'))
        <a href="{{ route('customer.orders') }}">Xóa lọc</a>
    @endif
</form>

@forelse($orders as $order)
<div class="order-card">
    <div class="order-card-head">
        <div>
            <a href="{{ route('customer.order-detail', $order) }}" class="order-code">{{ $order->order_code }}</a>
            <div class="order-date">{{ $order->created_at->format('H:i, d/m/Y') }}</div>
        </div>
        <span class="status-badge" style="color:{{ $order->status_color }};background:{{ $order->status_color }}15;border: 1px solid {{ $order->status_color }}33;">{{ $order->status_label }}</span>
    </div>
    <div class="order-card-body">
        @foreach($order->items->take(3) as $item)
        @php 
            $img = $item->variant?->product?->thumbnail; 
            $productId = $item->variant?->product_id;
        @endphp
        @if($img && $productId)
            <a href="{{ route('product', $productId) }}" title="{{ $item->product_name }}">
                <img src="{{ Storage::url($img) }}" class="order-item-img" alt="{{ $item->product_name }}">
            </a>
        @elseif($img)
            <img src="{{ Storage::url($img) }}" class="order-item-img" alt="{{ $item->product_name }}">
        @else
            <div class="order-item-img" style="display:flex;align-items:center;justify-content:center;color:#ccc;font-size:20px;">🧴</div>
        @endif
        @endforeach
        @if($order->items->count() > 3)
            <div style="font-size:12px;color:#888; margin-left: 10px;">+{{ $order->items->count() - 3 }} sản phẩm khác</div>
        @endif
    </div>
    <div class="order-card-foot">
        <div style="font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">{{ $order->items->count() }} sản phẩm</div>
        <div class="order-total">{{ number_format($order->grand_total, 0, ',', '.') }}₫</div>
        <a href="{{ route('customer.order-detail', $order) }}" class="btn-outline-sm">Xem chi tiết</a>
    </div>
</div>
@empty
<div class="orders-empty">
    <svg class="empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 01-8 0"></path></svg>
    <div class="empty-text">Quý khách hiện chưa có đơn hàng nào.</div>
    <a href="{{ route('catalog') }}" class="btn-dark">Khám phá sản phẩm</a>
</div>
@endforelse

@if($orders->hasPages())
<div class="pagination-wrap">
    <div class="pagination-info">Trang {{ $orders->currentPage() }}/{{ $orders->lastPage() }}</div>
    <div class="pagination-btns">
        @if(!$orders->onFirstPage())
            <a href="{{ $orders->previousPageUrl() }}" class="page-btn">← Trước</a>
        @endif
        @if($orders->hasMorePages())
            <a href="{{ $orders->nextPageUrl() }}" class="page-btn">Sau →</a>
        @endif
    </div>
</div>
@endif
@endsection
