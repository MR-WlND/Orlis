<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bảng Điều Khiển') - Orlis</title>
    @vite(['resources/css/admin.css'])
    @yield('page-style')
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header" style="display: flex; flex-direction: column; align-items: flex-start; justify-content: center; padding: 25px 20px;">
            <div style="font-family: 'Playfair Display', Georgia, serif; font-size: 26px; color: #111; letter-spacing: 2px; line-height: 1;">ORLIS</div>
            <div style="font-family: 'Inter', sans-serif; font-size: 10px; color: #555; text-transform: uppercase; letter-spacing: 3px; margin-top: 8px;">Trang quản trị {{ auth('admin')->check() && auth('admin')->user()->role === 'manager' ? '• Manager' : '' }}{{ auth('admin')->check() && auth('admin')->user()->role === 'staff' ? '• Staff' : '' }}</div>
        </div>
        


        <div class="sidebar-menu">
            @php
                $role = auth('admin')->user() ? auth('admin')->user()->role : null;
            @endphp
            <ul class="menu-list">
                <!-- 1. Tổng quan -->
                <li class="menu-item">
                    @php
                        $dashboardUrl = match($role ?? 'admin') {
                            'staff' => '/staff',
                            'shipper' => '/shipper',
                            'warehouse_staff' => '/warehouse',
                            'supplier' => '/supplier',
                            default => '/admin'
                        };
                    @endphp
                    <a href="{{ $dashboardUrl }}" class="menu-link {{ request()->is(trim($dashboardUrl, '/')) ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                        TỔNG QUAN
                    </a>
                </li>

                <!-- 2. POS -->
                @if(in_array($role, ['admin', 'manager', 'staff']))
                <li class="menu-item">
                    <a href="{{ route('admin.pos.create') }}" class="menu-link {{ request()->routeIs('admin.pos.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                        TẠO ĐƠN (POS)
                    </a>
                </li>
                @endif

                <!-- 3. Đơn hàng -->
                @if(in_array($role, ['admin', 'manager', 'staff', 'warehouse_staff', 'shipper']))
                <li class="menu-item">
                    @php
                        $ordersRoute = $role === 'shipper' ? route('shipper.orders') : route('admin.orders.index');
                        $isOrdersActive = request()->routeIs('admin.orders.*') || request()->routeIs('shipper.orders.*');
                    @endphp
                    <a href="{{ $ordersRoute }}" class="menu-link {{ $isOrdersActive ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 01-8 0"></path></svg>
                        ĐƠN HÀNG
                    </a>
                </li>
                @endif

                <!-- 4. Sản phẩm -->
                @if(in_array($role, ['admin', 'manager']))
                <li class="menu-item">
                    <a href="{{ route('admin.products.index') }}" class="menu-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        SẢN PHẨM
                    </a>
                </li>
                @endif

                <!-- 5. Danh mục -->
                @if(in_array($role, ['admin', 'manager']))
                <li class="menu-item">
                    <a href="{{ route('admin.categories.index') }}" class="menu-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                        DANH MỤC
                    </a>
                </li>
                @endif

                <!-- 6. Kho hàng -->
                @if(in_array($role, ['admin', 'manager', 'warehouse_staff']))
                <li class="menu-item">
                    <a href="{{ route('admin.inventory.index') }}" class="menu-link {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                        KHO HÀNG
                    </a>
                </li>
                @endif

                <!-- 7. PO -->
                @if(in_array($role, ['admin', 'warehouse_staff']))
                <li class="menu-item">
                    <a href="{{ route('admin.purchase_orders.create') }}" class="menu-link {{ request()->routeIs('admin.purchase_orders.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        TẠO YÊU CẦU NHẬP HÀNG (PO)
                    </a>
                </li>
                @endif

                <!-- 8. Khách hàng -->
                @if(in_array($role, ['admin', 'manager', 'staff']))
                <li class="menu-item">
                    <a href="{{ route('admin.users.index') }}" class="menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        KHÁCH HÀNG
                    </a>
                </li>
                
                <!-- 9. Lịch hẹn -->
                <li class="menu-item">
                    <a href="{{ route('admin.appointments.index') }}" class="menu-link {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        LỊCH HẸN
                    </a>
                </li>
                
                <!-- 10. Tickets -->
                <li class="menu-item">
                    <a href="{{ route('admin.tickets.index') }}" class="menu-link {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                        HỖ TRỢ & CHAT
                    </a>
                </li>
                @endif

                <!-- 11. Banners -->
                @if(in_array($role, ['admin', 'manager', 'editor']))
                <li class="menu-item">
                    <a href="{{ route('admin.banners.index') }}" class="menu-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                        BANNERS
                    </a>
                </li>
                @endif

                <!-- 12. Tạp chí -->
                @if(in_array($role, ['admin', 'editor']))
                <li class="menu-item">
                    <a href="{{ route('admin.posts.index') }}" class="menu-link {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        TẠP CHÍ
                    </a>
                </li>
                @endif

                <!-- 13. Mã giảm giá & 14. Giao hàng -->
                @if(in_array($role, ['admin', 'manager']))
                <li class="menu-item">
                    <a href="{{ route('admin.coupons.index') }}" class="menu-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                        MÃ GIẢM GIÁ
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.shipping-methods.index') }}" class="menu-link {{ request()->routeIs('admin.shipping-methods.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                        GIAO HÀNG
                    </a>
                </li>
                @endif

                <!-- 15. Tài khoản -->
                @if(in_array($role, ['admin']))
                @php
                    $isAccountActive = request()->routeIs('admin.admins.*');
                @endphp
                <li class="menu-item">
                    <div class="menu-link {{ $isAccountActive ? 'active' : '' }}" onclick="toggleSubmenu('accountSubmenu', 'accountIcon')" style="cursor: pointer; justify-content: space-between;">
                        <div style="display: flex; align-items: center;">
                            <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            TÀI KHOẢN
                        </div>
                        <svg id="accountIcon" style="width: 14px; height: 14px; margin-right: 0; transition: transform 0.3s ease; transform: {{ $isAccountActive ? 'rotate(180deg)' : 'rotate(0deg)' }};" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </div>
                    <ul id="accountSubmenu" style="list-style: none; padding-left: 50px; margin-top: 5px; margin-bottom: 10px; overflow: hidden; transition: max-height 0.3s ease-in-out; max-height: {{ $isAccountActive ? '150px' : '0px' }};">
                        <li class="menu-item">
                            <a href="{{ route('admin.admins.index') }}" class="menu-link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}" style="padding: 8px 0; font-size: 10px; border-right: none; background: transparent; {{ request()->routeIs('admin.admins.*') ? 'color: var(--text-primary);' : '' }}">
                                NHÂN SỰ
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- 16. Cấu hình -->
                <li class="menu-item">
                    <a href="{{ route('admin.settings.index') }}" class="menu-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        CẤU HÌNH
                    </a>
                </li>
                @endif
            </ul>
        </div>
        
        <div class="sidebar-footer">
            <a href="#" class="footer-link">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                HỖ TRỢ
            </a>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0; width: 100%;">
                @csrf
                <button type="submit" class="footer-link" style="width: 100%; border: none; background: none; text-align: left; cursor: pointer; padding: 0;">
                    <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    ĐĂNG XUẤT
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Header -->
        <header class="header">
            <div class="search-bar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" placeholder="Tìm kiếm đơn hàng, sản phẩm...">
            </div>
            
            <div class="header-actions">
                <!-- Language Selector -->
                <div class="action-icon" style="position: relative;" onclick="document.getElementById('langDropdown').classList.toggle('show')">
                    <svg viewBox="0 0 24 24" style="width: 20px; height: 20px; stroke: #111; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; cursor: pointer;"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                    <span style="font-size: 12px; margin-left: 5px; font-weight: 600; text-transform: uppercase; cursor: pointer;">{{ session('locale', 'vi') }}</span>
                    
                    <div id="langDropdown" class="lang-dropdown">
                        <a href="{{ route('lang.switch', 'vi') }}">🇻🇳 Tiếng Việt</a>
                        <a href="{{ route('lang.switch', 'en') }}">🇬🇧 English</a>
                        <a href="{{ route('lang.switch', 'fr') }}">🇫🇷 Français</a>
                        <a href="{{ route('lang.switch', 'ja') }}">🇯🇵 日本語</a>
                    </div>
                </div>

                <style>
                    .lang-dropdown { display: none; position: absolute; top: 100%; right: 0; background: white; min-width: 120px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 4px; padding: 5px 0; z-index: 1000; margin-top: 10px; border: 1px solid #eee; }
                    .lang-dropdown.show { display: block; }
                    .lang-dropdown a { display: block; padding: 8px 15px; color: #333; text-decoration: none; font-size: 13px; }
                    .lang-dropdown a:hover { background: #f9f9f9; }
                </style>
                <script>
                    window.addEventListener('click', function(e) {
                        if (!e.target.closest('.action-icon')) {
                            const dropdowns = document.getElementsByClassName("lang-dropdown");
                            for (let i = 0; i < dropdowns.length; i++) {
                                dropdowns[i].classList.remove('show');
                            }
                        }
                    });
                </script>

                <!-- Bell notification -->
                <div class="action-icon">
                    <svg viewBox="0 0 24 24" style="width: 20px; height: 20px; stroke: #111; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                    <div style="position: absolute; top: 0px; right: 0px; background-color: #cda873; width: 8px; height: 8px; border-radius: 50%; border: 1.5px solid #fff; box-sizing: content-box;"></div>
                </div>

                <!-- Divider -->
                <div class="header-divider"></div>

                <!-- User info -->
                <div class="header-user">
                    @php
                        $currentUser = auth('admin')->user() ?? auth('web')->user();
                    @endphp
                    <div class="user-info">
                        <span class="user-name">{{ $currentUser ? $currentUser->name : 'Quản Trị Viên' }}</span>
                        <span class="user-location">{{ $currentUser ? (App\Models\Admin::ROLES[$currentUser->role] ?? App\Models\User::ROLES[$currentUser->role] ?? $currentUser->role) : 'Quản trị hệ thống' }}</span>
                    </div>
                    <div class="user-avatar-small">
                        @if($currentUser && $currentUser->avatar)
                            <img src="{{ Storage::url($currentUser->avatar) }}" alt="Avatar">
                        @else
                            <div class="user-avatar-initials">
                                {{ $currentUser ? strtoupper(substr($currentUser->name, 0, 2)) : 'AD' }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="content">
            @yield('content')
        </main>
    </div>

    <script>
        function toggleSubmenu(menuId, iconId) {
            const menu = document.getElementById(menuId);
            const icon = document.getElementById(iconId);
            if (menu.style.maxHeight === '0px' || menu.style.maxHeight === '') {
                menu.style.maxHeight = '150px';
                icon.style.transform = 'rotate(180deg)';
            } else {
                menu.style.maxHeight = '0px';
                icon.style.transform = 'rotate(0deg)';
            }
        }
    </script>
</body>
</html>
