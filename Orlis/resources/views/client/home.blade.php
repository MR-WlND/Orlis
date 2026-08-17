@extends('layouts.client')

@section('title', 'Orlis - Thương Hiệu Thời Trang Cao Cấp')

@section('content')
    <!-- Hero Section -->
    @if(isset($homeHero))
    <style>
        .hero-banner-{{ $homeHero->id }} { background-image: url('{{ Storage::url($homeHero->image_mobile_path ?? $homeHero->image_path) }}'); }
        @media (min-width: 768px) { .hero-banner-{{ $homeHero->id }} { background-image: url('{{ Storage::url($homeHero->image_path) }}'); } }
    </style>
    <a href="{{ $homeHero->link_url ?? '#' }}" target="{{ $homeHero->link_target ?? '_self' }}" style="display: block; text-decoration: none;">
        <section class="hero hero-banner-{{ $homeHero->id }}">
            <div class="hero-content" style="color: {{ $homeHero->text_color }};">
                <p>{{ $homeHero->description }}</p>
                <h1 style="color: {{ $homeHero->text_color }};">{{ $homeHero->title }}</h1>
            </div>
        </section>
    </a>
    @else
    <section class="hero" style="background-image: url('{{ asset('images/orlis_hero.png') }}');">
        <div class="hero-content">
            <p>Bộ Sưu Tập Mùa Thu 2026</p>
            <h1>Một vở kịch của những sự tương phản</h1>
            <p style="font-size: 12px;">Khám phá bộ sưu tập</p>
        </div>
    </section>
    @endif

    <!-- Double Banner -->
    <section class="double-banner">
        @if(isset($homeDouble) && $homeDouble->count() > 0)
            @foreach($homeDouble as $banner)
            <style>
                .double-banner-{{ $banner->id }} { background-image: url('{{ Storage::url($banner->image_mobile_path ?? $banner->image_path) }}'); background-size: cover; background-position: center; min-height: 400px; display: block; }
                @media (min-width: 768px) { .double-banner-{{ $banner->id }} { background-image: url('{{ Storage::url($banner->image_path) }}'); } }
            </style>
            <a href="{{ $banner->link_url ?? '#' }}" target="{{ $banner->link_target ?? '_self' }}" class="banner-item double-banner-{{ $banner->id }}" style="text-decoration: none; color: inherit; position: relative;">
                <div style="position: absolute; bottom: 20px; left: 20px;">
                    <h3 style="color: {{ $banner->text_color }}; margin: 0; font-size: 20px; font-weight: 500;">{{ $banner->title }}</h3>
                    <p style="color: {{ $banner->text_color }}; margin: 5px 0 0 0; font-size: 12px;">{{ $banner->description }}</p>
                </div>
            </a>
            @endforeach
        @else
            <div class="banner-item">
                <img src="{{ asset('images/orlis_shoes.png') }}" alt="Giày da">
                <h3>Giày da cao cấp</h3>
            </div>
            <div class="banner-item">
                <img src="{{ asset('images/orlis_bag.png') }}" alt="Túi xách">
                <h3>Túi xách độc bản</h3>
            </div>
        @endif
    </section>

    <!-- Middle Banner -->
    @if(isset($homeWide))
    <style>
        .wide-banner-{{ $homeWide->id }} { background-image: url('{{ Storage::url($homeWide->image_mobile_path ?? $homeWide->image_path) }}'); }
        @media (min-width: 768px) { .wide-banner-{{ $homeWide->id }} { background-image: url('{{ Storage::url($homeWide->image_path) }}'); } }
    </style>
    <a href="{{ $homeWide->link_url ?? '#' }}" target="{{ $homeWide->link_target ?? '_self' }}" style="display: block; text-decoration: none;">
        <section class="mid-banner wide-banner-{{ $homeWide->id }}">
            <div class="mid-banner-content" style="color: {{ $homeWide->text_color }};">
                <h2 style="color: {{ $homeWide->text_color }};">{{ $homeWide->title }}</h2>
                <p style="font-size: 13px; text-transform: uppercase; letter-spacing: 1px;">{{ $homeWide->description }}</p>
            </div>
        </section>
    </a>
    @else
    <section class="mid-banner" style="background-image: url('https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=1920&q=80');">
        <div class="mid-banner-content">
            <h2>Di sản được tái hiện</h2>
            <p style="font-size: 13px; text-transform: uppercase; letter-spacing: 1px;">Khám phá câu chuyện</p>
        </div>
    </section>
    @endif

    <!-- Categories -->
    <section class="categories">
        <div class="category-item">
            <div class="category-img">
                <img src="{{ asset('images/orlis_bag.png') }}" alt="Túi xách">
            </div>
            <h4>Túi xách</h4>
        </div>
        <div class="category-item">
            <div class="category-img">
                <img src="{{ asset('images/orlis_shoes.png') }}" alt="Giày cao gót">
            </div>
            <h4>Giày cao gót</h4>
        </div>
        <div class="category-item">
            <div class="category-img">
                <img src="{{ asset('images/orlis_perfume.png') }}" alt="Nước hoa">
            </div>
            <h4>Nước hoa</h4>
        </div>
        <div class="category-item">
            <div class="category-img">
                <img src="{{ asset('images/orlis_scarf.png') }}" alt="Khăn lụa">
            </div>
            <h4>Khăn lụa</h4>
        </div>
    </section>

    <!-- Info Section -->
    <section class="info-section">
        <div class="info-text">
            <h2>Bước hoàn<br>thiện cuối<br>cùng</h2>
            <p>Sự hoàn hảo nằm ở từng chi tiết. Khám phá các dịch vụ đặc quyền của Orlis để nâng tầm trải nghiệm mua sắm của bạn.</p>
        </div>
        <div class="info-card">
            <img src="https://images.unsplash.com/photo-1549465220-1a8b9238cd48?auto=format&fit=crop&w=600&q=80" alt="Gói Quà Nghệ Thuật">
            <div class="info-card-content">
                <h4>Gói Quà Nghệ Thuật</h4>
                <a href="#">Xem thêm</a>
            </div>
        </div>
        <div class="info-card">
            <img src="https://images.unsplash.com/photo-1580674285054-bed31e145f59?auto=format&fit=crop&w=600&q=80" alt="Giao Hàng Hỏa Tốc">
            <div class="info-card-content">
                <h4>Giao Hàng Hỏa Tốc</h4>
                <a href="#">Xem thêm</a>
            </div>
        </div>
        <div class="info-card">
            <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=600&q=80" alt="Đổi Trả Dễ Dàng">
            <div class="info-card-content">
                <h4>Đổi Trả Dễ Dàng</h4>
                <a href="#">Xem thêm</a>
            </div>
        </div>
        <div class="info-card">
            <img src="https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=600&q=80" alt="Tư Vấn Cá Nhân">
            <div class="info-card-content">
                <h4>Tư Vấn Chuyên Gia</h4>
                <a href="#">Xem thêm</a>
            </div>
        </div>
        <div class="info-card">
            <img src="https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=600&q=80" alt="Bảo Dưỡng Đồ Da">
            <div class="info-card-content">
                <h4>Bảo Dưỡng Đồ Da</h4>
                <a href="#">Xem thêm</a>
            </div>
        </div>
    </section>

    </section>
@endsection
