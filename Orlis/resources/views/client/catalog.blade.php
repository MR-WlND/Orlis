@extends('layouts.client')

@section('title', 'Orlis - Danh mục Sản phẩm')

@section('content')
<div class="catalog-page">
    <div class="catalog-header-clean">
        <h1>{{ $category ? $category->name : ($categoryBanner->title ?? 'Tất cả sản phẩm') }}</h1>
        <p class="catalog-desc">{{ isset($categoryBanner) && $categoryBanner->description ? $categoryBanner->description : 'Khám phá các bộ sưu tập mới nhất, nơi kết hợp sự thanh lịch vượt thời gian và tinh thần hiện đại qua từng thiết kế.' }}</p>
        <p class="catalog-count">{{ $isParentCategory ? '' : $products->total() . ' mặt hàng' }}</p>
    </div>

    @if($isParentCategory)
        @foreach($subcategoriesData as $data)
            @if($data['products']->count() > 0)
                <div class="subcategory-section">
                    <div class="subcategory-banner">
                        @if($data['banner'])
                            <img src="{{ Storage::url($data['banner']->image_path) }}" alt="{{ $data['category']->name }}">
                        @else
                            <div class="subcategory-fallback-banner">
                                <h2>{{ $data['category']->name }}</h2>
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
                            <span class="product-tag-new">Mới</span>
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
        Lọc & Sắp xếp
    </button>

    <!-- Filter Offcanvas -->
    <div class="filter-overlay" id="filterOverlay" onclick="toggleFilter()"></div>
    <div class="filter-drawer" id="filterDrawer">
        <div class="filter-header">
            <h4>BỘ LỌC</h4>
            <svg onclick="toggleFilter()" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </div>
        <div class="filter-body">
            <div class="filter-group">
                <h5>DANH MỤC</h5>
                <ul>
                    <li><label><input type="checkbox"> Thời Trang Nữ</label></li>
                    <li><label><input type="checkbox"> Thời Trang Nam</label></li>
                    <li><label><input type="checkbox"> Đồ Da Nhỏ</label></li>
                    <li><label><input type="checkbox"> Nước Hoa</label></li>
                </ul>
            </div>
            <div class="filter-group">
                <h5>MÀU SẮC</h5>
                <div class="color-options">
                    <span style="background: #111;"></span>
                    <span style="background: #e6dac3;"></span>
                    <span style="background: #731e1e;"></span>
                    <span style="background: #20415a;"></span>
                </div>
            </div>
        </div>
        <div class="filter-footer">
            <button class="btn-clear">XÓA BỘ LỌC</button>
            <button class="btn-apply">ÁP DỤNG</button>
        </div>
    </div>
</div>

<script>
    function toggleFilter() {
        document.getElementById('filterDrawer').classList.toggle('active');
        document.getElementById('filterOverlay').classList.toggle('active');
    }
</script>
@endsection
