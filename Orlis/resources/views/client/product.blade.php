@extends('layouts.client')

@section('title', 'Orlis - ' . $product->name)

@section('content')
<div class="pdp-container">
    <div class="pdp-gallery">
        @forelse($images as $img)
            <img src="{{ Storage::url($img) }}" alt="{{ $product->name }}">
        @empty
            <img src="{{ asset('images/orlis_model_1.png') }}" alt="{{ $product->name }}">
        @endforelse
    </div>
    
    <div class="pdp-info-wrapper">
        <div class="pdp-info">
            <h1 class="pdp-title" style="margin-bottom: 5px;">{{ $product->name }}</h1>
            <p class="pdp-subtitle" style="font-family: var(--font-sans); font-size: 14px; color: #555; margin-bottom: 30px;">
                {{ $product->category ? $product->category->translated_name : __('messages.premium_product_default') }}
            </p>

            @if(isset($product->variants) && $product->variants->count() > 0)
                @php
                    // Build variant map để JS tra cứu nhanh
                    $variantMap = $product->variants->map(fn($v) => [
                        'id'    => $v->id,
                        'color' => $v->color,
                        'size'  => $v->size,
                        'price' => $v->price_override ?? ($product->sale_price ?? $product->price),
                    ]);

                    $colors = $product->variants->map(fn($v) => [
                        'hex'  => $v->color,
                        'name' => data_get(json_decode($v->getRawOriginal('attributes') ?? '{}', true), 'color_name', $v->color),
                    ])->filter(fn($c) => $c['hex'])->unique('hex')->values();

                    $sizes = $product->variants->pluck('size')->unique()->filter()->values();

                    $firstVariant = $product->variants->first();
                    $selectedColor = $firstVariant?->color;
                    $selectedSize  = $firstVariant?->size;
                @endphp

                {{-- Chọn màu sắc --}}
                @if($colors->count() > 0)
                <div style="margin-bottom: 20px;">
                    <p style="font-family: var(--font-sans); font-size: 12px; font-weight: 600; letter-spacing: 1px; color: #888; text-transform: uppercase; margin-bottom: 10px;">
                        {{ __('messages.other_color') }}
                    </p>
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        @foreach($colors as $i => $colorItem)
                        <button type="button"
                            class="variant-color-btn"
                            data-color="{{ $colorItem['hex'] }}"
                            title="{{ $colorItem['name'] }}"
                            style="
                                width: 30px; height: 30px;
                                background: {{ $colorItem['hex'] }};
                                border-radius: 3px;
                                border: 1px solid {{ $i === 0 ? '#1a1a1a' : '#e0e0e0' }};
                                cursor: pointer;
                                transition: border-color 0.15s;
                                padding: 0;
                                outline: none;
                                box-shadow: {{ $i === 0 ? '0 0 0 1px #1a1a1a' : 'none' }};
                            ">
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Chọn kích thước --}}
                @if($sizes->count() > 0)
                <div style="margin-bottom: 24px;">
                    <p style="font-family: var(--font-sans); font-size: 12px; font-weight: 600; letter-spacing: 1px; color: #888; text-transform: uppercase; margin-bottom: 10px;">
                        {{ __('messages.tab_size') }}
                    </p>
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        @foreach($sizes as $i => $size)
                        <button type="button"
                            class="variant-size-btn"
                            data-size="{{ $size }}"
                            style="
                                padding: 7px 16px;
                                font-size: 13px;
                                font-family: var(--font-sans);
                                border: 1px solid {{ $i === 0 ? '#1a1a1a' : '#d0d0d0' }};
                                background: white;
                                color: #333;
                                cursor: pointer;
                                transition: all 0.15s;
                                letter-spacing: 0.5px;
                            ">
                            {{ $size }}
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Giá sẽ được cập nhật trực tiếp vào nút Thêm vào giỏ hàng --}}

                <script id="variant-data" type="application/json">
                    {!! $variantMap->toJson() !!}
                </script>
            @endif

            <div class="pdp-actions-modern">
                <form action="{{ route('cart.add') }}" method="POST" style="width: 100%;">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    @if(isset($product->variants) && $product->variants->count() > 0)
                        <input type="hidden" name="variant_id" id="selectedVariantId" value="{{ $product->variants->first()->id }}">
                    @endif
                    {{-- Hiển thị giá trong nút (cập nhật qua JS) --}}
                    <button type="submit" class="btn-pdp-modern btn-dark-modern">
                        <span class="btn-left">{{ __('messages.add_to_cart') }}</span>
                        <span class="btn-right" id="btn-add-cart-price">
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <span style="text-decoration: line-through; opacity: 0.7; margin-right: 5px;">{{ number_format($product->price, 0, ',', '.') }} ₫</span>
                                {{ number_format($product->sale_price, 0, ',', '.') }} ₫
                            @else
                                {{ number_format($product->price, 0, ',', '.') }} ₫
                            @endif
                        </span>
                    </button>
                    <button type="submit" name="buy_now" value="1" class="btn-pdp-modern btn-light-modern" style="margin-top: 10px;">
                        <span class="btn-left">{{ __('messages.quick_checkout') }}</span>
                        <span class="btn-right" style="display: flex; align-items: center; gap: 5px;">
                            Thanh toán
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 10h18"/><path d="M7 15h.01"/></svg>
                        </span>
                    </button>
                </form>
            </div>

            <div class="pdp-notices">
                <p>{{ __('messages.receive_earliest') }} {{ now()->addDays(3)->format('d/m') }}.</p>
                <br>
                <p>{{ __('messages.support_team') }}</p>
                <p>{{ __('messages.contact_phone') }} +1 800 929 3467</p>
            </div>

            <div class="pdp-tabs">
                <div class="pdp-tab active" data-target="tab-desc">{{ __('messages.tab_desc') }}</div>
                <div class="pdp-tab" data-target="tab-size">{{ __('messages.tab_size') }}</div>
                <div class="pdp-tab" data-target="tab-contact">{{ __('messages.tab_contact') }}</div>
                <div class="pdp-tab" data-target="tab-shipping">{{ __('messages.tab_shipping') }}</div>
            </div>

            <div class="pdp-tab-content" id="tab-desc" style="display: block; line-height: 1.8;">
                {!! nl2br(e($product->description)) !!}
            </div>

            <div class="pdp-tab-content" id="tab-size" style="display: none; line-height: 1.8;">
                @if(!empty($product->size_guide))
                    {!! nl2br(e($product->size_guide)) !!}
                @else
                    <p style="color: #888; font-style: italic;">Đang cập nhật hướng dẫn kích thước cho sản phẩm này.</p>
                @endif
            </div>
            
            <div class="pdp-tab-content" id="tab-contact" style="display: none; line-height: 1.8;">
                <p><strong>Dịch vụ Khách hàng Orlis Concierge:</strong></p>
                <ul style="margin-top: 10px; padding-left: 20px; line-height: 1.8;">
                    <li><strong>Hotline đặc quyền:</strong> 1800 929 3467 (Miễn phí cước)</li>
                    <li><strong>Email:</strong> concierge@orlis.com</li>
                    <li><strong>Thời gian hoạt động:</strong> 24/7, bao gồm cả ngày lễ.</li>
                </ul>
                <p style="margin-top: 15px; color: #666;">Mọi yêu cầu về tư vấn phong cách, đặt thiết kế riêng hoặc bảo hành sẽ được chuyên viên của chúng tôi tiếp nhận và ưu tiên xử lý trong vòng 2 giờ làm việc.</p>
            </div>
            
            <div class="pdp-tab-content" id="tab-shipping" style="display: none; line-height: 1.8;">
                <p><strong>Đặc quyền giao nhận Orlis Premium:</strong></p>
                <ul style="margin-top: 10px; padding-left: 20px; line-height: 1.8;">
                    <li><strong>Vận chuyển miễn phí toàn cầu:</strong> Áp dụng cho mọi đơn hàng. Hàng hóa được đóng gói bảo mật 3 lớp trong kiện bảo vệ chuyên dụng.</li>
                    <li><strong>Thời gian:</strong> 1-3 ngày làm việc đối với nội địa, 3-7 ngày làm việc đối với quốc tế thông qua các đối tác vận chuyển cao cấp.</li>
                    <li><strong>Chính sách hoàn trả:</strong> Orlis hỗ trợ đổi trả hoặc hoàn tiền trong vòng 30 ngày kể từ khi nhận hàng. Dịch vụ thu hồi tận nơi hoàn toàn miễn phí.</li>
                </ul>
                <p style="margin-top: 15px; font-weight: 600;">* Mỗi kiệt tác đều được trao đến tay quý khách kèm hộp quà tặng Orlis sang trọng, ruy băng dệt nổi và thiệp viết tay theo yêu cầu cá nhân hóa.</p>
            </div>
        </div>
    </div>
