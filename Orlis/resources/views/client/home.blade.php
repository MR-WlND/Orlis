@extends('layouts.client')

@section('title', 'Orlis - Thương Hiệu Thời Trang Cao Cấp')

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <p>Bộ Sưu Tập Mùa Thu 2026</p>
            <h1>Một vở kịch của những sự tương phản</h1>
            <p style="font-size: 12px;">Khám phá bộ sưu tập</p>
        </div>
    </section>

    <!-- Double Banner -->
    <section class="double-banner">
        <div class="banner-item">
            <img src="https://images.unsplash.com/photo-1549298916-b41d501d3772?auto=format&fit=crop&w=800&q=80" alt="Giày nam">
            <h3>Giày nam</h3>
        </div>
        <div class="banner-item">
            <img src="https://images.unsplash.com/photo-1617137968427-85924c800a22?auto=format&fit=crop&w=800&q=80" alt="Thời trang nam">
            <h3>Thời trang nam</h3>
        </div>
    </section>

    <!-- Middle Banner -->
    <section class="mid-banner">
        <div class="mid-banner-content">
            <h2>Di sản được tái hiện</h2>
            <p style="font-size: 13px; text-transform: uppercase; letter-spacing: 1px;">Khám phá câu chuyện</p>
        </div>
    </section>

    <!-- Categories -->
    <section class="categories">
        <div class="category-item">
            <div class="category-img">
                <img src="https://images.unsplash.com/photo-1559589689-577aabd1ce4c?auto=format&fit=crop&w=400&q=80" alt="Thắt lưng nữ">
            </div>
            <h4>Thắt lưng nữ</h4>
        </div>
        <div class="category-item">
            <div class="category-img">
                <img src="https://images.unsplash.com/photo-1543163521-1bf539c55dd2?auto=format&fit=crop&w=400&q=80" alt="Giày cao gót">
            </div>
            <h4>Giày cao gót</h4>
        </div>
        <div class="category-item">
            <div class="category-img">
                <img src="https://images.unsplash.com/photo-1584916201218-f4242ceb4809?auto=format&fit=crop&w=400&q=80" alt="Túi xách">
            </div>
            <h4>Túi xách</h4>
        </div>
        <div class="category-item">
            <div class="category-img">
                <img src="https://images.unsplash.com/photo-1606760227091-3dd870d97f1d?auto=format&fit=crop&w=400&q=80" alt="Khăn lụa">
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
        <div class="info-grid">
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
        </div>
    </section>
@endsection
