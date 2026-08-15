<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bảng Điều Khiển') - Orlis</title>
    @vite(['resources/css/admin.css'])
    @yield('page-style')
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="brand-group">
                <div class="brand-text">
                    <a href="/admin" class="sidebar-logo">ORLIS</a>
                    <span class="brand-subtitle">TRANG QUẢN TRỊ</span>
                </div>
            </div>
        </div>
        


        <div class="sidebar-menu">
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="/admin" class="menu-link {{ request()->is('admin') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                        TỔNG QUAN
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.categories.index') }}" class="menu-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                        DANH MỤC
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.products.index') }}" class="menu-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        SẢN PHẨM
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.posts.index') }}" class="menu-link {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        TẠP CHÍ
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 01-8 0"></path></svg>
                        ĐƠN HÀNG
                    </a>
                </li>
                @php
                    $isAccountActive = request()->routeIs('admin.admins.*') || request()->routeIs('admin.users.*');
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
                            <a href="{{ route('admin.users.index') }}" class="menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" style="padding: 8px 0; font-size: 10px; border-right: none; background: transparent; {{ request()->routeIs('admin.users.*') ? 'color: var(--text-primary);' : '' }}">
                                KHÁCH HÀNG
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('admin.admins.index') }}" class="menu-link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}" style="padding: 8px 0; font-size: 10px; border-right: none; background: transparent; {{ request()->routeIs('admin.admins.*') ? 'color: var(--text-primary);' : '' }}">
                                NHÂN SỰ
                            </a>
                        </li>
                    </ul>
                </li>
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
                <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" placeholder="Tìm kiếm đơn hàng, sản phẩm...">
            </div>
            
            <div class="header-actions">
                <div class="action-icon">
                    <svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                    <div class="badge"></div>
                </div>
                
                <div class="header-user">
                    @php
                        $currentUser = auth('admin')->user() ?? auth('web')->user();
                    @endphp
                    <div class="user-info">
                        <span class="user-name">{{ $currentUser ? $currentUser->name : 'QUẢN TRỊ VIÊN' }}</span>
                        <span class="user-location">{{ $currentUser ? (App\Models\Admin::ROLES[$currentUser->role] ?? App\Models\User::ROLES[$currentUser->role] ?? $currentUser->role) : 'Quản trị hệ thống' }}</span>
                    </div>
                    <div class="user-avatar-small" style="width: 34px; height: 34px; border-radius: 50%; overflow: hidden;">
                        @if($currentUser && $currentUser->avatar)
                            <img src="{{ Storage::url($currentUser->avatar) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:#eee; font-weight:bold; color:#777; font-size:12px;">
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
