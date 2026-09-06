@extends('layouts.client')

@section('title', 'Nước Hoa - Orlis')

@section('content')
    <div class="perfume-page">
        
        <!-- Hero Section -->
        <section class="beauty-hero-grid">
            @if(isset($beautyHero) && $beautyHero->count() > 0)
                @php 
                    $banner1 = $beautyHero->first(); 
                    $banner2 = $beautyHero->skip(1)->first() ?? $banner1;
                @endphp
                <a href="{{ $banner1->link_url ?: '#' }}" class="beauty-hero-item">
                    <div class="beauty-hero-bg" style="background-image: url('{{ Storage::url($banner1->image_mobile_path ?: $banner1->image_path) }}');"></div>
                    <div class="beauty-item-content content-bottom">
                        <p class="beauty-item-desc">Một loại chypre vani đầy gợi cảm</p>
                        <span class="beauty-hero-btn">Khám phá</span>
                    </div>
                </a>
                <div class="beauty-hero-item">
                    <video autoplay loop muted playsinline style="width: 100%; height: 100%; object-fit: cover;">
                        <source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4">
                    </video>
                    <div class="beauty-item-content content-center">
                        <h4 class="beauty-hero-subtitle">Hoa hậu Dior Eau de Parfum</h4>
                        <h2 class="beauty-hero-title">Biểu tượng<br>thời trang<br>cao cấp mới</h2>
                        <a href="#" class="beauty-hero-btn">Khám phá</a>
                    </div>
                </div>
                <a href="{{ $banner2->link_url ?: '#' }}" class="beauty-hero-item">
                    <div class="beauty-hero-bg" style="background-image: url('{{ Storage::url($banner2->image_mobile_path ?: $banner2->image_path) }}');"></div>
                    <div class="beauty-item-content content-bottom">
                        <p class="beauty-item-desc">Hoa Dior trong hương Dior</p>
                        <span class="beauty-hero-btn">Khám phá</span>
                    </div>
                </a>
            @else
                <a href="/catalog/nuoc-hoa-lam-dep-nuoc-hoa" class="beauty-hero-item">
                    <div class="beauty-hero-bg" style="background-image: url('https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=600&q=80');"></div>
                    <div class="beauty-item-content content-bottom">
                        <p class="beauty-item-desc">Một loại chypre vani đầy gợi cảm</p>
                        <span class="beauty-hero-btn">Khám phá</span>
                    </div>
                </a>
                <div class="beauty-hero-item">
                    <video autoplay loop muted playsinline style="width: 100%; height: 100%; object-fit: cover;">
                        <source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4">
                    </video>
                    <div class="beauty-item-content content-center">
                        <h4 class="beauty-hero-subtitle">Hoa hậu Dior Eau de Parfum</h4>
                        <h2 class="beauty-hero-title">Biểu tượng<br>thời trang<br>cao cấp mới</h2>
                        <a href="/catalog/nuoc-hoa-lam-dep-nuoc-hoa" class="beauty-hero-btn">Khám phá</a>
                    </div>
                </div>
                <a href="/catalog/nuoc-hoa-lam-dep-nuoc-hoa" class="beauty-hero-item">
                    <div class="beauty-hero-bg" style="background-image: url('https://images.unsplash.com/photo-1590736969955-71cc94801759?auto=format&fit=crop&w=600&q=80');"></div>
                    <div class="beauty-item-content content-bottom">
                        <p class="beauty-item-desc">Hoa Dior trong hương Dior</p>
                        <span class="beauty-hero-btn">Khám phá</span>
                    </div>
                </a>
            @endif
        </section>

        <!-- Có thể bạn sẽ thích -->
        @if(isset($recommendedPerfumes) && $recommendedPerfumes->count() > 0)
        <section class="perfume-products-section">
            <h2 class="perfume-section-title">Có thể bạn sẽ thích</h2>
            <div class="perfume-product-grid">
                @foreach($recommendedPerfumes as $perfume)
                <a href="{{ route('product', $perfume->id) }}" class="perfume-product-card" style="text-decoration: none; color: inherit; display: block;">
                    <div class="product-img-wrapper">
                        <img src="{{ $perfume->thumbnail ? Storage::url($perfume->thumbnail) : 'https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $perfume->name }}">
                    </div>
                    <div class="product-info">
                        <span class="product-category">{{ $perfume->category ? ($perfume->category->translated_name ?? $perfume->category->name) : 'Nước hoa' }}</span>
                        <h3 class="product-name" style="font-family: var(--font-serif); font-size: 16px; font-weight: 400; color: #333; margin-top: 5px;">{{ $perfume->name }}</h3>
                        <p class="product-price">{{ number_format($perfume->sale_price ?? $perfume->price, 0, ',', '.') }} ₫</p>
                    </div>
                </a>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Middle Banner -->
        @if(isset($beautyWide) && $beautyWide->count() > 0)
            @php $wideBanner = $beautyWide->first(); @endphp
            <style>
                .wide-banner-{{ $wideBanner->id }} { background-image: url('{{ Storage::url($wideBanner->image_mobile_path ?? $wideBanner->image_path) }}'); }
                @media (min-width: 768px) { .wide-banner-{{ $wideBanner->id }} { background-image: url('{{ Storage::url($wideBanner->image_path) }}'); } }
            </style>
            <a href="{{ $wideBanner->link_url ?? '#' }}" target="{{ $wideBanner->link_target ?? '_self' }}" style="display: block; text-decoration: none; margin: 40px 0;">
                <section class="mid-banner wide-banner-{{ $wideBanner->id }}" style="height: 400px; background-size: cover; background-position: center; display: flex; align-items: center; justify-content: center; text-align: center;">
                    <div class="mid-banner-content" style="color: {{ $wideBanner->text_color }};">
                        <h2 style="color: {{ $wideBanner->text_color }};">{{ $wideBanner->title }}</h2>
                        <p style="font-size: 13px; text-transform: uppercase; letter-spacing: 1px;">{{ $wideBanner->description }}</p>
                    </div>
                </section>
            </a>
        @endif

        <!-- Sản phẩm bán chạy -->
        @if(isset($bestSellingPerfumes) && $bestSellingPerfumes->count() > 0)
        <section class="perfume-products-section">
            <h2 class="perfume-section-title">Sản phẩm bán chạy</h2>
            <div class="perfume-product-grid">
                @foreach($bestSellingPerfumes as $perfume)
                <a href="{{ route('product', $perfume->id) }}" class="perfume-product-card" style="text-decoration: none; color: inherit; display: block;">
                    <div class="product-img-wrapper">
                        <img src="{{ $perfume->thumbnail ? Storage::url($perfume->thumbnail) : 'https://images.unsplash.com/photo-1588405748880-12d1d2a59f75?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $perfume->name }}">
                    </div>
                    <div class="product-info">
                        <span class="product-category">{{ $perfume->category ? ($perfume->category->translated_name ?? $perfume->category->name) : 'Nước hoa' }}</span>
                        <h3 class="product-name" style="font-family: var(--font-serif); font-size: 16px; font-weight: 400; color: #333; margin-top: 5px;">{{ $perfume->name }}</h3>
                        <p class="product-price">{{ number_format($perfume->sale_price ?? $perfume->price, 0, ',', '.') }} ₫</p>
                    </div>
                </a>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Bottom Grid Section -->
        <section class="perfume-bottom-grid">
            @if(isset($beautyDouble) && $beautyDouble->count() > 0)
                @foreach($beautyDouble as $banner)
                <div class="perfume-bottom-card">
                    <img src="{{ Storage::url($banner->image_path) }}" alt="{{ $banner->title }}">
                    <div class="bottom-card-content">
                        <h3 style="color: {{ $banner->text_color }};">{{ $banner->title }}</h3>
                        <p style="color: {{ $banner->text_color }};">{{ $banner->description }}</p>
                        @if($banner->link_url)
                        <a href="{{ $banner->link_url }}" target="{{ $banner->link_target ?? '_self' }}" style="color: {{ $banner->text_color }};">Khám phá</a>
                        @endif
                    </div>
                </div>
                @endforeach
            @else
                <div class="perfume-bottom-card">
                    <img src="https://images.unsplash.com/photo-1594035987173-16a5d9333919?auto=format&fit=crop&w=600&q=80" alt="Mang đến điều bất ngờ">
                    <div class="bottom-card-content">
                        <h3>Mang đến điều bất ngờ</h3>
                        <p>Hãy cùng khám phá các dòng nước hoa tinh tế và quyến rũ được tạo nên bởi các nhà thiết kế nước hoa hàng đầu, đem đến cho bạn một sức quyến rũ không thể cưỡng lại.</p>
                        <a href="#">Tìm hiểu thêm về bộ sưu tập nước hoa</a>
                    </div>
                </div>
                <div class="perfume-bottom-card">
                    <img src="https://images.unsplash.com/photo-1590736969955-71cc94801759?auto=format&fit=crop&w=600&q=80" alt="Hương thơm tinh tế">
                    <div class="bottom-card-content">
                        <h3>Hương thơm tinh tế</h3>
                        <p>Sự kết hợp hoàn hảo giữa những tinh chất hương hoa, mang lại một mùi hương nhẹ nhàng thanh tao, gợi lên nét quyến rũ sang trọng cho phái đẹp.</p>
                        <a href="#">Khám phá quà tặng</a>
                    </div>
                </div>
                <div class="perfume-bottom-card">
                    <img src="https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?auto=format&fit=crop&w=600&q=80" alt="Quyến rũ mọi góc nhìn">
                    <div class="bottom-card-content">
                        <h3>Quyến rũ mọi góc nhìn</h3>
                        <p>Tận hưởng sự kết hợp độc đáo từ các thành phần quý phái, tạo ra một hương thơm đặc biệt giúp bạn nổi bật trong mọi khoảnh khắc.</p>
                        <a href="#">Tìm hiểu thêm về quà tặng cho doanh nghiệp</a>
                    </div>
                </div>
            @endif
        </section>
        
    </div>
@endsection
