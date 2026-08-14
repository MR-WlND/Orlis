<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bảng Điều Khiển') - Orlis</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Alata&family=Castoro:ital,wght@0,400;1,400&family=Charis+SIL:ital,wght@0,400;0,700;1,400;1,700&display=swap');

        :root {
            --bg-body: #f4f6f8;
            --bg-sidebar: #ffffff;
            --bg-header: #ffffff;
            --bg-card: #ffffff;
            --text-main: #333333;
            --text-muted: #888888;
            --border-color: #eaeaea;
            --accent: #1a1a1a;
            --font-sans: 'Alata', sans-serif;
            --font-serif: 'Charis SIL', serif;
            --font-logo: 'Castoro', serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--bg-body);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background-color: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
        }

        .sidebar-header {
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid var(--border-color);
        }

        .sidebar-logo {
            font-family: var(--font-logo);
            font-size: 26px;
            font-weight: 600;
            letter-spacing: 2px;
            color: var(--text-main);
        }

        .sidebar-menu {
            flex: 1;
            padding: 30px 20px;
            overflow-y: auto;
        }

        .menu-title {
            font-size: 11px;
            text-transform: uppercase;
            color: var(--text-muted);
            letter-spacing: 1px;
            margin-bottom: 15px;
            margin-left: 10px;
        }

        .menu-list {
            list-style: none;
        }

        .menu-item {
            margin-bottom: 8px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-radius: 6px;
            color: var(--text-muted);
            font-size: 14px;
            transition: all 0.3s;
        }

        .menu-link svg {
            width: 18px;
            height: 18px;
            margin-right: 15px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.5;
            transition: stroke 0.3s;
        }

        .menu-link:hover, .menu-link.active {
            background-color: #f9f9f9;
            color: var(--text-main);
        }

        .menu-link:hover svg, .menu-link.active svg {
            stroke: var(--text-main);
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: var(--text-main);
            font-size: 16px;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-size: 14px;
            font-weight: 600;
        }

        .user-role {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: capitalize;
        }

        /* Main Content */
        .main-wrapper {
            flex: 1;
            margin-left: 260px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Header */
        .header {
            height: 80px;
            background-color: var(--bg-header);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .header-title {
            font-family: var(--font-serif);
            font-size: 22px;
            font-weight: 600;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .search-bar {
            display: flex;
            align-items: center;
            background-color: var(--bg-body);
            border-radius: 20px;
            padding: 8px 15px;
            width: 250px;
        }

        .search-bar svg {
            width: 16px;
            height: 16px;
            stroke: var(--text-muted);
            margin-right: 10px;
        }

        .search-bar input {
            border: none;
            background: transparent;
            outline: none;
            font-family: var(--font-sans);
            font-size: 13px;
            color: var(--text-main);
            width: 100%;
        }

        .action-icon {
            cursor: pointer;
            position: relative;
        }

        .action-icon svg {
            width: 20px;
            height: 20px;
            stroke: var(--text-main);
            fill: none;
            stroke-width: 1.5;
        }

        .badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background-color: #ff4d4f;
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        /* Content Area */
        .content {
            padding: 40px;
            flex: 1;
        }

        @media (max-width: 900px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            .main-wrapper {
                margin-left: 0;
            }
        }
        
        @yield('page-style')
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="/admin" class="sidebar-logo">Orlis</a>
        </div>
        <div class="sidebar-menu">
            <div class="menu-title">Menu Chính</div>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="/admin" class="menu-link active">
                        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                        Tổng Quan
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 01-8 0"></path></svg>
                        Đơn Hàng
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <svg viewBox="0 0 24 24"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                        Sản Phẩm
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.users.index') }}" class="menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        Tài Khoản
                    </a>
                </li>
            </ul>
            
            <div class="menu-title" style="margin-top: 30px;">Cài Đặt</div>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        Cấu Hình
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="sidebar-footer">
            <div class="user-avatar">
                {{ auth()->check() ? substr(auth()->user()->name, 0, 1) : 'A' }}
            </div>
            <div class="user-info">
                <span class="user-name">{{ auth()->check() ? auth()->user()->name : 'Quản Trị Viên' }}</span>
                <span class="user-role">{{ auth()->check() ? auth()->user()->role : 'Quản Trị' }}</span>
            </div>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Header -->
        <header class="header">
            <h1 class="header-title">@yield('title', 'Tổng Quan')</h1>
            
            <div class="header-actions">
                <div class="search-bar">
                    <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" placeholder="Tìm kiếm...">
                </div>
                <div class="action-icon">
                    <svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                    <div class="badge"></div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="content">
            @yield('content')
        </main>
    </div>

</body>
</html>
