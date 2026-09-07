@extends('layouts.admin')

@section('title', 'Quản lý khách hàng')

@section('page-style')

@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Quản lý khách hàng</h2>
        <p class="page-subtitle">Quản lý và tra cứu thông tin khách hàng.</p>
    </div>
</div>

<form method="GET" action="{{ route('admin.users.index') }}" class="filter-bar" id="filterForm">
    <div class="search-box">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm theo tên, email, SĐT..." onkeydown="if(event.key === 'Enter') document.getElementById('filterForm').submit()">
    </div>
    <div class="filter-options">
        <div class="filter-dropdown" style="position: relative;">
            <select name="membership_level" onchange="document.getElementById('filterForm').submit()" style="appearance: none; background: transparent; border: none; font-size: 10px; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px; cursor: pointer; outline: none; padding-right: 15px;">
                <option value="">CẤP ĐỘ (MEMBERSHIP)</option>
                @foreach(App\Models\User::MEMBERSHIPS as $key => $label)
                    <option value="{{ $key }}" {{ request('membership_level') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <svg viewBox="0 0 24 24" style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); pointer-events: none;"><polyline points="6 9 12 15 18 9"></polyline></svg>
        </div>
        <div class="filter-dropdown" style="position: relative;">
            <select name="status" onchange="document.getElementById('filterForm').submit()" style="appearance: none; background: transparent; border: none; font-size: 10px; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px; cursor: pointer; outline: none; padding-right: 15px;">
                <option value="">TRẠNG THÁI (STATUS)</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Đã khóa</option>
            </select>
            <svg viewBox="0 0 24 24" style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); pointer-events: none;"><polyline points="6 9 12 15 18 9"></polyline></svg>
        </div>
        <div class="filter-stats">
            Hiển thị 1-{{ $users->count() }} trên tổng số {{ $users->total() }} tài khoản
        </div>
    </div>
</form>

<div class="table-container">
    <table class="luxury-table">
        <thead>
            <tr>
                <th>NGƯỜI DÙNG</th>
                <th>EMAIL</th>
                <th>SỐ ĐIỆN THOẠI</th>
                <th>CẤP ĐỘ</th>
                <th style="text-align: center;">ĐƠN HÀNG</th>
                <th style="text-align: center;">TRẠNG THÁI</th>
                <th style="text-align: right;">THAO TÁC</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>
                        <div class="table-user-info">
                            @if($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="table-user-avatar">
                            @else
                                <div class="table-user-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                            @endif
                            <span class="table-user-name" style="font-weight: 600; font-size: 13px;">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td><span style="font-size: 13px; color: #666;">{{ $user->email }}</span></td>
                    <td style="white-space: nowrap;"><span style="font-size: 13px; font-weight: 500;">{{ $user->phone ?? '--' }}</span></td>
                    <td style="text-transform: capitalize;"><span style="font-size: 13px;">{{ $user->membership_level ?? '--' }}</span></td>
                    <td style="text-align: center; font-weight: 600; font-family: var(--font-sans); font-size: 13px;">{{ rand(1, 50) }}</td>
                    <td style="text-align: center; white-space: nowrap;">
                        @if($user->status == 1)
                            <span class="status-badge" style="background: #e8f5e9; color: #2e7d32; border: none;">Hoạt động</span>
                        @else
                            <span class="status-badge" style="background: #fce4e4; color: #c62828; border: none;">Đã khóa</span>
                        @endif
                    </td>
                    <td style="text-align: right; white-space: nowrap;">
                        <div style="display: flex; gap: 12px; justify-content: flex-end; align-items: center;">
                            <a href="{{ route('admin.users.edit', $user->id) }}" style="color: #666; font-size: 12px; font-weight: 600; text-decoration: none;">SỬA</a>
                            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" style="margin: 0;">
                                @csrf @method('PUT')
                                <input type="hidden" name="name" value="{{ $user->name }}">
                                <input type="hidden" name="email" value="{{ $user->email }}">
                                <input type="hidden" name="phone" value="{{ $user->phone }}">
                                <input type="hidden" name="role" value="{{ $user->role }}">
                                <input type="hidden" name="membership_level" value="{{ $user->membership_level }}">
                                @if($user->status == 1)
                                    <input type="hidden" name="status" value="0">
                                    <button type="submit" style="background: none; border: none; color: #d93025; font-size: 12px; font-weight: 600; cursor: pointer; padding: 0;" onclick="return confirm('Khóa tài khoản này?')">KHÓA</button>
                                @else
                                    <input type="hidden" name="status" value="1">
                                    <button type="submit" style="background: none; border: none; color: #2e7d32; font-size: 12px; font-weight: 600; cursor: pointer; padding: 0;" onclick="return confirm('Mở khóa tài khoản này?')">MỠ KHÓA</button>
                                @endif
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-secondary); padding: 40px;">
                        Chưa có khách hàng nào.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($users->hasPages())
    {{ $users->links('vendor.pagination.admin') }}

@endif
@endsection
