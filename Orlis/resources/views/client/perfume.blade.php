@extends('layouts.client')

@section('title', 'Nước Hoa - Orlis')

@section('content')
    <div class="perfume-page">
        
        <!-- Hero Section -->
        <section class="perfume-hero-grid">
            <div class="perfume-hero-item">
                <video autoplay loop muted playsinline poster="https://images.unsplash.com/photo-1523485773295-ff8e244b0235?auto=format&fit=crop&w=600&q=80">
                    <source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4">
                </video>
            </div>
            <div class="perfume-hero-item">
                <video autoplay loop muted playsinline poster="https://images.unsplash.com/photo-1587402092301-725e37c70fd8?auto=format&fit=crop&w=600&q=80">
                    <source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4">
                </video>
            </div>
            <div class="perfume-hero-item">
                <video autoplay loop muted playsinline poster="https://images.unsplash.com/photo-1594035987173-16a5d9333919?auto=format&fit=crop&w=600&q=80">
                    <source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4">
                </video>
            </div>
        </section>

        <!-- Có thể bạn sẽ thích -->
        <section class="perfume-products-section">
            <h2 class="perfume-section-title">Có thể bạn sẽ thích</h2>
            <div class="perfume-product-grid">
                <!-- Product 1 -->
                <a href="{{ route('product', 1) }}" class="perfume-product-card" style="text-decoration: none; color: inherit; display: block;">
                    <div class="product-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=400&q=80" alt="Perfume 1">
                    </div>
                    <div class="product-info">
                        <span class="product-category">Túi xách</span>
                        <h3 class="product-name">Black Puffy Macrocannage Calfskin</h3>
                        <p class="product-price">$3,900</p>
                    </div>
                </a>
                <!-- Product 2 -->
                <a href="{{ route('product', 1) }}" class="perfume-product-card" style="text-decoration: none; color: inherit; display: block;">
                    <div class="product-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=400&q=80" alt="Perfume 2">
                    </div>
                    <div class="product-info">
                        <span class="product-category">Túi xách</span>
                        <h3 class="product-name">Black Puffy Macrocannage Calfskin</h3>
                        <p class="product-price">$3,900</p>
                    </div>
                </a>
                <!-- Product 3 -->
                <a href="{{ route('product', 1) }}" class="perfume-product-card" style="text-decoration: none; color: inherit; display: block;">
                    <div class="product-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=400&q=80" alt="Perfume 3">
                    </div>
                    <div class="product-info">
                        <span class="product-category">Túi xách</span>
                        <h3 class="product-name">Black Puffy Macrocannage Calfskin</h3>
                        <p class="product-price">$3,900</p>
                    </div>
                </a>
                <!-- Product 4 -->
                <a href="{{ route('product', 1) }}" class="perfume-product-card" style="text-decoration: none; color: inherit; display: block;">
                    <div class="product-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=400&q=80" alt="Perfume 4">
                    </div>
                    <div class="product-info">
                        <span class="product-category">Túi xách</span>
                        <h3 class="product-name">Black Puffy Macrocannage Calfskin</h3>
                        <p class="product-price">$3,900</p>
                    </div>
                </a>
            </div>
        </section>

        <!-- Sản phẩm bán chạy -->
        <section class="perfume-products-section">
            <h2 class="perfume-section-title">Sản phẩm bán chạy</h2>
            <div class="perfume-product-grid">
                <!-- Product 1 -->
                <a href="{{ route('product', 1) }}" class="perfume-product-card" style="text-decoration: none; color: inherit; display: block;">
                    <div class="product-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1588405748880-12d1d2a59f75?auto=format&fit=crop&w=400&q=80" alt="Perfume 1">
                    </div>
                    <div class="product-info">
                        <span class="product-category">Túi xách</span>
                        <h3 class="product-name">Black Puffy Macrocannage Calfskin</h3>
                        <p class="product-price">$3,900</p>
                    </div>
                </a>
                <!-- Product 2 -->
                <a href="{{ route('product', 1) }}" class="perfume-product-card" style="text-decoration: none; color: inherit; display: block;">
                    <div class="product-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1588405748880-12d1d2a59f75?auto=format&fit=crop&w=400&q=80" alt="Perfume 2">
                    </div>
                    <div class="product-info">
                        <span class="product-category">Túi xách</span>
                        <h3 class="product-name">Black Puffy Macrocannage Calfskin</h3>
                        <p class="product-price">$3,900</p>
                    </div>
                </a>
                <!-- Product 3 -->
                <a href="{{ route('product', 1) }}" class="perfume-product-card" style="text-decoration: none; color: inherit; display: block;">
                    <div class="product-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1588405748880-12d1d2a59f75?auto=format&fit=crop&w=400&q=80" alt="Perfume 3">
                    </div>
                    <div class="product-info">
                        <span class="product-category">Túi xách</span>
                        <h3 class="product-name">Black Puffy Macrocannage Calfskin</h3>
                        <p class="product-price">$3,900</p>
                    </div>
                </a>
                <!-- Product 4 -->
                <a href="{{ route('product', 1) }}" class="perfume-product-card" style="text-decoration: none; color: inherit; display: block;">
                    <div class="product-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1588405748880-12d1d2a59f75?auto=format&fit=crop&w=400&q=80" alt="Perfume 4">
                    </div>
                    <div class="product-info">
                        <span class="product-category">Túi xách</span>
                        <h3 class="product-name">Black Puffy Macrocannage Calfskin</h3>
                        <p class="product-price">$3,900</p>
                    </div>
                </a>
            </div>
        </section>

        <!-- Bottom Grid Section -->
        <section class="perfume-bottom-grid">
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
        </section>
        
    </div>
@endsection
