@extends('layouts.client')
@section('title', 'Yêu thích - Orlis')
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
    .section-title { font-family: var(--font-serif); font-size: 18px; font-weight: 500; margin-bottom: 18px; }
    .wishlist-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
    .wish-card { background: white; border-radius: 10px; border: 1px solid #efefef; overflow: hidden; transition: transform 0.2s, box-shadow 0.2s; }
    .wish-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,0.08); }
    .wish-img { width: 100%; aspect-ratio: 3/4; object-fit: cover; background: #f5f5f5; display: flex; align-items: center; justify-content: center; color: #ccc; font-size: 48px; }
    .wish-img img { width: 100%; height: 100%; object-fit: cover; }
    .wish-info { padding: 14px; }
    .wish-name { font-size: 14px; font-weight: 600; margin-bottom: 4px; color: #333; }
    .wish-variant { font-size: 12px; color: #aaa; margin-bottom: 8px; }
    .wish-price { font-size: 15px; font-weight: 700; color: var(--primary); }
    .wish-actions { display: flex; gap: 8px; margin-top: 12px; }
    .btn-cart { flex: 1; padding: 9px; background: #333; color: white; border: none; border-radius: 4px; font-size: 12px; font-weight: 600; cursor: pointer; text-align: center; transition: background 0.2s; }
    .btn-cart:hover { background: #111; }
    .btn-remove { width: 36px; height: 36px; border: 1px solid #f5c6c6; border-radius: 4px; background: transparent; color: #c0392b; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all 0.15s; }
    .btn-remove:hover { background: #c0392b; color: white; border-color: #c0392b; }
    @media(max-width: 768px) { .customer-wrap { grid-template-columns: 1fr; } .sidebar-nav { position: static; } .wishlist-grid { grid-template-columns: repeat(2, 1fr); } }
</style>
@endsection
@section('content')
<div style="background: #f8f7f5; min-height: 100vh;">
<div class="customer-wrap">
    @include('client.customer._sidebar')
    <div>
        @if(session('success'))
            <div style="background:#d4edda;color:#155724;padding:12px 16px;border-radius:6px;margin-bottom:20px;font-size:13px;">{{ session('success') }}</div>
        @endif

        <h2 class="section-title">Danh sách yêu thích ({{ $wishlists->count() }})</h2>

        @if($wishlists->isEmpty())
        <div style="text-align:center;padding:60px 0;color:#aaa;font-size:14px;font-style:italic;">
            Bạn chưa có sản phẩm yêu thích nào.
            <br><a href="{{ route('catalog') }}" style="color:var(--primary);text-decoration:none;font-weight:500;margin-top:8px;display:inline-block;">Khám phá sản phẩm →</a>
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
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14H6L5 6"></path><path d="M10 11v6M14 11v6"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
</div>
@endsection
