@extends('layouts.admin')

@section('title', 'Quản lý nhân sự')

@section('page-style')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 30px;
    }
    .page-title {
        font-family: var(--font-serif);
        font-size: 32px;
        color: var(--text-primary);
        font-weight: 500;
        margin: 0 0 8px 0;
    }
    .page-subtitle {
        font-size: 13px;
        color: var(--text-secondary);
        margin: 0;
    }

    .btn-add-new {
        display: inline-flex;
        align-items: center;
        background-color: var(--text-primary);
        color: #fff;
        padding: 12px 25px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        text-decoration: none;
        transition: all 0.2s;
        border: 1px solid var(--text-primary);
    }
    .btn-add-new:hover { background-color: #333; }

    /* Thanh Filter */
    .filter-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 10px;
    }
    
    .search-box {
        position: relative;
        width: 300px;
    }
    .search-box input {
        width: 100%;
        padding: 8px 10px 8px 30px;
        border: none;
        background: transparent;
        font-size: 13px;
        color: var(--text-primary);
        outline: none;
    }
    .search-box input::placeholder {
        color: var(--text-placeholder);
    }
    .search-box svg {
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        stroke: var(--text-placeholder);
        fill: none;
    }
    
    .search-box::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 1px;
        background: #eee;
    }

    .filter-options {
        display: flex;
        align-items: center;
        gap: 30px;
    }
    .filter-dropdown {
        font-size: 10px;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .filter-dropdown svg {
        width: 12px;
        height: 12px;
        stroke: currentColor;
        fill: none;
    }
    
    .filter-stats {
        font-size: 12px;
        color: var(--text-secondary);
    }

    /* Bảng dữ liệu */
    .table-container {
        background: #fff;
        border: 1px solid var(--border-color);
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th {
        font-size: 10px;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 20px 20px;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
    }
    td {
        padding: 20px;
        font-size: 14px;
        color: var(--text-primary);
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }
    tr:last-child td { border-bottom: none; }

    /* Cột User */
    .table-user-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .table-user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        background: #eee;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: bold;
        color: #777;
    }
    .table-user-name {
        font-family: var(--font-serif);
        font-size: 18px;
        font-weight: 500;
    }

    /* Cột Status */
    .status-active {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
    }
    .status-active::before {
        content: '';
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background-color: var(--text-primary);
    }
    
    .status-pending {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: var(--text-secondary);
    }
    .status-pending::before {
        content: '';
        width: 5px;
        height: 5px;
        border-radius: 50%;
        border: 1px solid var(--text-secondary);
    }

    .action-links {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .action-links a {
        color: var(--text-primary);
        text-decoration: none;
    }
    .action-links a:hover { text-decoration: underline; }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Quản lý nhân sự</h2>
        <p class="page-subtitle">Manage administrator, editor, and staff access to the atelier.</p>
    </div>
    <a href="{{ route('admin.admins.create') }}" class="btn-add-new">
        <span style="margin-right: 8px;">+</span> THÊM THÀNH VIÊN MỚI
    </a>
</div>

<form method="GET" action="{{ route('admin.admins.index') }}" class="filter-bar" id="filterForm">
    <div class="search-box">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm theo tên, email, SĐT..." onkeydown="if(event.key === 'Enter') document.getElementById('filterForm').submit()">
    </div>
    <div class="filter-options">
        <div class="filter-dropdown" style="position: relative;">
            <select name="role" onchange="document.getElementById('filterForm').submit()" style="appearance: none; background: transparent; border: none; font-size: 10px; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px; cursor: pointer; outline: none; padding-right: 15px;">
                <option value="">VAI TRÒ (ROLE)</option>
                @foreach(App\Http\Controllers\Admin\AdminAccountController::ROLES as $key => $label)
                    <option value="{{ $key }}" {{ request('role') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <svg viewBox="0 0 24 24" style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); pointer-events: none;"><polyline points="6 9 12 15 18 9"></polyline></svg>
        </div>
        <div class="filter-dropdown" style="position: relative;">
            <select name="status" onchange="document.getElementById('filterForm').submit()" style="appearance: none; background: transparent; border: none; font-size: 10px; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px; cursor: pointer; outline: none; padding-right: 15px;">
                <option value="">TRẠNG THÁI (STATUS)</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động</option>
                <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Chờ xác nhận</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Đã khóa</option>
            </select>
            <svg viewBox="0 0 24 24" style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); pointer-events: none;"><polyline points="6 9 12 15 18 9"></polyline></svg>
        </div>
        <div class="filter-stats">
            Hiển thị 1-{{ $admins->count() }} trên tổng số {{ $admins->total() }} tài khoản
        </div>
    </div>
</form>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>NGƯỜI DÙNG</th>
                <th>EMAIL</th>
                <th>SỐ ĐIỆN THOẠI</th>
                <th>VAI TRÒ</th>
                <th>TRẠNG THÁI</th>
                <th>HÀNH ĐỘNG</th>
            </tr>
        </thead>
        <tbody>
            @forelse($admins as $admin)
                <tr>
                    <td>
                        <div class="table-user-info">
                            @if($admin->avatar)
                                <img src="{{ Storage::url($admin->avatar) }}" alt="{{ $admin->name }}" class="table-user-avatar">
                            @else
                                <div class="table-user-avatar">{{ strtoupper(substr($admin->name, 0, 2)) }}</div>
                            @endif
                            <span class="table-user-name">{{ $admin->name }}</span>
                        </div>
                    </td>
                    <td>{{ $admin->email }}</td>
                    <td style="white-space: nowrap;">{{ $admin->phone ?? '--' }}</td>
                    <td>{{ App\Http\Controllers\Admin\AdminAccountController::ROLES[$admin->role] ?? $admin->role }}</td>
                    <td style="white-space: nowrap;">
                        @if($admin->status == 1)
                            <span class="status-active">Hoạt động</span>
                        @elseif($admin->status == 2)
                            <span class="status-pending">Chờ xác nhận</span>
                        @else
                            <span class="status-pending" style="color: #d32f2f;">Đã khóa</span>
                        @endif
                    </td>
                    <td style="white-space: nowrap;">
                        <div class="action-links" style="display: flex; gap: 15px;">
                            <a href="{{ route('admin.admins.edit', $admin->id) }}">SỬA</a>
                            @if($admin->status == 2)
                                <button type="button" style="background:none; border:none; color: #007bff; cursor:pointer; font:inherit; padding:0;" onclick="alert('Đã gửi lại email xác nhận cho nhân sự thành công!')">GỬI LẠI MAIL</button>
                            @else
                                <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST" style="margin:0;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="name" value="{{ $admin->name }}">
                                    <input type="hidden" name="email" value="{{ $admin->email }}">
                                    <input type="hidden" name="phone" value="{{ $admin->phone }}">
                                    <input type="hidden" name="role" value="{{ $admin->role }}">
                                    @if($admin->status == 1)
                                        <input type="hidden" name="status" value="0">
                                        <button type="submit" style="background:none; border:none; color: var(--text-secondary); cursor:pointer; font:inherit; padding:0;" onclick="return confirm('Bạn có chắc chắn muốn khóa tài khoản này không?')">KHÓA</button>
                                    @elseif($admin->status == 0)
                                        <input type="hidden" name="status" value="1">
                                        <button type="submit" style="background:none; border:none; color: #28a745; cursor:pointer; font:inherit; padding:0;" onclick="return confirm('Mở khóa tài khoản này?')">MỞ KHÓA</button>
                                    @endif
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-secondary); padding: 40px;">
                        Chưa có tài khoản quản trị nào.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($admins->hasPages())
    <div style="margin-top: 30px;">
        {{ $admins->links() }}
    </div>
@endif
@endsection
