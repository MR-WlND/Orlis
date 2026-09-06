@extends('layouts.client')

@section('title', 'Orlis - Danh mục Sản phẩm')

@section('content')
<div class="catalog-page">
    <div class="catalog-header-clean">
        <h1>{{ $category ? $category->translated_name : ($categoryBanner->title ?? __('messages.all_products')) }}</h1>
        <p class="catalog-desc">{{ isset($categoryBanner) && $categoryBanner->description ? $categoryBanner->description : __('messages.catalog_desc_default') }}</p>
        <p class="catalog-count">{{ $isParentCategory ? '' : $products->total() . ' ' . __('messages.items_count') }}</p>
    </div>

    @if($isParentCategory)
        @foreach($subcategoriesData as $data)
            @if($data['products']->count() > 0)
                <div class="subcategory-section">
                    <div class="subcategory-banner">
                        @if($data['banner'])
                            <img src="{{ Storage::url($data['banner']->image_path) }}" alt="{{ $data['category']->translated_name }}">
                        @else
                            <div class="subcategory-fallback-banner">
                                <h2>{{ $data['category']->translated_name }}</h2>
                            </div>
                        @endif
                    </div>

                    <div class="catalog-grid">
                        @foreach($data['products'] as $product)
                            <a href="{{ route('product', $product->id) }}" class="product-card">
                                <div class="product-img">
                                    @if($product->created_at > now()->subDays(30))
                                        <span class="product-tag-new">Mới</span>
                                    @endif
                                    <img src="{{ $product->thumbnail ? Storage::url($product->thumbnail) : asset('images/orlis_model_1.png') }}" alt="{{ $product->name }}">
                                </div>
                                <div class="product-info center-info">
                                    <h3>{{ $product->name }}</h3>
                                    <p>{{ number_format($product->price, 0, ',', '.') }} ₫</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    @else
        <div class="catalog-grid">
            @forelse($products as $product)
                <a href="{{ route('product', $product->id) }}" class="product-card">
                    <div class="product-img">
                        @if($product->created_at > now()->subDays(30))
                            <span class="product-tag-new">{{ __('messages.new_tag') }}</span>
                        @endif
                        <img src="{{ $product->thumbnail ? Storage::url($product->thumbnail) : asset('images/orlis_model_1.png') }}" alt="{{ $product->name }}">
                    </div>
                    <div class="product-info center-info">
                        <h3>{{ $product->name }}</h3>
                        <p>{{ number_format($product->price, 0, ',', '.') }} ₫</p>
                    </div>
                </a>
            @empty
                <p style="text-align: center; grid-column: span 4; font-family: var(--font-sans); color: #666; margin: 40px 0;">Không có sản phẩm nào trong danh mục này.</p>
            @endforelse
        </div>
        
        @if($products->hasPages())
            <div class="pagination-wrapper" style="margin-top: 50px; display: flex; justify-content: center;">
                {{ $products->links() }}
            </div>
        @endif
    @endif

    <!-- Floating Filter Button -->
    <button class="floating-filter-btn" onclick="toggleFilter()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" width="16"><path d="M4 6h16M10 12h10M14 18h6"/></svg>
        {{ __('messages.filter_sort') }}
    </button>

    <!-- Filter Offcanvas -->
    <div class="filter-overlay" id="filterOverlay" onclick="toggleFilter()"></div>
    <div class="filter-drawer" id="filterDrawer">
        <form action="{{ route('catalog', $slug) }}" method="GET" style="height: 100%; display: flex; flex-direction: column;">
            <div class="filter-header">
                <h4>{{ __('messages.filter_search') }}</h4>
                <svg onclick="toggleFilter()" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="cursor: pointer;"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </div>
            <div class="filter-body" style="flex: 1; overflow-y: auto;">
                <div class="filter-group">
                    <h5>{{ __('messages.search_title') }}</h5>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.search_placeholder') }}" style="width: 100%; padding: 10px; border: 1px solid #ccc;">
                </div>
                
                <div class="filter-group" style="margin-top: 20px;">
                    <h5>{{ __('messages.price_title') }}</h5>
                    <div style="display: flex; gap: 10px;">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="{{ __('messages.min_price') }}" style="flex: 1; padding: 10px; border: 1px solid #ccc; width: 100%;">
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="{{ __('messages.max_price') }}" style="flex: 1; padding: 10px; border: 1px solid #ccc; width: 100%;">
                    </div>
                </div>

                <div class="filter-group" style="margin-top: 20px;">
                    <h5>{{ __('messages.sort_title') }}</h5>
                    <select name="sort" style="width: 100%; padding: 10px; border: 1px solid #ccc;">
                        <option value="">{{ __('messages.default') }}</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('messages.sort_newest') }}</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>{{ __('messages.sort_price_asc') }}</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>{{ __('messages.sort_price_desc') }}</option>
                    </select>
                </div>
            </div>
            <div class="filter-footer">
                <a href="{{ route('catalog', $slug) }}" class="btn-clear" style="text-align: center; display: inline-block; text-decoration: none;">{{ __('messages.clear_filter') }}</a>
                <button type="submit" class="btn-apply">{{ __('messages.apply') }}</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleFilter() {
        document.getElementById('filterDrawer').classList.toggle('active');
        document.getElementById('filterOverlay').classList.toggle('active');
    }
</script>
@endsection
