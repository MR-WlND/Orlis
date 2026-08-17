@extends('layouts.client')

@section('title', 'Orlis - Danh mục Sản phẩm')

@section('content')
<div class="catalog-page">
    <div class="catalog-header">
        <h1>Bộ Sưu Tập</h1>
        <div class="catalog-controls">
            <button class="filter-btn" onclick="toggleFilter()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 6h16M10 12h10M14 18h6"/></svg>
                Bộ Lọc
            </button>
            <div class="sort-dropdown">Sắp xếp: Mới nhất <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="16"><path d="M6 9l6 6 6-6"/></svg></div>
        </div>
    </div>

    <div class="catalog-grid">
        <a href="{{ route('product', 1) }}" class="product-card">
            <div class="product-img">
                <img src="{{ asset('images/orlis_model_1.png') }}" alt="Product">
            </div>
            <div class="product-info">
                <h3>Váy Lụa Đen Tuyền</h3>
                <p>25,000,000 ₫</p>
            </div>
        </a>
        <a href="{{ route('product', 2) }}" class="product-card">
            <div class="product-img">
                <img src="{{ asset('images/orlis_model_2.png') }}" alt="Product">
            </div>
            <div class="product-info">
                <h3>Suit Nam Beige</h3>
                <p>45,000,000 ₫</p>
            </div>
        </a>
        <a href="{{ route('product', 3) }}" class="product-card">
            <div class="product-img">
                <img src="{{ asset('images/orlis_bag.png') }}" alt="Product">
            </div>
            <div class="product-info">
                <h3>Túi Xách Da Cá Sấu</h3>
                <p>120,000,000 ₫</p>
            </div>
        </a>
        <a href="{{ route('product', 4) }}" class="product-card">
            <div class="product-img">
                <img src="{{ asset('images/orlis_shoes.png') }}" alt="Product">
            </div>
            <div class="product-info">
                <h3>Giày Cao Gót Nhung</h3>
                <p>18,500,000 ₫</p>
            </div>
        </a>
        <a href="{{ route('product', 5) }}" class="product-card">
            <div class="product-img">
                <img src="{{ asset('images/orlis_perfume.png') }}" alt="Product">
            </div>
            <div class="product-info">
                <h3>Nước Hoa L'Orlis</h3>
                <p>8,500,000 ₫</p>
            </div>
        </a>
        <a href="{{ route('product', 6) }}" class="product-card">
            <div class="product-img">
                <img src="{{ asset('images/orlis_scarf.png') }}" alt="Product">
            </div>
            <div class="product-info">
                <h3>Khăn Lụa Equestrian</h3>
                <p>12,000,000 ₫</p>
            </div>
        </a>
    </div>

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
