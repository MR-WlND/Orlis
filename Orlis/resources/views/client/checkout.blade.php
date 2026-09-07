@extends('layouts.client')

@section('title', 'Thanh toán - Orlis')

@section('styles')
@endsection

@section('content')
<div style="background:#f9f9f9; min-height: 100vh; font-family: var(--font-sans); color: #333; padding-bottom: 80px;">

<form method="POST" action="{{ route('checkout.store') }}" id="checkout-form">
@csrf
<input type="hidden" name="idempotency_key" value="{{ session()->get('checkout_idempotency_key') ?? tap(\Illuminate\Support\Str::uuid()->toString(), fn($k) => session()->put('checkout_idempotency_key', $k)) }}">
<div class="checkout-wrap">

    {{-- LEFT: Form --}}
    <div class="checkout-left">
        
        @if(session('error'))
            <div style="background: #fff1f0; color: #f5222d; padding: 15px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #ffa39e;">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background: #fff1f0; color: #f5222d; padding: 15px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #ffa39e;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        {{-- Card 1: Địa chỉ giao hàng --}}
        <div class="checkout-card">
            <div class="checkout-card-header">
                <h2 class="checkout-card-title">1. {{ __('messages.shipping_address') }}</h2>
                <span class="checkout-card-step">{{ __('messages.step_1_3') }}</span>
            </div>
            <div class="checkout-card-body">
                @if($addresses->isNotEmpty())
                <div style="margin-bottom: 24px;">
                    @foreach($addresses as $addr)
                    <label class="checkout-radio-block">
                        <input type="radio" name="saved_address_id" value="{{ $addr->id }}" {{ $addr->is_default ? 'checked' : '' }} onchange="fillAddress({{ json_encode($addr) }})">
                        <div class="cr-content">
                            <div class="cr-title"><strong>{{ $addr->recipient_name }}</strong> — {{ $addr->phone }} @if($addr->is_default)<span class="badge-default">{{ __('messages.default') }}</span>@endif</div>
                            <div class="cr-desc">{{ $addr->full_address }}</div>
                        </div>
                    </label>
                    @endforeach
                    <div style="text-align: right;">
                        <button type="button" onclick="clearAddress()" class="btn-text-link">{{ __('messages.use_new_address') }}</button>
                    </div>
                </div>
                @endif

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">{{ __('messages.recipient_name') }} *</label>
                        <input type="text" name="recipient_name" id="f_name" class="form-input" value="{{ old('recipient_name', $addresses->firstWhere('is_default', true)?->recipient_name ?? auth()->user()->name) }}" required>
                        @error('recipient_name')<div class="error-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('messages.phone_number') }} *</label>
                        <input type="text" name="recipient_phone" id="f_phone" class="form-input" value="{{ old('recipient_phone', $addresses->firstWhere('is_default', true)?->phone ?? auth()->user()->phone) }}" required>
                        @error('recipient_phone')<div class="error-msg">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-row three-cols">
                    <div class="form-group">
                        <label class="form-label">{{ __('messages.province') }} *</label>
                        <input type="text" name="province" id="f_province" class="form-input" value="{{ old('province', $addresses->firstWhere('is_default', true)?->province) }}" required placeholder="{{ __('messages.province_placeholder') }}">
                        @error('province')<div class="error-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('messages.district') }} *</label>
                        <input type="text" name="district" id="f_district" class="form-input" value="{{ old('district', $addresses->firstWhere('is_default', true)?->district) }}" required placeholder="{{ __('messages.district_placeholder') }}">
                        @error('district')<div class="error-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('messages.ward') }} *</label>
                        <input type="text" name="ward" id="f_ward" class="form-input" value="{{ old('ward', $addresses->firstWhere('is_default', true)?->ward) }}" required placeholder="{{ __('messages.ward_placeholder') }}">
                        @error('ward')<div class="error-msg">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('messages.detail_address') }} *</label>
                    <input type="text" name="detail_address" id="f_detail" class="form-input" value="{{ old('detail_address', $addresses->firstWhere('is_default', true)?->detail_address) }}" required placeholder="{{ __('messages.detail_address_placeholder') }}">
                    @error('detail_address')<div class="error-msg">{{ $message }}</div>@enderror
                </div>

                <div class="form-group" style="position: relative;">
                    <label class="form-label">{{ __('messages.gift_note') }} <span style="float:right;color:#aaa;text-transform:none;">({{ __('messages.optional') }})</span></label>
                    <textarea name="gift_note" class="form-input" rows="3" placeholder="{{ __('messages.gift_note_placeholder') }}">{{ old('gift_note') }}</textarea>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer;color:#555;">
                        <input type="checkbox" name="save_address" value="1" style="width:16px;height:16px;accent-color:#111;">
                        {{ __('messages.save_address_for_next_time') }}
                    </label>
                </div>
            </div>
        </div>

        {{-- Card 2: Phương thức giao hàng --}}
        <div class="checkout-card">
            <div class="checkout-card-header">
                <h2 class="checkout-card-title">2. {{ __('messages.shipping_method') }}</h2>
                <span class="checkout-card-step">{{ __('messages.maison_standard') }}</span>
            </div>
            <div class="checkout-card-body">
                @foreach($shippingMethods as $index => $method)
                    @php
                        $isFree = false;
                        if ($method->min_order_amount_for_free_shipping && $cart->total >= $method->min_order_amount_for_free_shipping) {
                            $isFree = true;
                        }
                        // Nếu phương thức vốn dĩ giá 0đ
                        if ($method->cost == 0) {
                            $isFree = true;
                        }
                    @endphp
                    <label class="checkout-radio-block {{ $index === 0 ? 'active' : '' }}" id="shipping-{{ $method->id }}-label">
                        <input type="radio" name="shipping_method_id" value="{{ $method->id }}" {{ $index === 0 ? 'checked' : '' }} data-cost="{{ $isFree ? 0 : $method->cost }}" onchange="updateShippingSelect(this)">
                        <div class="cr-content" style="display:flex; justify-content:space-between; align-items:flex-start; width:100%;">
                            <div>
                                <div class="cr-title" style="display:flex; align-items:center; gap:6px; font-weight: 600;">
                                    {{ $method->name }}
                                    @if(str_contains(strtolower($method->name), 'hỏa tốc') && $isFree)
                                        <span class="badge-vip">{{ __('messages.vip_privilege') }}</span>
                                    @endif
                                    @if($method->cost == 0)
                                        <span class="badge-gift-icon" title="{{ __('messages.gift_packaging') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
                                        </span>
                                    @endif
                                </div>
                                <div class="cr-desc">
                                    {{ $method->description }}
                                    @if($method->min_order_amount_for_free_shipping > 0)
                                        @if($isFree)
                                            <span style="display:flex; align-items:center; gap:4px; color:#b8860b; font-size:12px; font-weight:500; margin-top:4px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#b8860b" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l2.4 6.6L21 11l-6.6 2.4L12 20l-2.4-6.6L3 11l6.6-2.4L12 2z"/></svg>
                                                {{ __('messages.orders_from') }} {{ number_format($method->min_order_amount_for_free_shipping, 0, ',', '.') }}đ {{ __('messages.free_privilege') }}
                                            </span>
                                        @else
                                            <span style="display:block; font-size:11px; color:#999; margin-top:4px;">({{ __('messages.free_shipping_from') }} {{ number_format($method->min_order_amount_for_free_shipping, 0, ',', '.') }}đ)</span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="cr-price {{ $isFree ? 'highlight' : '' }}" style="font-size: 12px; margin-top: 2px;">
                                @if($isFree)
                                    {{ __('messages.free') }}
                                @else
                                    {{ number_format($method->cost, 0, ',', '.') }}₫
                                @endif
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Card 3: Phương thức thanh toán --}}
        <div class="checkout-card">
            <div class="checkout-card-header">
                <h2 class="checkout-card-title">3. {{ __('messages.payment_method') }}</h2>
                <span class="checkout-card-step"><svg style="width:12px;height:12px;vertical-align:middle;margin-right:4px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>{{ __('messages.ssl_secure') }}</span>
            </div>
            <div class="checkout-card-body">
                <label class="checkout-radio-block active" id="label-cod">
                    <input type="radio" name="payment_method" value="cod" checked onchange="updatePaymentSelect()">
                    <div class="cr-content" style="display:flex; justify-content:space-between; align-items:center; width:100%;">
                        <div class="cr-title" style="display:flex;align-items:center;gap:8px;">
                            <span class="payment-icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg></span> {{ __('messages.cod') }}
                        </div>
                        <div class="cr-desc" style="text-align:right;">{{ __('messages.check_before_pay') }}</div>
                    </div>
                </label>
                
                <label class="checkout-radio-block" id="label-vnpay">
                    <input type="radio" name="payment_method" value="vnpay" onchange="updatePaymentSelect()">
                    <div class="cr-content" style="display:flex; justify-content:space-between; align-items:center; width:100%;">
                        <div class="cr-title" style="display:flex;align-items:center;gap:8px;">
                            <span class="payment-icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg></span> {{ __('messages.vnpay') }}
                        </div>
                        <div class="cr-desc"><span class="badge-pay">VNPAY</span> <span class="badge-pay">QR</span></div>
                    </div>
                </label>
                @error('payment_method')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

    </div>

    {{-- RIGHT: Order Summary --}}
    <div class="checkout-right">
        <div class="checkout-summary-card">
            <div class="checkout-summary-header">
                <h2>{{ __('messages.order_summary') }}</h2>
                <span>({{ $cart->total_quantity }} SẢN PHẨM)</span>
            </div>
            
            <div class="summary-items-list">
                @foreach($cart->items as $item)
                @php
                    $product = $item->variant?->product;
                    $price = $item->variant?->price_override ?? $product?->sale_price ?? $product?->price ?? 0;
                @endphp
                <div class="summary-item">
                    <div class="si-img">
                        @if($product?->thumbnail)
                            <img src="{{ Storage::url($product->thumbnail) }}" alt="{{ $product->name }}">
                        @else
                            <div class="si-placeholder">🧴</div>
                        @endif
                    </div>
                    <div class="si-info">
                        <div class="si-name">{{ $product?->name ?? __('messages.product') }}</div>
                        <div class="si-variant">{{ $item->variant?->display_name }}</div>
                        <div class="si-meta">Mã tham chiếu: ORL-{{ $item->variant_id ?? '000' }}</div>
                    </div>
                    <div class="si-price">{{ number_format($item->subtotal, 0, ',', '.') }}₫</div>
                </div>
                @endforeach
            </div>

            <div class="summary-divider"></div>

            <div class="checkout-coupon">
                <label class="form-label" style="font-size:10px;">MÃ ƯU ĐÃI PRIVÉ</label>
                <div class="coupon-flex">
                    <input type="text" id="checkout_coupon_code" name="coupon_code" class="form-input" placeholder="NHẬP MÁ ƯU ĐÃI..." value="{{ session('applied_coupon')['code'] ?? old('coupon_code') }}">
                    <button type="button" class="btn-apply" onclick="applyCouponCheckout()">ÁP DỤNG</button>
                </div>
                <div id="checkout-coupon-msg" style="font-size: 12px; margin-top: 8px;"></div>
            </div>

            <div class="summary-divider"></div>

            @if(auth()->check() && auth()->user()->points > 0)
            <div class="checkout-points">
                <label class="form-label" style="font-size:10px;">SỬ DỤNG {{ __('messages.points') }} THƯỞNG (HIỆN CÓ: {{ number_format(auth()->user()->points, 0, ',', '.') }})</label>
                <div class="coupon-flex">
                    <input type="number" id="use_points_input" name="use_points" class="form-input" placeholder="Nhập số điểm..." min="0" max="{{ auth()->user()->points }}" value="0" oninput="updatePointsDiscount()">
                    <button type="button" class="btn-apply" onclick="document.getElementById('use_points_input').value={{ auth()->user()->points }}; updatePointsDiscount();">DÙNG TẤT CẢ</button>
                </div>
                <div style="font-size: 11px; margin-top: 8px; color: #888;">1 điểm = 1.000₫. <span id="points_discount_text" style="color:#28a745; font-weight:600;">Giảm: 0₫</span></div>
            </div>
            
            <div class="summary-divider"></div>
            @endif

            <div class="summary-rows">
                <div class="s-row">
                    <span>Tạm tính</span>
                    <span>{{ number_format($cart->total, 0, ',', '.') }}₫</span>
                </div>
                <div class="s-row">
                    <span>Phí vận chuyển bảo hiểm</span>
                    <span class="highlight" id="summary-shipping-fee">{{ __('messages.free') }}</span>
                </div>
                <div class="s-row">
                    <span>Hộp quà & Ruy băng Couture</span>
                    <span>Bao gồm</span>
                </div>
                
                @php
                    $discount = session('applied_coupon')['discount_amount'] ?? 0;
                    $grandTotal = max(0, $cart->total - $discount);
                @endphp
                
                @if($discount > 0)
                <div class="s-row" style="color:#28a745;">
                    <span>Chiết khấu ({{ session('applied_coupon')['code'] }})</span>
                    <span>-{{ number_format($discount, 0, ',', '.') }}₫</span>
                    <input type="hidden" name="coupon_code_applied" value="{{ session('applied_coupon')['code'] }}">
                </div>
                @endif
            </div>

            <div class="summary-divider"></div>

            <div class="summary-grand">
                <div class="sg-label">TỔNG THANH TOÁN</div>
                <div class="sg-value">
                    <div class="sg-price" id="summary-grand-total">{{ number_format($grandTotal, 0, ',', '.') }}₫</div>
                    <div class="sg-vat">(Đã bao gồm Thuế giá trị gia tăng GTGT)</div>
                </div>
            </div>

            <button type="submit" class="btn-place-order" id="btn-submit">
                <span id="btn-text">{{ mb_strtoupper(__('messages.place_order'), 'UTF-8') }} &rarr;</span>
            </button>
            
            <div class="checkout-policy">
                <p>Bằng việc đặt hàng, bạn đồng ý với <a href="#">Điều khoản mua hàng</a> & <a href="#">Chính sách bảo mật</a> của Orlis.</p>
            </div>
            
            <div class="checkout-trust">
                <div class="trust-item">
                    <span class="ti-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg></span>
                    <span class="ti-text">Đóng gói thủ công trong hộp quà Orlis Paris với ruy băng lụa cao cấp.</span>
                </div>
                <div class="trust-item">
                    <span class="ti-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></span>
                    <span class="ti-text">Đổi trả bảo đảm linh hoạt trong vòng 5 ngày làm việc.</span>
                </div>
                <div class="trust-item">
                    <span class="ti-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg></span>
                    <span class="ti-text">Cam kết 100% chế tác chính hãng và bảo hành đường may trọn đời.</span>
                </div>
            </div>

        </div>
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
    
    // update active styling
    document.querySelectorAll('input[name="saved_address_id"]').forEach(r => {
        if(r.checked) {
            r.closest('.checkout-radio-block').classList.add('active');
        } else {
            r.closest('.checkout-radio-block').classList.remove('active');
        }
    });
}
function clearAddress() {
    ['f_name','f_phone','f_province','f_district','f_ward','f_detail'].forEach(id => {
        document.getElementById(id).value = '';
    });
    document.querySelectorAll('input[name="saved_address_id"]').forEach(r => {
        r.checked = false;
        r.closest('.checkout-radio-block').classList.remove('active');
    });
}

