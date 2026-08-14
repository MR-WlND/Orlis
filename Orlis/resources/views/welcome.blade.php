<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orlis - Thương Hiệu Thời Trang Cao Cấp</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Alata&family=Castoro:ital,wght@0,400;1,400&family=Charis+SIL:ital,wght@0,400;0,700;1,400;1,700&display=swap');

        :root {
            --primary: #1a1a1a;
            --bg-light: #fafafa;
            --text-dark: #333;
            --text-light: #fff;
            --border: #eaeaea;
            --font-serif: 'Charis SIL', serif;
            --font-sans: 'Alata', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-sans);
            color: var(--text-dark);
            background-color: var(--text-light);
            line-height: 1.6;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* Header */
        header {
            position: fixed;
            top: 0; left: 0; right: 0;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
            transition: all 0.3s;
            color: #fff;
        }
        header.header-light {
            background: #fff;
            color: #333;
            box-shadow: 0 1px 5px rgba(0,0,0,0.1);
        }
        .menu-icon {
            cursor: pointer;
        }
        .menu-icon svg {
            width: 24px;
            height: 24px;
            stroke: currentColor;
            transition: stroke 0.3s;
        }
        .logo {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            font-size: 28px;
            font-weight: 400;
            letter-spacing: 2px;
            font-family: 'Castoro', serif;
        }
        .action-icons {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        .action-icons a {
            color: inherit;
            display: flex;
            align-items: center;
        }
        .action-icons svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            cursor: pointer;
            transition: stroke 0.3s;
        }
        .search-container {
            display: flex;
            align-items: center;
        }
        .search-input {
            width: 0;
            opacity: 0;
            border: none;
            border-bottom: 1px solid currentColor;
            background: transparent;
            color: inherit;
            font-size: 13px;
            padding: 2px 0;
            outline: none;
            transition: width 0.3s, opacity 0.3s, margin 0.3s;
            margin-right: 0;
            font-family: inherit;
        }
        .search-input::placeholder {
            color: currentColor;
            opacity: 0.6;
        }
        .search-container.active .search-input {
            width: 150px;
            opacity: 1;
            margin-right: 10px;
        }

        /* Hero Section */
        .hero {
            position: relative;
            height: 100vh;
            background-image: url('https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: var(--text-light);
        }

        .hero::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.3);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            padding: 0 20px;
        }

        .hero-content h1 {
            font-family: var(--font-serif);
            font-size: 48px;
            font-weight: 400;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .hero-content p {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 30px;
        }

        /* Double Banner */
        .double-banner {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            padding: 40px;
        }

        .banner-item {
            position: relative;
            height: 700px;
            overflow: hidden;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding-bottom: 40px;
        }

        .banner-item img {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            object-fit: cover;
            z-index: 1;
            transition: transform 0.5s;
        }
        
        .banner-item:hover img {
            transform: scale(1.05);
        }

        .banner-item h3 {
            position: relative;
            z-index: 2;
            color: var(--text-light);
            font-weight: 500;
            letter-spacing: 1px;
            font-size: 18px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }

        /* Middle Banner */
        .mid-banner {
            position: relative;
            height: 600px;
            background-image: url('https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding-bottom: 60px;
            color: var(--text-light);
            text-align: center;
        }
        
        .mid-banner::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.2);
            z-index: 1;
        }

        .mid-banner-content {
            position: relative;
            z-index: 2;
        }

        .mid-banner h2 {
            font-family: var(--font-serif);
            font-size: 40px;
            font-style: italic;
            margin-bottom: 15px;
        }

        /* Categories Grid */
        .categories {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            padding: 40px;
            gap: 20px;
        }

        .category-item {
            text-align: center;
        }

        .category-img {
            width: 100%;
            aspect-ratio: 3/4;
            overflow: hidden;
            background-color: var(--bg-light);
            margin-bottom: 15px;
        }

        .category-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .category-img:hover img {
            transform: scale(1.05);
        }

        .category-item h4 {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-dark);
        }

        /* Info Section */
        .info-section {
            padding: 60px 40px;
            display: grid;
            grid-template-columns: 1fr 3fr;
            gap: 40px;
            background-color: var(--bg-light);
        }

        .info-text h2 {
            font-family: var(--font-serif);
            font-size: 32px;
            line-height: 1.3;
            margin-bottom: 20px;
        }

        .info-text p {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .info-card {
            position: relative;
            height: 400px;
            overflow: hidden;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding-bottom: 30px;
        }

        .info-card img {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            object-fit: cover;
            z-index: 1;
        }
        
        .info-card::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
            z-index: 2;
        }

        .info-card h4 {
            position: relative;
            z-index: 3;
            color: var(--text-light);
            font-size: 18px;
            font-weight: 500;
        }
        
        .info-card a {
            position: relative;
            z-index: 3;
            color: var(--text-light);
            font-size: 12px;
            text-decoration: underline;
            margin-top: 5px;
            display: block;
            text-align: center;
        }
        
        .info-card-content {
            position: relative;
            z-index: 3;
            text-align: center;
        }

        /* Footer */
        footer {
            padding: 60px 40px 20px;
            border-top: 1px solid var(--border);
        }

        .footer-top {
            margin-bottom: 40px;
            max-width: 400px;
        }

        .footer-top h4 {
            font-family: var(--font-serif);
            font-size: 18px;
            margin-bottom: 15px;
        }

        .subscribe-form {
            display: flex;
            gap: 10px;
        }

        .subscribe-form input {
            flex: 1;
            padding: 12px;
            border: 1px solid var(--border);
            outline: none;
        }

        .subscribe-form button {
            padding: 12px 24px;
            background: var(--text-dark);
            color: var(--text-light);
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        .footer-links {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            margin-bottom: 60px;
        }

        .link-column h5 {
            font-size: 14px;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .link-column ul {
            list-style: none;
        }

        .link-column ul li {
            margin-bottom: 10px;
        }

        .link-column ul li a {
            font-size: 13px;
            color: #666;
        }

        .link-column ul li a:hover {
            color: var(--text-dark);
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
            border-top: 1px solid var(--border);
            font-size: 12px;
            color: #999;
        }
        
        .social-links {
            display: flex;
            gap: 15px;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .double-banner {
                grid-template-columns: 1fr;
            }
            .categories {
                grid-template-columns: repeat(2, 1fr);
            }
            .info-section {
                grid-template-columns: 1fr;
            }
            .footer-links {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 32px;
            }
            .info-grid {
                grid-template-columns: 1fr;
            }
            .footer-links {
                grid-template-columns: 1fr;
            }
            .drawer {
                width: 300px;
            }
        }
        
        /* Drawer Menu */
        .drawer-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.4);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }
        .drawer-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        .drawer {
            position: fixed;
            top: 20px; 
            left: -400px; /* Ẩn đi */
            width: 360px;
            height: calc(100vh - 40px); /* Cách trên dưới 20px */
            background: #f5f5f5;
            z-index: 1000;
            transition: left 0.3s ease-in-out, opacity 0.3s;
            display: flex;
            flex-direction: column;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        }
        .drawer.active {
            left: 20px; /* Cách mép trái 20px */
        }
        .drawer-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
            cursor: pointer;
            font-size: 14px;
            color: #333;
        }
        .drawer-header svg {
            width: 16px;
            height: 16px;
            fill: #333;
        }
        .drawer-content {
            flex: 1;
            overflow-y: auto;
            padding: 20px 0;
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
        .drawer-content::-webkit-scrollbar {
            display: none;
        }
        .menu-list {
            list-style: none;
            display: none;
        }
        .menu-list.active {
            display: block;
        }
        .menu-list li {
            padding: 12px 0;
            font-size: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
        }
        .menu-list li:hover {
            color: #666;
        }
        .menu-list li svg {
            width: 14px;
            height: 14px;
        }
        .drawer-footer {
            margin-top: auto;
            padding-top: 20px;
        }
        .tab-wrapper {
            display: flex;
            background: #a1a1a1;
            padding: 4px;
            border-radius: 6px;
        }
        .tab-btn {
            flex: 1;
            padding: 10px;
            text-align: center;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            color: #fff;
            background: transparent;
            border-radius: 4px;
            border: none;
            transition: all 0.2s;
        }
        .tab-btn.active {
            background: #f5f5f5;
            color: #333;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header id="mainHeader">
        <div class="menu-icon" onclick="toggleDrawer()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 8h16M4 16h16"/></svg>
        </div>
        <div class="logo">Orlis</div>
        <div class="action-icons">
            <div class="search-container" id="searchContainer">
                <input type="text" class="search-input" placeholder="Tìm kiếm sản phẩm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" onclick="toggleSearch()"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35"/></svg>
            </div>
            <a href="{{ route('cart') }}" title="Giỏ hàng">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 01-8 0"/></svg>
            </a>
            <a href="#" onclick="toggleLoginModal(event)" title="Đăng nhập">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </a>
        </div>
    </header>

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

    <!-- Footer -->
    <footer>
        <div class="footer-top">
            <h4>Đăng ký để nhận thông tin mới nhất về Orlis</h4>
            <div class="subscribe-form">
                <input type="email" placeholder="Email">
                <button>Đăng ký</button>
            </div>
        </div>
        
        <div class="footer-links">
            <div class="link-column">
                <h5>Cửa Hàng Orlis</h5>
                <ul>
                    <li><a href="#">Câu chuyện thương hiệu</a></li>
                    <li><a href="#">Hệ thống cửa hàng</a></li>
                    <li><a href="#">Tuyển dụng</a></li>
                </ul>
            </div>
            <div class="link-column">
                <h5>Dịch Vụ Khách Hàng</h5>
                <ul>
                    <li><a href="#">Chăm sóc khách hàng</a></li>
                    <li><a href="#">Hướng dẫn chọn size</a></li>
                    <li><a href="#">Chính sách vận chuyển</a></li>
                </ul>
            </div>
            <div class="link-column">
                <h5>Quy Tắc Ứng Xử</h5>
                <ul>
                    <li><a href="#">Cam kết chất lượng</a></li>
                    <li><a href="#">Trách nhiệm xã hội</a></li>
                </ul>
            </div>
            <div class="link-column">
                <h5>Thuật Ngữ Pháp Lý</h5>
                <ul>
                    <li><a href="#">Chính sách bảo mật</a></li>
                    <li><a href="#">Điều khoản sử dụng</a></li>
                    <li><a href="#">Chính sách thanh toán</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="social-links">
                <span>Theo dõi chúng tôi:</span>
                <a href="#">Tiktok</a>
                <a href="#">Facebook</a>
                <a href="#">Instagram</a>
                <a href="#">Pinterest</a>
            </div>
            <div class="logo" style="font-size: 24px; color: var(--primary);">Orlis</div>
            <div>© 2026 Copyright Orlis</div>
        </div>
    </footer>

    <!-- Drawer Menu -->
    <div class="drawer-overlay" id="drawerOverlay" onclick="toggleDrawer()"></div>
    <div class="drawer" id="sideDrawer">
        <div class="drawer-header" onclick="toggleDrawer()">
            <svg viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
            Close
        </div>
        <div class="drawer-content">
            <ul class="menu-list active" id="menu-fashion">
                <li>Quà tặng <svg viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg></li>
                <li>Có gì mới? <svg viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg></li>
                <li>Thời trang nam <svg viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg></li>
                <li>Thời trang nữ <svg viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg></li>
                <li>Túi <svg viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg></li>
                <li>Trang sức & Đồng hồ <svg viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg></li>
                <li>Trẻ em & Em bé <svg viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg></li>
                <li>Thời trang cao cấp <svg viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg></li>
                <li style="margin-top: 40px;">Liên hệ</li>
                <li><a href="{{ route('cart') }}">Giỏ hàng</a></li>
            </ul>
            <ul class="menu-list" id="menu-beauty">
                <li>Có gì mới?</li>
                <li>Nước hoa</li>
                <li>Trang điểm</li>
                <li>Chăm sóc da</li>
            </ul>
        </div>
        <div class="drawer-footer">
            <div class="tab-wrapper">
                <div class="tab-btn active" id="tab-fashion" onclick="switchTab('fashion')">Thời trang & phụ kiện</div>
                <div class="tab-btn" id="tab-beauty" onclick="switchTab('beauty')">Nước hoa & Làm đẹp</div>
            </div>
        </div>
    </div>

    <script>
        function toggleDrawer() {
            document.getElementById('drawerOverlay').classList.toggle('active');
            document.getElementById('sideDrawer').classList.toggle('active');
        }

        function switchTab(tab) {
            if (tab === 'fashion') {
                document.getElementById('tab-fashion').classList.add('active');
                document.getElementById('tab-beauty').classList.remove('active');
                document.getElementById('menu-fashion').classList.add('active');
                document.getElementById('menu-beauty').classList.remove('active');
            } else {
                document.getElementById('tab-beauty').classList.add('active');
                document.getElementById('tab-fashion').classList.remove('active');
                document.getElementById('menu-beauty').classList.add('active');
                document.getElementById('menu-fashion').classList.remove('active');
            }
        }

        function toggleSearch() {
            var container = document.getElementById('searchContainer');
            container.classList.toggle('active');
            if (container.classList.contains('active')) {
                setTimeout(function() {
                    container.querySelector('.search-input').focus();
                }, 100);
            }
        }

        // Close search when clicking outside and input is empty
        document.addEventListener('click', function(event) {
            var container = document.getElementById('searchContainer');
            var input = container.querySelector('.search-input');
            if (container.classList.contains('active') && !container.contains(event.target)) {
                if (input.value.trim() === '') {
                    container.classList.remove('active');
                }
            }
        });

        // Handle scroll effect for header
        window.addEventListener('scroll', function() {
            var header = document.getElementById('mainHeader');
            if (window.scrollY > 50) {
                header.classList.add('header-light');
            } else {
                header.classList.remove('header-light');
            }
        });
    </script>

    @include('components.login-modal')
</body>
</html>
