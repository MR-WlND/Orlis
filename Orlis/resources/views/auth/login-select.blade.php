<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chọn vai trò đăng nhập</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 40px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 40px;
        }

        .roles {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .role-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            text-decoration: none;
            color: inherit;
            transition: transform 0.2s, box-shadow 0.2s;
            border: 2px solid transparent;
        }

        .role-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            border-color: #2563eb;
        }

        .role-name {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 8px;
            color: #2563eb;
        }

        .role-desc {
            font-size: 14px;
            color: #666;
            margin-bottom: 16px;
        }

        .role-btn {
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.2s;
        }

        .role-card:hover .role-btn {
            background: #1d4ed8;
        }
    </style>
</head>

<body>
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
                <div class="role-name">🛍️ Customer</div>
                <div class="role-desc">Đăng ký, mua hàng, theo dõi đơn, đánh giá sản phẩm</div>
                <a href="{{ route('role.login', ['role' => 'customer']) }}" class="role-btn">Đăng nhập</a>
            </a>

            <a href="{{ route('role.login', ['role' => 'shipper']) }}" class="role-card">
                <div class="role-name">🚚 Shipper</div>
                <div class="role-desc">Nhận đơn giao hàng, cập nhật trạng thái giao hàng</div>
                <a href="{{ route('role.login', ['role' => 'shipper']) }}" class="role-btn">Đăng nhập</a>
            </a>

            <a href="{{ route('role.login', ['role' => 'warehouse_staff']) }}" class="role-card">
                <div class="role-name">📦 Warehouse Staff</div>
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
