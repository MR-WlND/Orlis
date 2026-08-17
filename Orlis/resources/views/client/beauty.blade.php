@extends('layouts.client')

@section('title', 'Orlis - Thế Giới Nước Hoa & Làm Đẹp')

@section('content')
    <!-- Hero Section -->
    @if(isset($beautyHero))
    <style>
        .hero-banner-{{ $beautyHero->id }} { background-image: url('{{ Storage::url($beautyHero->image_mobile_path ?? $beautyHero->image_path) }}'); }
        @media (min-width: 768px) { .hero-banner-{{ $beautyHero->id }} { background-image: url('{{ Storage::url($beautyHero->image_path) }}'); } }
    </style>
    <a href="{{ $beautyHero->link_url ?? '#' }}" target="{{ $beautyHero->link_target ?? '_self' }}" style="display: block; text-decoration: none;">
        <section class="hero hero-banner-{{ $beautyHero->id }}">
            <div class="hero-content" style="color: {{ $beautyHero->text_color }};">
                <p>{{ $beautyHero->description }}</p>
                <h1 style="color: {{ $beautyHero->text_color }};">{{ $beautyHero->title }}</h1>
            </div>
        </section>
    </a>
    @else
    <section class="hero" style="background-image: url('https://images.unsplash.com/photo-1594035987173-16a5d9333919?auto=format&fit=crop&w=1920&q=80');">
        <div class="hero-content">
            <p>Bộ Sưu Tập Nước Hoa</p>
            <h1>Tinh hoa của nghệ thuật ướp hương</h1>
            <p style="font-size: 12px;">Khám phá thế giới sắc đẹp</p>
        </div>
    </section>
    @endif

    <!-- Double Banner -->
    <section class="double-banner">
        @if(isset($beautyDouble) && $beautyDouble->count() > 0)
            @foreach($beautyDouble as $banner)
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
            <div class="banner-item" style="background-image: url('https://images.unsplash.com/photo-1590736969955-71cc94801759?auto=format&fit=crop&w=800&q=80'); background-size: cover;">
                <h3 style="color:#fff;">Nước hoa Nữ</h3>
            </div>
            <div class="banner-item" style="background-image: url('https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?auto=format&fit=crop&w=800&q=80'); background-size: cover;">
                <h3 style="color:#fff;">Trang điểm</h3>
            </div>
        @endif
    </section>

    <!-- Middle Banner -->
    @if(isset($beautyWide))
    <style>
        .wide-banner-{{ $beautyWide->id }} { background-image: url('{{ Storage::url($beautyWide->image_mobile_path ?? $beautyWide->image_path) }}'); }
        @media (min-width: 768px) { .wide-banner-{{ $beautyWide->id }} { background-image: url('{{ Storage::url($beautyWide->image_path) }}'); } }
    </style>
    <a href="{{ $beautyWide->link_url ?? '#' }}" target="{{ $beautyWide->link_target ?? '_self' }}" style="display: block; text-decoration: none;">
        <section class="mid-banner wide-banner-{{ $beautyWide->id }}">
            <div class="mid-banner-content" style="color: {{ $beautyWide->text_color }};">
                <h2 style="color: {{ $beautyWide->text_color }};">{{ $beautyWide->title }}</h2>
                <p style="font-size: 13px; text-transform: uppercase; letter-spacing: 1px;">{{ $beautyWide->description }}</p>
            </div>
        </section>
    </a>
    @else
    <section class="mid-banner" style="background-image: url('https://images.unsplash.com/photo-1588405748880-12d1d2a59f75?auto=format&fit=crop&w=1920&q=80');">
        <div class="mid-banner-content">
            <h2>Nghệ thuật chăm sóc da</h2>
            <p style="font-size: 13px; text-transform: uppercase; letter-spacing: 1px;">Khám phá ngay</p>
        </div>
    </section>
    @endif
@endsection
