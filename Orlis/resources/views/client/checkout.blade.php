@extends('layouts.client')

@section('title', 'Thanh toán - Orlis')

@section('styles')
<style>
    .checkout-wrap {
        max-width: 1060px;
        margin: 0 auto;
        padding: 100px 20px 60px;
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 40px;
    }
    h2.section-title {
        font-family: var(--font-serif);
        font-size: 20px;
        font-weight: 500;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e0e0e0;
    }
    .form-group { margin-bottom: 16px; }
    .form-label { display: block; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #666; margin-bottom: 6px; }
    .form-input {
        width: 100%;
        padding: 11px 14px;
        border: 1px solid #d0d0d0;
        border-radius: 4px;
        font-size: 14px;
        color: #333;
        transition: border-color 0.2s;
        box-sizing: border-box;
    }
    .form-input:focus { border-color: var(--primary); outline: none; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .payment-options { display: flex; gap: 12px; }
    .payment-option {
        flex: 1;
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        padding: 14px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        font-weight: 500;
    }
    .payment-option input[type="radio"] { accent-color: var(--primary); width: 16px; height: 16px; }
    .payment-option:has(input:checked) { border-color: var(--primary); background: rgba(139,111,71,0.04); }
    .order-summary {
        background: #f8f8f8;
        border-radius: 8px;
        padding: 24px;
        position: sticky;
        top: 90px;
        height: fit-content;
    }
    .summary-item {
        display: flex;
        gap: 14px;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #ebebeb;
    }
    .summary-item:last-of-type { border-bottom: none; }
    .summary-item-img {
        width: 52px;
        height: 68px;
        object-fit: cover;
        border-radius: 4px;
        background: #eee;
        flex-shrink: 0;
    }
    .summary-item-name { font-size: 13px; font-weight: 600; }
    .summary-item-variant { font-size: 12px; color: #888; margin-top: 2px; }
    .summary-item-price { font-size: 13px; font-weight: 700; margin-left: auto; white-space: nowrap; }
    .divider { height: 1px; background: #e0e0e0; margin: 14px 0; }
    .summary-row { display: flex; justify-content: space-between; font-size: 13px; padding: 4px 0; }
    .summary-row.grand { font-size: 16px; font-weight: 700; margin-top: 8px; }
    .coupon-row { display: flex; gap: 8px; margin-bottom: 16px; }
    .coupon-row input { flex: 1; }
    .coupon-row button { padding: 11px 18px; background: #444; color: white; border: none; border-radius: 4px; font-size: 13px; cursor: pointer; white-space: nowrap; }
    .btn-place-order {
        width: 100%;
        padding: 16px;
        background: #333;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        margin-top: 18px;
        letter-spacing: 0.3px;
        transition: background 0.2s;
    }
    .btn-place-order:hover { background: #111; }
    .error-msg { color: #c0392b; font-size: 12px; margin-top: 4px; }
    .address-card {
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        padding: 12px 14px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: border-color 0.2s;
        font-size: 13px;
    }
    .address-card:has(input:checked) { border-color: var(--primary); background: rgba(139,111,71,0.03); }
    .address-card input[type="radio"] { accent-color: var(--primary); margin-right: 8px; }
    @media(max-width: 768px) {
        .checkout-wrap { grid-template-columns: 1fr; }
        .order-summary { position: static; }
    }
</style>
@endsection

@section('content')
<div style="background:#f5f5f3; min-height: 100vh;">
<form method="POST" action="{{ route('checkout.store') }}" id="checkout-form">
@csrf
<input type="hidden" name="idempotency_key" value="{{ session()->get('checkout_idempotency_key') ?? tap(\Illuminate\Support\Str::uuid()->toString(), fn($k) => session()->put('checkout_idempotency_key', $k)) }}">
<div class="checkout-wrap">

    {{-- LEFT: Form --}}
    <div>
        {{-- Địa chỉ giao hàng --}}
        <h2 class="section-title">{{ __('messages.shipping_address') }}</h2>

        @if($addresses->isNotEmpty())
        <div style="margin-bottom: 16px;">
            @foreach($addresses as $addr)
            <label class="address-card">
                <input type="radio" name="saved_address_id" value="{{ $addr->id }}" {{ $addr->is_default ? 'checked' : '' }}
                    onchange="fillAddress({{ json_encode($addr) }})">
                <strong>{{ $addr->recipient_name }}</strong> — {{ $addr->phone }}<br>
                <span style="color:#888;">{{ $addr->full_address }}</span>
            </label>
            @endforeach
            <button type="button" onclick="clearAddress()" style="font-size:12px;color:var(--primary);background:none;border:none;cursor:pointer;padding:0;margin-bottom:16px;text-decoration:underline;">{{ __('messages.use_new_address') }}</button>
        </div>
        @endif

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">{{ __('messages.recipient_name') }}</label>
                <input type="text" name="recipient_name" id="f_name" class="form-input" value="{{ old('recipient_name', $addresses->firstWhere('is_default', true)?->recipient_name ?? auth()->user()->name) }}" required>
                @error('recipient_name')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('messages.phone_number') }}</label>
                <input type="text" name="recipient_phone" id="f_phone" class="form-input" value="{{ old('recipient_phone', $addresses->firstWhere('is_default', true)?->phone ?? auth()->user()->phone) }}" required>
                @error('recipient_phone')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">{{ __('messages.province') }}</label>
                <input type="text" name="province" id="f_province" class="form-input" value="{{ old('province', $addresses->firstWhere('is_default', true)?->province) }}" required placeholder="{{ __('messages.province_placeholder') }}">
                @error('province')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('messages.district') }}</label>
                <input type="text" name="district" id="f_district" class="form-input" value="{{ old('district', $addresses->firstWhere('is_default', true)?->district) }}" required placeholder="{{ __('messages.district_placeholder') }}">
                @error('district')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">{{ __('messages.ward') }}</label>
                <input type="text" name="ward" id="f_ward" class="form-input" value="{{ old('ward', $addresses->firstWhere('is_default', true)?->ward) }}" required placeholder="{{ __('messages.ward_placeholder') }}">
                @error('ward')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('messages.detail_address') }}</label>
                <input type="text" name="detail_address" id="f_detail" class="form-input" value="{{ old('detail_address', $addresses->firstWhere('is_default', true)?->detail_address) }}" required placeholder="{{ __('messages.detail_address_placeholder') }}">
                @error('detail_address')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">{{ __('messages.gift_note') }}</label>
            <textarea name="gift_note" class="form-input" rows="2" placeholder="{{ __('messages.gift_note_placeholder') }}">{{ old('gift_note') }}</textarea>
        </div>

        <div class="form-group">
            <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer;">
                <input type="checkbox" name="save_address" value="1" style="width:15px;height:15px;accent-color:var(--primary);">
                {{ __('messages.save_address_for_next_time') }}
            </label>
        </div>

        {{-- Phương thức thanh toán --}}
        <h2 class="section-title" style="margin-top: 28px;">{{ __('messages.payment_method') }}</h2>
        <div class="payment-options">
            <label class="payment-option">
                <input type="radio" name="payment_method" value="cod" checked>
                <span>💵 {{ __('messages.cod') }}</span>
            </label>
            <label class="payment-option">
                <input type="radio" name="payment_method" value="vnpay">
                <span>🏦 {{ __('messages.vnpay') }}</span>
            </label>
        </div>
        @error('payment_method')<div class="error-msg">{{ $message }}</div>@enderror
    </div>

    {{-- RIGHT: Order Summary --}}
    <div class="order-summary">
        <h2 class="section-title" style="border:none;margin-bottom:14px;">{{ __('messages.order_summary') }} ({{ $cart->total_quantity }} {{ __('messages.items') }})</h2>

        @foreach($cart->items as $item)
        @php
            $product = $item->variant?->product;
            $price = $item->variant?->price_override ?? $product?->sale_price ?? $product?->price ?? 0;
        @endphp
        <div class="summary-item">
            @if($product?->thumbnail)
                <img class="summary-item-img" src="{{ Storage::url($product->thumbnail) }}" alt="{{ $product->name }}">
            @else
                <div class="summary-item-img" style="display:flex;align-items:center;justify-content:center;color:#ccc;font-size:22px;">🧴</div>
            @endif
            <div style="flex:1;">
                <div class="summary-item-name">{{ $product?->name ?? __('messages.product') }}</div>
                <div class="summary-item-variant">{{ $item->variant?->display_name }} × {{ $item->quantity }}</div>
            </div>
            <div class="summary-item-price">{{ number_format($item->subtotal, 0, ',', '.') }}₫</div>
        </div>
        @endforeach

        {{-- Coupon --}}
        <div class="divider"></div>
        <div class="coupon-row" style="margin-bottom: 5px;">
            <input type="text" id="checkout_coupon_code" name="coupon_code" class="form-input" placeholder="Mã giảm giá" value="{{ session('applied_coupon')['code'] ?? old('coupon_code') }}">
            <input type="text" id="checkout_coupon_code" name="coupon_code" class="form-input" placeholder="{{ __('messages.coupon_code') }}" value="{{ session('applied_coupon')['code'] ?? old('coupon_code') }}">
            <button type="button" onclick="applyCouponCheckout()">{{ __('messages.apply') }}</button>
        </div>
        <div id="checkout-coupon-msg" style="font-size: 12px; margin-bottom: 15px;"></div>

        <div class="summary-row">
            <span>{{ __('messages.subtotal') }}</span>
            <span>{{ number_format($cart->total, 0, ',', '.') }}₫</span>
        </div>
        <div class="summary-row">
            <span>{{ __('messages.shipping_fee') }}</span>
            <span style="color:#52c41a;">{{ __('messages.free') }}</span>
        </div>
        
        @php
            $discount = session('applied_coupon')['discount_amount'] ?? 0;
            $grandTotal = max(0, $cart->total - $discount);
        @endphp
        
        @if($discount > 0)
        <div class="summary-row" style="color:#28a745;">
            <span>{{ __('messages.discount') }} ({{ session('applied_coupon')['code'] }})</span>
            <span>-{{ number_format($discount, 0, ',', '.') }}₫</span>
            <input type="hidden" name="coupon_code" value="{{ session('applied_coupon')['code'] }}">
        </div>
        @endif

        <div class="divider"></div>
        <div class="summary-row grand">
            <span>{{ __('messages.grand_total') }}</span>
            <span>{{ number_format($grandTotal, 0, ',', '.') }}₫</span>
        </div>

        <button type="submit" class="btn-place-order" id="btn-submit">
            <span id="btn-text">{{ __('messages.place_order') }}</span>
        </button>

        <p style="font-size: 11px; color: #999; text-align: center; margin-top: 14px; line-height: 1.5;">
            {{ __('messages.terms_and_conditions') }}
        </p>
    </div>

</div>
</form>
</div>

<script>
function fillAddress(addr) {
    document.getElementById('f_name').value = addr.recipient_name || '';
    document.getElementById('f_phone').value = addr.phone || '';
    document.getElementById('f_province').value = addr.province || '';
    document.getElementById('f_district').value = addr.district || '';
    document.getElementById('f_ward').value = addr.ward || '';
    document.getElementById('f_detail').value = addr.detail_address || '';
}
function clearAddress() {
    ['f_name','f_phone','f_province','f_district','f_ward','f_detail'].forEach(id => {
        document.getElementById(id).value = '';
    });
    document.querySelectorAll('input[name="saved_address_id"]').forEach(r => r.checked = false);
}

function applyCouponCheckout() {
    let code = document.getElementById('checkout_coupon_code').value;
    let msgEl = document.getElementById('checkout-coupon-msg');
    if (!code) {
        msgEl.innerHTML = '<span style="color: #dc3545;">Vui lòng nhập mã giảm giá</span>';
        return;
    }

    fetch('{{ route("cart.coupon.apply") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ coupon_code: code })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            msgEl.innerHTML = '<span style="color: #28a745;">' + data.message + '</span>';
            document.getElementById('discount-row').style.display = 'flex';
            document.getElementById('discount-amount').textContent = data.discount_formatted;
            document.getElementById('checkout-grand-total').textContent = data.new_total_formatted;
        } else {
            msgEl.innerHTML = '<span style="color: #dc3545;">' + data.message + '</span>';
        }
    });
}
// Idempotency: vô hiệu hóa nút sau lần bấm đầu
const checkoutForm = document.getElementById('checkout-form');
const btnSubmit = document.getElementById('btn-submit');
const btnText = document.getElementById('btn-text');
let isSubmitting = false;

checkoutForm.addEventListener('submit', function(e) {
    if (isSubmitting) {
        e.preventDefault();
        return false;
    }
    isSubmitting = true;
    btnSubmit.disabled = true;
    btnSubmit.style.opacity = '0.6';
    btnSubmit.style.cursor = 'not-allowed';
    btnText.textContent = 'Đang xử lý...';
});
</script>
@endsection
