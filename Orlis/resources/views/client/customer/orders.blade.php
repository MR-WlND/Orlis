@extends('layouts.customer')
@section('customer_title', 'Đơn hàng của tôi - Orlis')
@section('customer_styles')
<style>
    .filter-bar { display: flex; gap: 10px; margin-bottom: 30px; flex-wrap: wrap; }
    .filter-bar select, .filter-bar button, .filter-bar a { padding: 10px 16px; border: 1px solid #ddd; border-radius: 0; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; background: white; color: #555; text-decoration: none; cursor: pointer; transition: 0.3s; }
    .filter-bar button { background: #111; color: white; border-color: #111; font-weight: 600; }
    .filter-bar button:hover { background: #333; }
    .order-card { background: white; border: 1px solid #eee; margin-bottom: 20px; transition: 0.3s; }
    .order-card:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.03); border-color: #ddd; }
    .order-card-head { display: flex; justify-content: space-between; align-items: center; padding: 20px; border-bottom: 1px solid #f9f9f9; }
    .order-code { font-weight: 600; font-size: 14px; color: #111; text-decoration: none; }
    .order-date { font-size: 12px; color: #888; margin-top: 4px; }
    .status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 12px; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
    .order-card-body { padding: 20px; display: flex; gap: 15px; align-items: center; }
    .order-item-img { width: 60px; height: 80px; object-fit: cover; background: #f9f9f9; flex-shrink: 0; }
    .order-card-foot { padding: 15px 20px; background: #fbfbfb; display: flex; justify-content: space-between; align-items: center; font-size: 13px; }
    .order-total { font-weight: 600; font-size: 15px; }
    .btn-outline-sm { padding: 8px 16px; border: 1px solid #ddd; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; text-decoration: none; color: #111; transition: all 0.2s; }
    .btn-outline-sm:hover { border-color: #111; background: #111; color: #fff; }
    .pagination-wrap { display: flex; justify-content: space-between; align-items: center; margin-top: 40px; }
    .pagination-info { font-size: 11px; color: #888; text-transform: uppercase; letter-spacing: 1px; }
    .pagination-btns { display: flex; gap: 8px; }
    .page-btn { padding: 8px 16px; border: 1px solid #ddd; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; text-decoration: none; color: #555; transition: all 0.2s; }
    .page-btn:hover { background: #111; color: white; border-color: #111; }
</style>
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
