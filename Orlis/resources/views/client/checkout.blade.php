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
<div class="checkout-wrap">

    {{-- LEFT: Form --}}
    <div>
        {{-- Địa chỉ giao hàng --}}
        <h2 class="section-title">Địa chỉ giao hàng</h2>

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
            <button type="button" onclick="clearAddress()" style="font-size:12px;color:var(--primary);background:none;border:none;cursor:pointer;padding:0;margin-bottom:16px;text-decoration:underline;">+ Dùng địa chỉ mới</button>
        </div>
        @endif

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Tên người nhận *</label>
                <input type="text" name="recipient_name" id="f_name" class="form-input" value="{{ old('recipient_name', $addresses->firstWhere('is_default', true)?->recipient_name ?? auth()->user()->name) }}" required>
                @error('recipient_name')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Số điện thoại *</label>
                <input type="text" name="recipient_phone" id="f_phone" class="form-input" value="{{ old('recipient_phone', $addresses->firstWhere('is_default', true)?->phone ?? auth()->user()->phone) }}" required>
                @error('recipient_phone')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Tỉnh / Thành phố *</label>
                <input type="text" name="province" id="f_province" class="form-input" value="{{ old('province', $addresses->firstWhere('is_default', true)?->province) }}" required placeholder="Ví dụ: TP. Hồ Chí Minh">
                @error('province')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Quận / Huyện *</label>
                <input type="text" name="district" id="f_district" class="form-input" value="{{ old('district', $addresses->firstWhere('is_default', true)?->district) }}" required placeholder="Ví dụ: Quận 1">
                @error('district')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Phường / Xã *</label>
                <input type="text" name="ward" id="f_ward" class="form-input" value="{{ old('ward', $addresses->firstWhere('is_default', true)?->ward) }}" required placeholder="Ví dụ: Phường Bến Nghé">
                @error('ward')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Địa chỉ chi tiết *</label>
                <input type="text" name="detail_address" id="f_detail" class="form-input" value="{{ old('detail_address', $addresses->firstWhere('is_default', true)?->detail_address) }}" required placeholder="Số nhà, tên đường...">
                @error('detail_address')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Ghi chú quà tặng (tùy chọn)</label>
            <textarea name="gift_note" class="form-input" rows="2" placeholder="Ví dụ: Gói quà, viết thiệp...">{{ old('gift_note') }}</textarea>
        </div>

        <div class="form-group">
            <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer;">
                <input type="checkbox" name="save_address" value="1" style="width:15px;height:15px;accent-color:var(--primary);">
                Lưu địa chỉ này cho lần sau
            </label>
        </div>

        {{-- Phương thức thanh toán --}}
        <h2 class="section-title" style="margin-top: 28px;">Phương thức thanh toán</h2>
        <div class="payment-options">
            <label class="payment-option">
                <input type="radio" name="payment_method" value="cod" checked>
                <span>💵 Thanh toán khi nhận hàng (COD)</span>
            </label>
            <label class="payment-option">
                <input type="radio" name="payment_method" value="vnpay">
                <span>🏦 Thanh toán qua VNPAY</span>
            </label>
        </div>
        @error('payment_method')<div class="error-msg">{{ $message }}</div>@enderror
    </div>

    {{-- RIGHT: Order Summary --}}
    <div class="order-summary">
        <h2 class="section-title" style="border:none;margin-bottom:14px;">Đơn hàng ({{ $cart->total_quantity }} sản phẩm)</h2>

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
                <div class="summary-item-name">{{ $product?->name ?? 'Sản phẩm' }}</div>
                <div class="summary-item-variant">{{ $item->variant?->display_name }} × {{ $item->quantity }}</div>
            </div>
            <div class="summary-item-price">{{ number_format($item->subtotal, 0, ',', '.') }}₫</div>
        </div>
        @endforeach

        {{-- Coupon --}}
        <div class="divider"></div>
        <div class="coupon-row" style="margin-bottom: 5px;">
            <input type="text" id="checkout_coupon_code" name="coupon_code" class="form-input" placeholder="Mã giảm giá" value="{{ session('applied_coupon')['code'] ?? old('coupon_code') }}">
            <button type="button" onclick="applyCouponCheckout()">Áp dụng</button>
        </div>
        <div id="checkout-coupon-msg" style="font-size: 12px; margin-bottom: 15px;"></div>

        <div class="summary-row">
            <span>Tạm tính</span>
            <span>{{ number_format($cart->total, 0, ',', '.') }}₫</span>
        </div>
        <div class="summary-row">
            <span>Phí vận chuyển</span>
            <span style="color:#52c41a;">Miễn phí</span>
        </div>
        
        @php
            $discount = session('applied_coupon')['discount_amount'] ?? 0;
            $grandTotal = max(0, $cart->total - $discount);
        @endphp
        
        <div class="summary-row" id="discount-row" style="{{ $discount > 0 ? '' : 'display: none;' }}">
            <span>Giảm giá</span>
            <span style="color:#dc3545;" id="discount-amount">-{{ number_format($discount, 0, ',', '.') }}₫</span>
        </div>
        
        <div class="divider"></div>
        <div class="summary-row grand">
            <span>Tổng cộng</span>
            <span id="checkout-grand-total">{{ number_format($grandTotal, 0, ',', '.') }}₫</span>
        </div>

        <button type="submit" class="btn-place-order">ĐẶT HÀNG NGAY</button>

        <p style="font-size: 11px; color: #999; text-align: center; margin-top: 14px; line-height: 1.5;">
            Bằng cách đặt hàng, bạn đồng ý với Điều khoản dịch vụ và Chính sách bảo mật của Orlis.
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
</script>
@endsection
