@extends('layouts.admin')

@section('title', 'Quản Lý Tài Khoản')

@section('page-style')
<style>
    .table-container {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        overflow: hidden;
    }

    .table-header {
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--border-color);
    }

    .btn {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background-color: var(--accent);
        color: white;
    }

    .btn-primary:hover {
        background-color: #333;
    }

    .btn-action {
        background-color: transparent;
        color: var(--text-muted);
        padding: 4px 8px;
    }
    
    .btn-action:hover {
        color: var(--accent);
    }

    .btn-action.delete:hover {
        color: #ff4d4f;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        text-align: left;
        padding: 16px 24px;
        font-size: 12px;
        text-transform: uppercase;
        color: var(--text-muted);
        font-weight: 600;
        letter-spacing: 0.5px;
        border-bottom: 1px solid var(--border-color);
        background-color: #fafafa;
    }

    td {
        padding: 16px 24px;
        font-size: 14px;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }

    tr:last-child td {
        border-bottom: none;
    }

    tr:hover {
        background-color: #fcfcfc;
    }

    .role-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .role-admin { background-color: #fff1f0; color: #f5222d; border: 1px solid #ffa39e; }
    .role-manager { background-color: #f9f0ff; color: #722ed1; border: 1px solid #d3adf7; }
    .role-staff { background-color: #e6f7ff; color: #1890ff; border: 1px solid #91d5ff; }
    .role-customer { background-color: #f6ffed; color: #52c41a; border: 1px solid #b7eb8f; }
    .role-default { background-color: #f5f5f5; color: #595959; border: 1px solid #d9d9d9; }

    .user-col {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar-sm {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 12px;
    }
    
    .pagination-wrapper {
        padding: 16px 24px;
        border-top: 1px solid var(--border-color);
    }
</style>
@endsection

@section('content')

@if(session('success'))
    <div style="padding: 15px; margin-bottom: 20px; background-color: #f6ffed; border: 1px solid #b7eb8f; border-radius: 4px; color: #52c41a; font-size: 14px;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="padding: 15px; margin-bottom: 20px; background-color: #fff1f0; border: 1px solid #ffa39e; border-radius: 4px; color: #f5222d; font-size: 14px;">
        {{ session('error') }}
    </div>
@endif

<div class="table-container">
    <div class="table-header">
        <h2 style="font-family: var(--font-serif); font-size: 18px; font-weight: 600;">Danh Sách Tài Khoản</h2>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" fill="none" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Thêm Mới
        </a>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Họ và Tên</th>
                <th>Email</th>
                <th>Chức vụ</th>
                <th>Ngày tham gia</th>
                <th style="text-align: right;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>
                    <div class="user-col">
                        <div class="user-avatar-sm">{{ substr($user->name, 0, 1) }}</div>
                        <span style="font-weight: 500;">{{ $user->name }}</span>
                    </div>
                </td>
                <td style="color: var(--text-muted);">{{ $user->email }}</td>
                <td>
                    @php
                        $roleClass = match($user->role) {
                            'admin' => 'role-admin',
                            'manager' => 'role-manager',
                            'staff' => 'role-staff',
                            'customer' => 'role-customer',
                            default => 'role-default',
                        };
                    @endphp
                    <span class="role-badge {{ $roleClass }}">{{ \App\Models\User::ROLES[$user->role] ?? $user->role }}</span>
                </td>
                <td style="color: var(--text-muted); font-size: 13px;">{{ $user->created_at->format('d/m/Y') }}</td>
                <td style="text-align: right;">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-action" title="Chỉnh sửa">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" fill="none" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </a>
                    
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản này không?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-action delete" title="Xóa">
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" fill="none" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="pagination-wrapper">
        {{ $users->links() }}
    </div>
</div>
@endsection