function updatePaymentSelect() {
    document.getElementById('label-cod').classList.remove('active');
    document.getElementById('label-vnpay').classList.remove('active');
    const checked = document.querySelector('input[name="payment_method"]:checked').value;
    document.getElementById('label-'+checked).classList.add('active');
}

function updateShippingSelect(input) {
    document.querySelectorAll('.checkout-radio-block').forEach(el => {
        // Chỉ bỏ active của các block nằm trong thẻ chứa phương thức giao hàng
        if(el.id.startsWith('shipping-')) {
            el.classList.remove('active');
        }
    });
    input.closest('.checkout-radio-block').classList.add('active');

    // Cập nhật phí
    let cost = parseFloat(input.getAttribute('data-cost')) || 0;
    let feeEl = document.getElementById('summary-shipping-fee');
    if(cost === 0) {
        feeEl.innerText = '{{ __('messages.free') }}';
    } else {
        feeEl.innerText = cost.toLocaleString('vi-VN') + '₫';
    }

    updateTotals();
}

function updatePointsDiscount() {
    let input = document.getElementById('use_points_input');
    if (!input) return;
    let points = parseInt(input.value) || 0;
    let maxPoints = parseInt(input.getAttribute('max')) || 0;
    
    if (points > maxPoints) {
        points = maxPoints;
        input.value = points;
    }
    if (points < 0) {
        points = 0;
        input.value = points;
    }
    
    let discountAmount = points * 1000;
    document.getElementById('points_discount_text').innerText = 'Giảm: -' + discountAmount.toLocaleString('vi-VN') + '₫';
    
    updateTotals();
}

