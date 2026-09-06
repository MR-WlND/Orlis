<!DOCTYPE html>
<html lang="vi">

<head>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chọn vai trò đăng nhập</title>
    @vite(['resources/css/client.css'])
</head>

<body class="login-select-body">
    <div class="container">
        <h1>Chọn vai trò để đăng nhập</h1>
        <div class="roles">
            <a href="{{ route('role.login', ['role' => 'admin']) }}" class="role-card">
                <div class="role-name">👨‍💼 Admin</div>
                <div class="role-desc">Quản lý toàn bộ hệ thống, phân quyền, cấu hình</div>
                <a href="{{ route('role.login', ['role' => 'admin']) }}" class="role-btn">Đăng nhập</a>
            </a>

            <a href="{{ route('role.login', ['role' => 'manager']) }}" class="role-card">
                <div class="role-name">📊 Manager</div>
                <div class="role-desc">Quản lý đơn hàng, sản phẩm, báo cáo, nhân viên</div>
                <a href="{{ route('role.login', ['role' => 'manager']) }}" class="role-btn">Đăng nhập</a>
            </a>

            <a href="{{ route('role.login', ['role' => 'staff']) }}" class="role-card">
                <div class="role-name">👔 Staff</div>
                <div class="role-desc">Xử lý đơn hàng, cập nhật trạng thái, hỗ trợ khách hàng</div>
                <a href="{{ route('role.login', ['role' => 'staff']) }}" class="role-btn">Đăng nhập</a>
            </a>

            <a href="{{ route('role.login', ['role' => 'customer']) }}" class="role-card">
                <div class="role-name"><svg style="vertical-align: middle; margin-right: 8px;" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg> Customer</div>
                <div class="role-desc">Đăng ký, mua hàng, theo dõi đơn, đánh giá sản phẩm</div>
                <a href="{{ route('role.login', ['role' => 'customer']) }}" class="role-btn">Đăng nhập</a>
            </a>

            <a href="{{ route('role.login', ['role' => 'shipper']) }}" class="role-card">
                <div class="role-name"><svg style="vertical-align: middle; margin-right: 8px;" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg> Shipper</div>
                <div class="role-desc">Nhận đơn giao hàng, cập nhật trạng thái giao hàng</div>
                <a href="{{ route('role.login', ['role' => 'shipper']) }}" class="role-btn">Đăng nhập</a>
            </a>

            <a href="{{ route('role.login', ['role' => 'warehouse_staff']) }}" class="role-card">
                <div class="role-name"><svg style="vertical-align: middle; margin-right: 8px;" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg> Warehouse Staff</div>
                <div class="role-desc">Quản lý tồn kho, nhập/xuất hàng</div>
                <a href="{{ route('role.login', ['role' => 'warehouse_staff']) }}" class="role-btn">Đăng nhập</a>
            </a>

            <a href="{{ route('role.login', ['role' => 'supplier']) }}" class="role-card">
                <div class="role-name">🏭 Supplier</div>
                <div class="role-desc">Cập nhật hàng hóa, theo dõi đơn nhập</div>
                <a href="{{ route('role.login', ['role' => 'supplier']) }}" class="role-btn">Đăng nhập</a>
            </a>

            <a href="{{ route('role.login', ['role' => 'guest']) }}" class="role-card">
                <div class="role-name">👤 Guest</div>
                <div class="role-desc">Khách chưa đăng nhập, chỉ xem sản phẩm và tìm kiếm</div>
                <a href="{{ route('role.login', ['role' => 'guest']) }}" class="role-btn">Đăng nhập</a>
            </a>
        </div>
    </div>
</body>

</html>
