@extends('layouts.client')
@section('title', 'Đơn hàng của tôi - Orlis')
@section('styles')
<style>
    .customer-wrap { max-width: 1060px; margin: 0 auto; padding: 100px 20px 60px; display: grid; grid-template-columns: 220px 1fr; gap: 32px; }
    .sidebar-nav { position: sticky; top: 90px; height: fit-content; }
    .sidebar-nav .user-info { padding-bottom: 20px; border-bottom: 1px solid #e8e8e8; margin-bottom: 16px; }
    .avatar-circle { width: 56px; height: 56px; border-radius: 50%; object-fit: cover; background: #f0ece6; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 20px; color: var(--primary); margin-bottom: 10px; overflow: hidden; }
    .user-name { font-weight: 600; font-size: 15px; margin-bottom: 2px; }
    .user-level { font-size: 12px; color: #999; }
    .nav-link { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 6px; text-decoration: none; color: #555; font-size: 14px; transition: all 0.15s; margin-bottom: 2px; }
    .nav-link:hover, .nav-link.active { background: #f5f0ea; color: var(--primary); font-weight: 500; }
    .nav-link svg { width: 16px; height: 16px; stroke: currentColor; fill: none; stroke-width: 2; flex-shrink: 0; }
    .section-title { font-family: var(--font-serif); font-size: 18px; font-weight: 500; margin-bottom: 18px; }
    .filter-bar { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
    .filter-bar select, .filter-bar button, .filter-bar a { padding: 8px 14px; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 13px; background: white; color: #555; text-decoration: none; cursor: pointer; }
    .filter-bar button { background: #444; color: white; border-color: #444; }
    .order-card { background: white; border-radius: 10px; border: 1px solid #efefef; margin-bottom: 14px; overflow: hidden; }
    .order-card-head { display: flex; justify-content: space-between; align-items: center; padding: 14px 18px; border-bottom: 1px solid #f5f5f5; }
    .order-code { font-weight: 600; font-size: 14px; color: var(--primary); text-decoration: none; }
    .order-date { font-size: 12px; color: #aaa; margin-top: 2px; }
    .status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
    .order-card-body { padding: 14px 18px; display: flex; gap: 14px; align-items: center; }
    .order-item-img { width: 48px; height: 64px; object-fit: cover; border-radius: 4px; background: #f5f5f5; flex-shrink: 0; }
    .order-card-foot { padding: 12px 18px; background: #fafafa; display: flex; justify-content: space-between; align-items: center; font-size: 13px; }
    .order-total { font-weight: 700; font-size: 15px; }
    .btn-outline-sm { padding: 6px 14px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 12px; font-weight: 500; text-decoration: none; color: #444; transition: all 0.15s; }
    .btn-outline-sm:hover { border-color: var(--primary); color: var(--primary); }
    .pagination-wrap { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; }
    .pagination-info { font-size: 12px; color: #aaa; }
    .pagination-btns { display: flex; gap: 8px; }
    .page-btn { padding: 8px 14px; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 12px; text-decoration: none; color: #555; transition: all 0.15s; }
    .page-btn:hover { background: var(--primary); color: white; border-color: var(--primary); }
    @media(max-width: 768px) { .customer-wrap { grid-template-columns: 1fr; } .sidebar-nav { position: static; } }
</style>
@endsection
@section('content')
<div style="background: #f8f7f5; min-height: 100vh;">
<div class="customer-wrap">
    @include('client.customer._sidebar')
    <div>
        <h2 class="section-title">Đơn hàng của tôi</h2>

        <form method="GET" action="{{ route('customer.orders') }}" class="filter-bar">
            <select name="status">
                <option value="">-- Tất cả --</option>
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
                <span class="status-badge" style="color:{{ $order->status_color }};background:{{ $order->status_color }}22;">{{ $order->status_label }}</span>
            </div>
            <div class="order-card-body">
                @foreach($order->items->take(3) as $item)
                @php $img = $item->variant?->product?->thumbnail; @endphp
                @if($img)
                    <img src="{{ Storage::url($img) }}" class="order-item-img" alt="{{ $item->product_name }}">
                @else
                    <div class="order-item-img" style="display:flex;align-items:center;justify-content:center;color:#ccc;font-size:20px;">🧴</div>
                @endif
                @endforeach
                @if($order->items->count() > 3)
                    <div style="font-size:13px;color:#aaa;">+{{ $order->items->count() - 3 }} sản phẩm khác</div>
                @endif
            </div>
            <div class="order-card-foot">
                <div>{{ $order->items->count() }} sản phẩm</div>
                <div class="order-total">{{ number_format($order->grand_total, 0, ',', '.') }}₫</div>
                <a href="{{ route('customer.order-detail', $order) }}" class="btn-outline-sm">Xem chi tiết</a>
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:60px 0;color:#aaa;font-size:14px;font-style:italic;">
            Không có đơn hàng nào.
        </div>
        @endforelse

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
    </div>
</div>
</div>
@endsection
