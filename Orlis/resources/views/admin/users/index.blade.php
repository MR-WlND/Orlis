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
    <table>
        <thead>
            <tr>
                <th>NGƯỜI DÙNG</th>
                <th>EMAIL</th>
                <th>SỐ ĐIỆN THOẠI</th>
                <th>CẤP ĐỘ</th>
                <th>SỐ ĐƠN HÀNG</th>
                <th>TRẠNG THÁI</th>
                <th>HÀNH ĐỘNG</th>
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
                            <span class="table-user-name">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td style="white-space: nowrap;">{{ $user->phone ?? '--' }}</td>
                    <td style="text-transform: capitalize;">{{ $user->membership_level ?? '--' }}</td>
                    <td style="font-weight: 600; font-family: var(--font-sans);">{{ rand(1, 50) }}</td>
                    <td style="white-space: nowrap;">
                        @if($user->status == 1)
                            <span class="status-active">Hoạt động</span>
                        @else
                            <span class="status-pending">Đã khóa</span>
                        @endif
                    </td>
                    <td style="white-space: nowrap;">
                        <div class="action-links" style="display: flex; gap: 15px;">
                            <a href="{{ route('admin.users.edit', $user->id) }}">SỬA</a>
                            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" style="margin:0;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="name" value="{{ $user->name }}">
                                <input type="hidden" name="email" value="{{ $user->email }}">
                                <input type="hidden" name="phone" value="{{ $user->phone }}">
                                <input type="hidden" name="role" value="{{ $user->role }}">
                                <input type="hidden" name="membership_level" value="{{ $user->membership_level }}">
                                @if($user->status == 1)
                                    <input type="hidden" name="status" value="0">
                                    <button type="submit" style="background:none; border:none; color: var(--text-secondary); cursor:pointer; font:inherit; padding:0;" onclick="return confirm('Bạn có chắc chắn muốn khóa tài khoản này không?')">KHÓA</button>
                                @else
                                    <input type="hidden" name="status" value="1">
                                    <button type="submit" style="background:none; border:none; color: #28a745; cursor:pointer; font:inherit; padding:0;" onclick="return confirm('Mở khóa tài khoản này?')">MỞ KHÓA</button>
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
    <div class="pagination-container">
        <div class="pagination-info">
            Hiển thị {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} trên tổng số {{ $users->total() ?? 0 }} tài khoản
        </div>
        <div class="pagination-buttons">
            @if ($users->onFirstPage())
                <button class="btn-page" disabled style="opacity: 0.5; cursor: not-allowed;">TRANG TRƯỚC</button>
            @else
                <a href="{{ $users->previousPageUrl() }}" class="btn-page">TRANG TRƯỚC</a>
            @endif

            @if ($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" class="btn-page">TIẾP THEO</a>
            @else
                <button class="btn-page" disabled style="opacity: 0.5; cursor: not-allowed;">TIẾP THEO</button>
            @endif
        </div>
    </div>
@endif
@endsection