</div>


@if(isset($relatedProducts) && $relatedProducts->count() > 0)
<div style="padding: 60px 60px 40px; background: #fff;">
    <h2 style="font-family: var(--font-serif); font-size: 32px; font-weight: 400; color: #333; text-align: center; margin-bottom: 40px;">Có thể bạn sẽ thích</h2>
    <div class="catalog-grid">
        @foreach($relatedProducts as $related)
            <a href="{{ route('product', $related->id) }}" class="product-card">
                <div class="product-img">
                    <img src="{{ $related->thumbnail ? Storage::url($related->thumbnail) : asset('images/orlis_model_1.png') }}" alt="{{ $related->name }}">
                </div>
                <div class="product-info center-info">
                    <h3 style="font-family: var(--font-serif); font-size: 18px; font-weight: 400; color: #333; margin-bottom: 8px;">{{ $related->name }}</h3>
                    <p style="font-size: 14px; color: #666;">{{ number_format($related->sale_price ?? $related->price, 0, ',', '.') }} ₫</p>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endif

@if(isset($recentlyViewed) && $recentlyViewed->count() > 0)
<div style="padding: 40px 60px 80px; background: #fff;">
    <h2 style="font-family: var(--font-serif); font-size: 32px; font-weight: 400; color: #333; text-align: center; margin-bottom: 40px;">Đã xem gần đây</h2>
    <div class="catalog-grid">
        @foreach($recentlyViewed as $viewed)
            <a href="{{ route('product', $viewed->id) }}" class="product-card">
                <div class="product-img">
                    <img src="{{ $viewed->thumbnail ? Storage::url($viewed->thumbnail) : asset('images/orlis_model_1.png') }}" alt="{{ $viewed->name }}">
                </div>
                <div class="product-info center-info">
                    <h3 style="font-family: var(--font-serif); font-size: 18px; font-weight: 400; color: #333; margin-bottom: 8px;">{{ $viewed->name }}</h3>
                    <p style="font-size: 14px; color: #666;">{{ number_format($viewed->sale_price ?? $viewed->price, 0, ',', '.') }} ₫</p>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const variantData = JSON.parse(document.getElementById('variant-data')?.textContent || '[]');
    let selectedColor = variantData[0]?.color || null;
    let selectedSize  = variantData[0]?.size  || null;

    function findVariant(color, size) {
        return variantData.find(v => v.color === color && v.size === size)
            || variantData.find(v => v.color === color)
            || variantData.find(v => v.size === size)
            || variantData[0];
    }

    function updateUI() {
        const v = findVariant(selectedColor, selectedSize);
        if (!v) return;

        // Cập nhật variant_id
        const input = document.getElementById('selectedVariantId');
        if (input) input.value = v.id;

        // Cập nhật giá
        const priceEl = document.getElementById('btn-add-cart-price');
        if (priceEl) priceEl.textContent = new Intl.NumberFormat('vi-VN').format(v.price) + ' ₫';
    }

    // Xử lý nút màu
    document.querySelectorAll('.variant-color-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            selectedColor = this.dataset.color;

            document.querySelectorAll('.variant-color-btn').forEach(b => {
                b.style.border = '1px solid #e0e0e0';
                b.style.boxShadow = 'none';
            });
            this.style.border = '1px solid #1a1a1a';
            this.style.boxShadow = '0 0 0 1px #1a1a1a';

            updateUI();
        });
    });

    // Xử lý nút size
    document.querySelectorAll('.variant-size-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            selectedSize = this.dataset.size;

            document.querySelectorAll('.variant-size-btn').forEach(b => {
                b.style.border = '1px solid #d0d0d0';
            });
            this.style.border = '1px solid #1a1a1a';

            updateUI();
        });
    });

    // Xử lý Tabs
    document.querySelectorAll('.pdp-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            // Xóa active khỏi tất cả tabs
            document.querySelectorAll('.pdp-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Ẩn tất cả nội dung
            document.querySelectorAll('.pdp-tab-content').forEach(c => c.style.display = 'none');
            
            // Hiện nội dung tab được chọn
            const targetId = this.getAttribute('data-target');
            const targetContent = document.getElementById(targetId);
            if(targetContent) {
                targetContent.style.display = 'block';
            }
        });
    });
});
</script>
@endsection
