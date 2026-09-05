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
                {{ $product->category ? $product->category->name : 'Sản phẩm cao cấp' }}
            </p>

            @if(isset($product->variants) && $product->variants->count() > 0)
                @php
                    $colors = $product->variants->pluck('color')->unique()->filter();
                    $sizes = $product->variants->pluck('size')->unique()->filter();
                @endphp
                
                @if($colors->count() > 0)
                    <div class="pdp-color-section" style="margin-bottom: 25px;">
                        <p style="font-family: var(--font-sans); font-size: 13px; color: #333; margin-bottom: 10px;">Other color</p>
                        <div class="color-selector">
                            @foreach($colors as $index => $color)
                                <span class="color-btn {{ $index === 0 ? 'active' : '' }}" style="background: {{ $color }}; border-radius: 2px; width: 30px; height: 30px;" title="{{ $color }}"></span>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <!-- Sizes can go here if needed later -->
            @endif

            <div class="pdp-actions-modern">
                <button class="btn-pdp-modern btn-dark-modern">
                    <span class="btn-left">Thêm vào giỏ hàng</span>
                    <span class="btn-right">
                        @if($product->sale_price && $product->sale_price < $product->price)
                            <span style="text-decoration: line-through; opacity: 0.7; margin-right: 5px;">{{ number_format($product->price, 0, ',', '.') }} ₫</span>
                            {{ number_format($product->sale_price, 0, ',', '.') }} ₫
                        @else
                            {{ number_format($product->price, 0, ',', '.') }} ₫
                        @endif
                    </span>
                </button>
                <button class="btn-pdp-modern btn-light-modern">
                    <span class="btn-left">Thanh toán nhanh</span>
                    <span class="btn-right" style="display: flex; align-items: center; gap: 5px;">
                        VNpay
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 10h18"/><path d="M7 15h.01"/></svg>
                    </span>
                </button>
            </div>

            <div class="pdp-notices">
                <p>Nhận hàng sớm nhất vào ngày {{ now()->addDays(3)->format('d \t\h\á\n\g m') }}.</p>
                <br>
                <p>Đội ngũ tư vấn khách hàng của chúng tôi rất hân hạnh được hỗ trợ bạn.</p>
                <p>Vui lòng liên hệ với chúng tôi theo số +1 800 929 3467</p>
            </div>

            <div class="pdp-tabs">
                <div class="pdp-tab active">Mô tả</div>
                <div class="pdp-tab">Kích thước</div>
                <div class="pdp-tab">Thông tin liên hệ</div>
                <div class="pdp-tab">Giao hàng & trả hàng</div>
            </div>

            <div class="pdp-tab-content">
                {!! nl2br(e($product->description)) !!}
            </div>
        </div>
    </div>
</div>

<!-- Đánh giá & Nhận xét -->
<div class="product-reviews-section" style="padding: 60px 60px; background: #fafafa; border-top: 1px solid #eee;">
    <h2 style="font-family: var(--font-serif); font-size: 28px; margin-bottom: 40px; text-align: center;">Đánh giá & Nhận xét ({{ $product->reviews->count() }})</h2>
    
    <div style="max-width: 900px; margin: 0 auto; display: grid; grid-template-columns: 1fr 2fr; gap: 40px;">
        <!-- Form đánh giá -->
        <div class="review-form-container">
            <h3 style="font-size: 18px; margin-bottom: 20px;">Viết đánh giá của bạn</h3>
            @if(auth()->check())
                <form action="{{ route('product.review', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px;">Số sao:</label>
                        <select name="rating" required style="width: 100%; padding: 10px; border: 1px solid #ccc;">
                            <option value="5">5 Sao - Tuyệt vời</option>
                            <option value="4">4 Sao - Tốt</option>
                            <option value="3">3 Sao - Tạm được</option>
                            <option value="2">2 Sao - Kém</option>
                            <option value="1">1 Sao - Tệ</option>
                        </select>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px;">Nhận xét:</label>
                        <textarea name="comment" rows="4" style="width: 100%; padding: 10px; border: 1px solid #ccc;" placeholder="Chia sẻ cảm nhận của bạn về sản phẩm..."></textarea>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 5px;">Đính kèm ảnh (tuỳ chọn):</label>
                        <input type="file" name="images[]" multiple accept="image/*" style="width: 100%;">
                    </div>
                    <button type="submit" class="btn-pdp-modern btn-dark-modern" style="width: 100%;">Gửi đánh giá</button>
                </form>
            @else
                <p style="color: #666; background: #fff; padding: 20px; border: 1px solid #eee;">
                    Vui lòng <a href="{{ route('role.login', 'customer') }}" style="color: var(--color-primary); text-decoration: underline;">đăng nhập</a> để gửi đánh giá.
                </p>
            @endif
        </div>

        <!-- Danh sách đánh giá -->
        <div class="reviews-list">
            @if($product->reviews->isEmpty())
                <p style="color: #666; font-style: italic;">Chưa có đánh giá nào cho sản phẩm này.</p>
            @else
                @foreach($product->reviews as $review)
                    <div class="review-item" style="margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                            <strong style="font-size: 16px;">{{ $review->user->name ?? 'Khách hàng' }}</strong>
                            <span style="color: #ff9800; font-size: 14px;">
                                {!! str_repeat('★', $review->rating) !!}{!! str_repeat('☆', 5 - $review->rating) !!}
                            </span>
                        </div>
                        <p style="color: #444; line-height: 1.6; margin-bottom: 10px;">{{ $review->comment }}</p>
                        @if(!empty($review->images))
                            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                @foreach($review->images as $img)
                                    <img src="{{ Storage::url($img) }}" alt="Review Image" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                                @endforeach
                            </div>
                        @endif
                        <small style="color: #999; display: block; margin-top: 10px;">Đã đánh giá vào {{ $review->created_at->format('d/m/Y') }}</small>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@if(isset($relatedProducts) && $relatedProducts->count() > 0)
<div style="padding: 80px 60px;">
    <h2 style="font-family: var(--font-serif); font-size: 28px; text-align: center; margin-bottom: 50px;">Sản Phẩm Cùng Danh Mục</h2>
    <div class="catalog-grid">
        @foreach($relatedProducts as $related)
            <a href="{{ route('product', $related->id) }}" class="product-card">
                <div class="product-img">
                    <img src="{{ $related->thumbnail ? Storage::url($related->thumbnail) : asset('images/orlis_model_1.png') }}" alt="{{ $related->name }}">
                </div>
                <div class="product-info center-info">
                    <h3>{{ $related->name }}</h3>
                    <p>{{ number_format($related->price, 0, ',', '.') }} ₫</p>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endif
@endsection
