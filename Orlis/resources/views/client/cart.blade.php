@extends('layouts.client')

@section('title', 'Giỏ hàng - Orlis')

@section('styles')
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 20px;
            width: 100%;
            flex: 1;
        }
        .page-title {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 40px;
        }
        .page-title h1 {
            font-family: var(--font-serif);
            font-size: 24px;
            font-weight: 500;
        }
        .page-title span { font-size: 13px; color: #666; }
        .empty-state { display: flex; flex-direction: column; align-items: center; text-align: center; }
        .login-banner { width: 100%; background-color: var(--primary); color: white; padding: 15px; border-radius: 4px; font-size: 14px; margin-bottom: 15px; cursor: pointer; }
        .login-note { font-size: 12px; color: #555; margin-bottom: 60px; max-width: 600px; line-height: 1.5; }
        .bag-icon { margin-bottom: 20px; }
        .bag-icon svg { width: 32px; height: 32px; stroke: #333; fill: none; stroke-width: 1.5; }
        .empty-msg { font-size: 13px; color: #555; margin-bottom: 30px; }
        .btn-continue { padding: 12px 30px; border: 1px solid var(--text-dark); background: transparent; font-size: 14px; cursor: pointer; transition: all 0.2s; }
        .btn-continue:hover { background: var(--text-dark); color: white; }
        .cart-header { display: grid; grid-template-columns: 16px 4fr 1fr 1.5fr 1fr; gap: 24px; padding-bottom: 15px; padding-left: 24px; padding-right: 24px; border-bottom: 1px solid #d0d0d0; font-size: 13px; color: #666; text-align: center; margin-bottom: 15px; font-weight: 400; }
        .cart-header > :nth-child(2) { text-align: left; }
        .cart-item { display: grid; grid-template-columns: 16px 4fr 1fr 1.5fr 1fr; gap: 24px; align-items: center; background: white; padding: 16px 24px; border-radius: 8px; margin-bottom: 15px; text-align: center; }
        .cart-item > :nth-child(2) { display: flex; gap: 24px; text-align: left; align-items: center; }
        .item-img { width: 90px; height: 120px; min-width: 90px; min-height: 120px; max-width: 90px; max-height: 120px; flex-shrink: 0; object-fit: cover; border: 1px solid #e0e0e0; background: #ffffff; border-radius: 2px; }
        .item-info h4 { font-family: var(--font-sans); font-size: 14px; margin-bottom: 5px; font-weight: 600; color: #333; }
        .item-info p { font-family: var(--font-sans); font-size: 13px; color: #666; margin-bottom: 15px; line-height: 1.5; }
        .item-remove { font-size: 12px; color: #555; text-decoration: underline; cursor: pointer; }
        .item-price, .item-total { font-family: var(--font-sans); font-size: 14px; font-weight: 700; color: #333; }
        .qty-control { display: inline-flex; align-items: center; background: #e2e2e2; border-radius: 4px; height: 32px; padding: 0 6px; }
        .qty-btn { width: 24px; height: 100%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: none; background: none; font-size: 14px; font-weight: 500; color: #333; }
        .qty-input { width: 28px; text-align: center; border: none; background: none; font-size: 14px; font-weight: 600; color: #333; }
        .cart-footer { display: flex; justify-content: space-between; align-items: center; background: white; padding: 16px 24px; border-radius: 8px; margin-top: 15px; position: sticky; bottom: 20px; z-index: 90; box-shadow: 0 -4px 20px rgba(0,0,0,0.08); }
        .footer-left { display: flex; align-items: center; gap: 12px; font-size: 14px; color: #333; }
        .footer-right { display: flex; align-items: center; gap: 30px; }
        .total-price { font-family: var(--font-sans); font-size: 14px; font-weight: 700; color: #333; }
        .btn-checkout { padding: 14px 40px; background: #444444; color: white; border: none; border-radius: 4px; font-size: 14px; cursor: pointer; font-weight: 500; text-decoration: none; display: inline-block; }
        input[type="checkbox"] { width: 16px; height: 16px; cursor: pointer; appearance: none; background-color: #dbdbdb; border-radius: 2px; border: none; position: relative; display: inline-flex; align-items: center; justify-content: center; transition: 0.2s; }
        input[type="checkbox"]:checked { background-color: #111; }
        input[type="checkbox"]:checked::after { content: ''; position: absolute; width: 4px; height: 8px; border: solid white; border-width: 0 2px 2px 0; transform: rotate(45deg); margin-bottom: 2px; }
    </style>
@endsection

@section('content')
<div style="background-color: #f1f4f5; min-height: 100vh; padding-top: 80px; padding-bottom: 80px;">
    <div class="container">

        <div class="page-title">
            <h1>{{ __('messages.your_bag') }}</h1>
            <span id="item-count">{{ $cart ? $cart->total_quantity : 0 }} {{ __('messages.items') }}</span>
        </div>

        @if(session('success'))
            <div style="background:#d4edda;color:#155724;padding:12px 16px;border-radius:6px;margin-bottom:16px;font-size:13px;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div style="background:#f8d7da;color:#721c24;padding:12px 16px;border-radius:6px;margin-bottom:16px;font-size:13px;">{{ session('error') }}</div>
        @endif

        @guest
        <div class="empty-state">
            <a href="{{ route('role.login', 'customer') }}" style="width:100%;display:flex;justify-content:center;">
                <button class="login-banner" style="max-width:600px;">{{ __('messages.login_or_create') }}</button>
            </a>
            <p class="login-note">{{ __('messages.member_rewards') }}</p>
            <div class="bag-icon">
                <svg viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <p class="empty-msg">{{ __('messages.login_to_view_cart') }}</p>
            <a href="/"><button class="btn-continue">{{ __('messages.continue_shopping') }}</button></a>
        </div>
        @endguest

        @auth
        @if(!$cart || $cart->items->isEmpty())
        <div class="empty-state">
            <div class="bag-icon">
                <svg viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <p class="empty-msg">{{ __('messages.cart_empty') }}</p>
            <a href="{{ route('catalog') }}"><button class="btn-continue">{{ __('messages.continue_shopping') }}</button></a>
        </div>
        @else
        <div style="display: block;">
            <div class="cart-header">
                <div></div>
                <div>{{ __('messages.item_details') }}</div>
                <div>{{ __('messages.unit_price') }}</div>
                <div>{{ __('messages.quantity') }}</div>
                <div>{{ __('messages.total_price') }}</div>
            </div>

            @foreach($cart->items as $item)
            @php
                $product = $item->variant?->product;
                $price = $item->variant?->price_override ?? $product?->sale_price ?? $product?->price ?? 0;
            @endphp
            <div class="cart-item" id="cart-item-{{ $item->variant_id }}">
                <div><input type="checkbox" class="item-cb" checked></div>
                <div>
                    @if($product?->thumbnail)
                        <img src="{{ Storage::url($product->thumbnail) }}" alt="{{ $product->name }}" class="item-img">
                    @else
                        <div class="item-img" style="display:flex;align-items:center;justify-content:center;background:#f5f5f5;color:#ccc;font-size:32px;">🧴</div>
                    @endif
                    <div class="item-info">
                        <h4>{{ $product?->name ?? __('messages.product') }}</h4>
                        <p>{{ $item->variant?->display_name }}</p>
                        <form method="POST" action="{{ route('cart.remove', $item->variant_id) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="item-remove" style="background:none;border:none;padding:0;">{{ __('messages.remove') }}</button>
                        </form>
                    </div>
                </div>
                <div class="item-price">{{ number_format($price, 0, ',', '.') }}₫</div>
                <div>
                    <div class="qty-control">
                        <button class="qty-btn" onclick="changeQty({{ $item->variant_id }}, {{ $price }}, -1)" type="button">−</button>
                        <input type="text" value="{{ $item->quantity }}" class="qty-input" id="qty-{{ $item->variant_id }}" readonly>
                        <button class="qty-btn" onclick="changeQty({{ $item->variant_id }}, {{ $price }}, 1)" type="button">+</button>
                    </div>
                </div>
                <div class="item-total" id="total-{{ $item->variant_id }}">{{ number_format($item->subtotal, 0, ',', '.') }}₫</div>
            </div>
            @endforeach



            <div class="cart-footer">
                <div class="footer-left">
                    <input type="checkbox" id="check-all" onclick="toggleAll(this)">
                    <label for="check-all">{{ __('messages.select_all') }}</label>
                </div>
                <div class="footer-right">
                    <div class="total-price">{{ __('messages.total') }} <span id="grand-total">{{ number_format($cart->total, 0, ',', '.') }}₫</span></div>
                    <a href="{{ route('checkout.index') }}" class="btn-checkout">{{ __('messages.checkout') }}</a>
                </div>
            </div>
        </div>
        @endif
        @endauth

    </div>
</div>

<script>
const csrfToken = '{{ csrf_token() }}';

function changeQty(variantId, unitPrice, delta) {
    const input = document.getElementById('qty-' + variantId);
    const newQty = Math.max(1, parseInt(input.value) + delta);
    input.value = newQty;

    // Update local total immediately
    const itemTotalEl = document.getElementById('total-' + variantId);
    if (itemTotalEl) {
        itemTotalEl.textContent = new Intl.NumberFormat('vi-VN').format(unitPrice * newQty) + '₫';
    }

    fetch('/cart/' + variantId, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'X-HTTP-Method-Override': 'PATCH' },
        body: JSON.stringify({ _method: 'PATCH', quantity: newQty })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            if (newQty === 0) {
                document.getElementById('cart-item-' + variantId)?.remove();
            }
            if (data.total) {
                document.getElementById('grand-total').textContent = data.total;
            }
        }
    });
}

function toggleAll(cb) {
    document.querySelectorAll('.item-cb').forEach(el => el.checked = cb.checked);
}
</script>
@endsection
