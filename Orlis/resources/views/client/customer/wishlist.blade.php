@extends('layouts.customer')
@section('customer_title', 'Bộ sưu tập yêu thích - Orlis')
@section('customer_styles')
@endsection
@section('customer_content')
<div class="section-header">
    <div>
        <div class="subtitle">BỘ SƯU TẬP YÊU THÍCH</div>
        <h2 class="section-title">Danh sách sản phẩm ({{ $wishlists->count() }})</h2>
    </div>
</div>

@if($wishlists->isEmpty())
<div style="text-align:center;padding:80px 20px;background:#fbfbfb;border:1px solid #eee;">
    <svg class="empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"></path></svg>
    <div class="empty-text">Bộ sưu tập của Quý khách hiện chưa có sản phẩm nào.</div>
    <a href="{{ route('catalog') }}" class="btn-dark">KHÁM PHÁ NGAY</a>
</div>
@else
<div class="wishlist-grid">
    @foreach($wishlists as $wish)
    @php
        $variant = $wish->variant;
        $product = $variant?->product;
        $price = $variant?->price_override ?? $product?->sale_price ?? $product?->price ?? 0;
    @endphp
    <div class="wish-card">
        <a href="{{ $product ? route('product', $product->id) : '#' }}" style="display:block;">
            <div class="wish-img">
                @if($product?->thumbnail)
                    <img src="{{ Storage::url($product->thumbnail) }}" alt="{{ $product->name }}">
                @else
                    🧴
                @endif
            </div>
        </a>
        <div class="wish-info">
            <div class="wish-name">{{ $product?->name ?? 'Sản phẩm' }}</div>
            <div class="wish-variant">{{ $variant?->display_name }}</div>
            <div class="wish-price">{{ number_format($price, 0, ',', '.') }}₫</div>
            <div class="wish-actions">
                <form method="POST" action="{{ route('cart.add') }}" style="flex:1;">
                    @csrf
                    <input type="hidden" name="variant_id" value="{{ $variant?->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn-cart">Thêm vào giỏ</button>
                </form>
                <form method="POST" action="{{ route('wishlist.toggle') }}">
                    @csrf
                    <input type="hidden" name="variant_id" value="{{ $variant?->id }}">
                    <button type="submit" class="btn-remove" title="Xóa khỏi yêu thích">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14H6L5 6"></path><path d="M10 11v6M14 11v6"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
