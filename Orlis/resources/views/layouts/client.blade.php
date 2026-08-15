<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Orlis - Thương Hiệu Thời Trang Cao Cấp')</title>
    @vite(['resources/css/client.css', 'resources/js/client.js'])
</head>
<body>
    @yield('styles')

    <!-- Header -->
    <header id="mainHeader" class="{{ request()->is('/') ? '' : 'header-light' }}">
        <div class="menu-icon" onclick="toggleDrawer()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 8h16M4 16h16"/></svg>
        </div>
        <a href="/" class="logo">Orlis</a>
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

    <main>
        @yield('content')
    </main>

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
            <a href="/" class="logo" style="font-size: 24px; color: var(--primary);">Orlis</a>
            <div>© 2026 Copyright Orlis</div>
        </div>
    </footer>

    <!-- Drawer Menu -->
    <div class="drawer-overlay" id="drawerOverlay" onclick="toggleDrawer()"></div>
    <div class="drawer" id="sideDrawer">
        <div class="drawer-viewport">
            
            <!-- MAIN MENU PANEL -->
            <div class="drawer-panel active" id="panel-main">
                <div class="drawer-header" onclick="toggleDrawer()">
                    <svg viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    Close
                </div>
                <div class="drawer-content">
                    <ul class="menu-list active" id="menu-fashion">
                        <li onclick="openMegaMenu('gifts')">Quà tặng <svg viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg></li>
                        <li onclick="openMegaMenu('new')">Có gì mới? <svg viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg></li>
                        <li>Thời trang nam <svg viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg></li>
                        <li>Thời trang nữ <svg viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg></li>
                        <li>Túi <svg viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg></li>
                        <li>Trang sức & Đồng hồ <svg viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg></li>
                        <li>Trẻ em & Em bé <svg viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg></li>
                        <li>Thời trang cao cấp <svg viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg></li>
                        <li style="margin-top: 40px;">Liên hệ</li>
                        <li><a href="{{ route('cart') }}" style="text-decoration: none; color: inherit; display: block; width: 100%;">Giỏ hàng</a></li>
                    </ul>
                    <ul class="menu-list" id="menu-beauty">
                        <li>Có gì mới?</li>
                        <li><a href="{{ route('perfume') }}" style="text-decoration: none; color: inherit; display: block; width: 100%;">Nước hoa</a></li>
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

            <!-- MEGA MENU: QUÀ TẶNG -->
            <div class="drawer-panel mega-panel" id="panel-gifts">
                <div class="mega-sidebar">
                    <div class="drawer-header" onclick="toggleDrawer()">
                        <svg viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                        Close
                    </div>
                    <div class="mega-title" onclick="closeMegaMenu()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M15 18l-6-6 6-6"/></svg>
                        Quà tặng
                    </div>
                    <ul class="mega-list menu-list active">
                        <li>Khám phá quà tặng</li>
                        <li>Quà tặng cho nữ</li>
                        <li>Quà tặng cho nam</li>
                        <li>Quà tặng cho bé</li>
                        <li>Quà tặng cho gia đình</li>
                        <li>Những món quà nhỏ sang trọng</li>
                    </ul>
                </div>
                <div class="mega-content">
                    <div class="mega-grid">
                        <div class="mega-card">
                            <img src="https://images.unsplash.com/photo-1549465220-1a8b9238cd48?auto=format&fit=crop&w=400&q=80" alt="Gifts for her">
                            <span class="mega-label">Quà tặng cho nữ</span>
                        </div>
                        <div class="mega-card">
                            <img src="https://images.unsplash.com/photo-1594911772125-07fc7a2d8d9f?auto=format&fit=crop&w=400&q=80" alt="Gifts for him">
                            <span class="mega-label">Quà tặng cho nam</span>
                        </div>
                        <div class="mega-card">
                            <img src="https://images.unsplash.com/photo-1519689680058-324335c77eba?auto=format&fit=crop&w=400&q=80" alt="Gifts for kids">
                            <span class="mega-label">Quà tặng cho trẻ em</span>
                        </div>
                        <div class="mega-card">
                            <img src="https://images.unsplash.com/photo-1513201099705-a9746e1e201f?auto=format&fit=crop&w=400&q=80" alt="Gifts for family">
                            <span class="mega-label">Quà tặng cho gia đình</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MEGA MENU: CÓ GÌ MỚI? -->
            <div class="drawer-panel mega-panel" id="panel-new">
                <div class="mega-sidebar">
                    <div class="drawer-header" onclick="toggleDrawer()">
                        <svg viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                        Close
                    </div>
                    <div class="mega-title" onclick="closeMegaMenu()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M15 18l-6-6 6-6"/></svg>
                        Có gì mới?
                    </div>
                    <ul class="mega-list menu-list active">
                        <li>Dành cho nữ</li>
                        <li>Dành cho nam</li>
                        <li>Dành cho trẻ em</li>
                        <li>Dành cho gia đình</li>
                        <li>Trang sức</li>
                        <li>Đồng hồ</li>
                    </ul>
                </div>
                <div class="mega-content">
                    <div class="mega-grid">
                        <div class="mega-card">
                            <img src="https://images.unsplash.com/photo-1520608552192-3211516e87f3?auto=format&fit=crop&w=400&q=80" alt="New for her">
                            <span class="mega-label">Dành cho nữ</span>
                        </div>
                        <div class="mega-card">
                            <img src="https://images.unsplash.com/photo-1550246140-5119ae4790b8?auto=format&fit=crop&w=400&q=80" alt="New for him">
                            <span class="mega-label">Dành cho nam</span>
                        </div>
                        <div class="mega-card">
                            <img src="https://images.unsplash.com/photo-1522204523234-8729aa6e3d5f?auto=format&fit=crop&w=400&q=80" alt="New for kids">
                            <span class="mega-label">Dành cho trẻ em</span>
                        </div>
                        <div class="mega-card">
                            <img src="https://images.unsplash.com/photo-1528698827591-e19ccd7bc23d?auto=format&fit=crop&w=400&q=80" alt="New for family">
                            <span class="mega-label">Dành cho gia đình</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>



    @include('components.login-modal')
    @include('components.register-modal')
</body>
</html>