function updateTotals() {
    let checkedShipping = document.querySelector('input[name="shipping_method_id"]:checked');
    let cost = 0;
    if (checkedShipping) {
        cost = parseFloat(checkedShipping.getAttribute('data-cost')) || 0;
    }

    let subtotal = {{ $cart->total }};
    let discount = {{ session('applied_coupon')['discount_amount'] ?? 0 }};
    
    let pointsInput = document.getElementById('use_points_input');
    let pointsDiscount = 0;
    if (pointsInput) {
        pointsDiscount = (parseInt(pointsInput.value) || 0) * 1000;
    }
    
    let grandTotal = Math.max(0, subtotal + cost - discount - pointsDiscount);
    document.getElementById('summary-grand-total').innerText = grandTotal.toLocaleString('vi-VN') + '₫';
}

function applyCouponCheckout() {
    let code = document.getElementById('checkout_coupon_code').value;
    let msgEl = document.getElementById('checkout-coupon-msg');
    if (!code) {
        msgEl.innerHTML = '<span style="color: #c0392b;">Vui lòng nhập mã ưu đãi</span>';
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
            window.location.reload(); // Reload to update PHP grand totals easily
        } else {
            msgEl.innerHTML = '<span style="color: #c0392b;">' + data.message + '</span>';
        }
    });
}

// Initial active setup
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('input[name="saved_address_id"]').forEach(r => {
        if(r.checked) r.closest('.checkout-radio-block').classList.add('active');
    });
    
    let checkedShipping = document.querySelector('input[name="shipping_method_id"]:checked');
    if (checkedShipping) {
        updateShippingSelect(checkedShipping);
    }
});

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
    
    setTimeout(() => {
        btnSubmit.disabled = true;
        btnSubmit.style.opacity = '0.6';
        btnSubmit.style.cursor = 'not-allowed';
        btnText.textContent = 'ĐANG XỬ LÝ...';
    }, 10);
});
</script>
@endsection
