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
    <header id="mainHeader" class="{{ request()->is('/') ? '' : (request()->is('beauty') ? 'header-dark-text' : 'header-light') }}">
        <div class="menu-icon" onclick="toggleDrawer()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 8h16M4 16h16"/></svg>
        </div>
        <a href="{{ session('department') === 'beauty' ? url('/beauty') : url('/') }}" class="logo">Orlis</a>
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

    <main id="swup" class="transition-fade">
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
                    @foreach($globalCategories as $index => $level1)
                        @php 
                            $isActive = (session('department', 'fashion') === 'beauty' && str_contains($level1->name, 'Nước hoa')) || 
                                        (session('department', 'fashion') === 'fashion' && str_contains($level1->name, 'Thời trang'));
                        @endphp
                        <ul class="menu-list {{ $isActive ? 'active' : '' }}" id="menu-{{ $level1->slug }}">
                            @foreach($level1->children as $level2)
                                @if($level2->children->count() > 0)
                                    <li onclick="openMegaMenu('{{ $level2->slug }}')">
                                @else
                                    <li onclick="window.location='/catalog/{{ $level2->slug }}'">
                                @endif
                                    {{ $level2->name }} 
                                    @if($level2->children->count() > 0)
                                        <svg viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
                                    @endif
                                </li>
                            @endforeach
                            @if($index === 0)
                                <li style="margin-top: 40px;"><a href="#" style="text-decoration: none; color: inherit; display: block; width: 100%;">Liên hệ</a></li>
                                <li><a href="{{ route('cart') }}" style="text-decoration: none; color: inherit; display: block; width: 100%;">Giỏ hàng</a></li>
                            @endif
                        </ul>
                    @endforeach
                </div>
                <div class="drawer-footer">
                    <div class="tab-wrapper">
                        @foreach($globalCategories as $index => $level1)
                            @php 
                                $isActive = (session('department', 'fashion') === 'beauty' && str_contains($level1->name, 'Nước hoa')) || 
                                            (session('department', 'fashion') === 'fashion' && str_contains($level1->name, 'Thời trang'));
                            @endphp
                            <div class="tab-btn {{ $isActive ? 'active' : '' }}" id="tab-{{ $level1->slug }}" onclick="switchTab('{{ $level1->slug }}')">
                                {{ $level1->name }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- MEGA MENUS DYNAMIC -->
            @foreach($globalCategories as $level1)
                @foreach($level1->children as $level2)
                    @if($level2->children->count() > 0)
                    <div class="drawer-panel mega-panel" id="panel-{{ $level2->slug }}">
                        <div class="mega-sidebar">
                            <div class="drawer-header" onclick="toggleDrawer()">
                                <svg viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                                Close
                            </div>
                            <div class="mega-title" onclick="closeMegaMenu()">
                                <svg viewBox="0 0 24 24"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
                                {{ $level2->name }}
                            </div>
                            <ul class="mega-list menu-list active">
                                <li><a href="/catalog/{{ $level2->slug }}" style="text-decoration:none; color:inherit; display:block;">Khám phá {{ $level2->name }}</a></li>
                                @foreach($level2->children as $level3)
                                    <li><a href="/catalog/{{ $level3->slug }}" style="text-decoration:none; color:inherit; display:block;">{{ $level3->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="mega-content">
                            <div class="mega-grid">
                                @foreach($level2->children as $level3)
                                    <div class="mega-card" onclick="window.location='/catalog/{{ $level3->slug }}'" style="cursor: pointer;">
                                        <img src="{{ $level3->image ?? 'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $level3->name }}">
                                        <span class="mega-label">{{ $level3->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            @endforeach

        </div>
    </div>



    @include('components.login-modal')
    @include('components.register-modal')

    <script src="https://unpkg.com/swup@4"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (!window.swup) {
                window.swup = new Swup({
                    containers: ['#swup', '#sideDrawer', '#mainHeader'],
                    cache: false
                });
            }
        });
    </script>
</body>
</html>
