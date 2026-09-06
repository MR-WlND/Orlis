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
            <p style="text-transform: uppercase;">{{ __('messages.fall_collection') }}</p>
            <h1>{{ __('messages.play_of_contrasts') }}</h1>
            <p style="font-size: 12px; text-transform: uppercase;">{{ __('messages.explore_collection') }}</p>
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
                <div class="desktop-banner-text" style="position: absolute; bottom: 30px; left: 0; right: 0; text-align: center;">
                    <h3 style="color: {{ $banner->text_color }}; margin: 0; font-size: 20px; font-weight: 500;">{{ $banner->title }}</h3>
                    <p style="color: {{ $banner->text_color }}; margin: 5px 0 0 0; font-size: 13px;">{{ $banner->description }}</p>
                </div>
            </a>
            @endforeach
        @else
            <div class="banner-item">
                <img src="{{ asset('images/orlis_shoes.png') }}" alt="Giày da">
                <h3 class="desktop-banner-title" style="position: absolute; bottom: 30px; left: 0; right: 0; text-align: center; margin: 0; z-index: 2; color: #fff; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">{{ __('messages.premium_shoes') }}</h3>
            </div>
            <div class="banner-item">
                <img src="{{ asset('images/orlis_bag.png') }}" alt="Túi xách">
                <h3 class="desktop-banner-title" style="position: absolute; bottom: 30px; left: 0; right: 0; text-align: center; margin: 0; z-index: 2; color: #fff; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">{{ __('messages.exclusive_bags') }}</h3>
            </div>
        @endif
    </section>

    <!-- Middle Banner -->
    @if(isset($homeWide))
    <style>
        .wide-banner-{{ $homeWide->id }} { background-image: url('{{ Storage::url($homeWide->image_mobile_path ?? $homeWide->image_path) }}'); }
        @media (min-width: 768px) { .wide-banner-{{ $homeWide->id }} { background-image: url('{{ Storage::url($homeWide->image_path) }}'); } }
    </style>
    <a href="{{ $homeWide->link_url ?? '#' }}" target="{{ $homeWide->link_target ?? '_self' }}" style="display: block; text-decoration: none; position: relative;">
        <section class="mid-banner wide-banner-{{ $homeWide->id }}">
            <div class="mid-banner-content" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; width: 100%; padding: 0 20px; box-sizing: border-box; z-index: 2;">
                <h2 style="color: {{ $homeWide->text_color }}; margin: 0; font-size: clamp(32px, 6vw, 64px); font-weight: 400; font-family: var(--font-serif); text-shadow: 0 4px 12px rgba(0,0,0,0.4); letter-spacing: 1px;">{{ $homeWide->title }}</h2>
                <p style="color: {{ $homeWide->text_color }}; font-size: clamp(10px, 2vw, 14px); font-weight: 600; font-family: var(--font-sans); text-transform: uppercase; letter-spacing: 3px; margin: 20px 0 0 0; text-shadow: 0 2px 6px rgba(0,0,0,0.4);">{{ $homeWide->description }}</p>
            </div>
        </section>
    </a>
    @else
    <section class="mid-banner" style="background-image: url('https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=1920&q=80'); position: relative;">
        <div class="mid-banner-content" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; width: 100%; padding: 0 20px; box-sizing: border-box; z-index: 2;">
            <h2 style="color: #fff; margin: 0; font-size: clamp(32px, 6vw, 64px); font-weight: 400; font-family: var(--font-serif); text-shadow: 0 4px 12px rgba(0,0,0,0.4); letter-spacing: 1px;">{{ __('messages.heritage_reborn') }}</h2>
            <p style="color: #fff; font-size: clamp(10px, 2vw, 14px); font-weight: 600; font-family: var(--font-sans); text-transform: uppercase; letter-spacing: 3px; margin: 20px 0 0 0; text-shadow: 0 2px 6px rgba(0,0,0,0.4);">{{ __('messages.discover_story') }}</p>
        </div>
    </section>
    @endif

    <!-- Categories -->
    <section class="categories">
        @php
            $displayCategories = collect();
            if (isset($globalCategories)) {
                foreach($globalCategories as $level1) {
                    foreach($level1->children as $level2) {
                        if ($displayCategories->count() < 4) {
                            $displayCategories->push($level2);
                        }
                    }
                }
            }
        @endphp

        @if($displayCategories->count() > 0)
            @foreach($displayCategories as $category)
            <div class="category-item">
                <div class="category-img">
                    <a href="{{ url('/catalog/' . $category->slug) }}" style="display:block; width:100%; height:100%;">
                        <img src="{{ $category->image ? (Str::startsWith($category->image, 'http') ? $category->image : Storage::url($category->image)) : asset('images/orlis_bag.png') }}" alt="{{ $category->translated_name ?? $category->name }}">
                    </a>
                </div>
                <a href="{{ url('/catalog/' . $category->slug) }}" style="text-decoration: none; color: inherit; display: block;">
                    <h4>{{ $category->translated_name ?? $category->name }}</h4>
                </a>
            </div>
            @endforeach
        @else
            <!-- Fallback if no categories -->
            <div class="category-item">
                <div class="category-img"><img src="{{ asset('images/orlis_bag.png') }}" alt="Túi xách"></div>
                <h4>{{ __('messages.handbags') }}</h4>
            </div>
            <div class="category-item">
                <div class="category-img"><img src="{{ asset('images/orlis_shoes.png') }}" alt="Giày cao gót"></div>
                <h4>{{ __('messages.high_heels') }}</h4>
            </div>
            <div class="category-item">
                <div class="category-img"><img src="{{ asset('images/orlis_perfume.png') }}" alt="Nước hoa"></div>
                <h4>{{ __('messages.perfume') }}</h4>
            </div>
            <div class="category-item">
                <div class="category-img"><img src="{{ asset('images/orlis_scarf.png') }}" alt="Khăn lụa"></div>
                <h4>{{ __('messages.silk_scarf') }}</h4>
            </div>
        @endif
    </section>

    <!-- Info Section -->
    <section class="info-section">
        <div class="info-text">
            <h2>{!! __('messages.final_touch') !!}</h2>
            <p>{{ __('messages.perfect_details') }}</p>
        </div>
        @if(isset($services) && $services->count() > 0)
            @foreach($services as $service)
            <div class="info-card">
                <img src="{{ Str::startsWith($service->image_path, 'http') ? $service->image_path : Storage::url($service->image_path) }}" alt="{{ $service->title }}">
                <div class="info-card-content">
                    <h4>{{ $service->title }}</h4>
                    <a href="{{ $service->link_url ?? '#' }}">{{ __('messages.see_more') }}</a>
                </div>
            </div>
            @endforeach
        @endif
    </section>

    <!-- Magazine / Recent Posts Section -->
    @if(isset($recentPosts) && $recentPosts->count() > 0)
    <section class="home-magazine" style="padding: 60px 40px; background: #fff;">
        <div style="text-align: center; margin-bottom: 40px;">
            <h2 style="font-family: var(--font-serif); font-size: 32px; font-weight: 400; letter-spacing: 2px;">TẠP CHÍ ORLIS</h2>
            <p style="font-family: var(--font-sans); color: #666; text-transform: uppercase; letter-spacing: 2px; font-size: 13px; margin-top: 10px;">{{ __('messages.news_events') }}</p>
        </div>
        <div class="magazine-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px;">
            @foreach($recentPosts as $post)
            <div class="magazine-card" style="display: flex; flex-direction: column;">
                <a href="{{ route('magazine.show', $post->slug) }}" style="display: block; width: 100%; aspect-ratio: 4/3; overflow: hidden; margin-bottom: 20px;">
                    <img src="{{ $post->thumbnail ? (Str::startsWith($post->thumbnail, 'http') ? $post->thumbnail : Storage::url($post->thumbnail)) : asset('images/orlis_bag.png') }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                </a>
                <div class="magazine-meta" style="font-size: 11px; color: #888; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px;">
                    {{ $post->created_at->format('d/m/Y') }} &mdash; {{ strtoupper($post->department) }}
                </div>
                <a href="{{ route('magazine.show', $post->slug) }}" style="text-decoration: none; color: #111;">
                    <h4 style="font-family: var(--font-serif); font-size: 20px; font-weight: 400; margin-bottom: 15px; line-height: 1.4;">{{ $post->title }}</h4>
                </a>
                <p style="font-family: var(--font-sans); font-size: 14px; color: #555; line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                    {{ $post->excerpt }}
                </p>
            </div>
            @endforeach
        </div>
        <div style="text-align: center; margin-top: 50px;">
            <a href="{{ route('magazine.index') }}" style="display: inline-block; padding: 12px 30px; border: 1px solid #111; color: #111; text-decoration: none; font-size: 13px; text-transform: uppercase; letter-spacing: 2px; transition: all 0.3s ease;" onmouseover="this.style.background='#111'; this.style.color='#fff';" onmouseout="this.style.background='transparent'; this.style.color='#111';">{{ __('messages.see_more') }}</a>
        </div>
    </section>
    @endif
@endsection
