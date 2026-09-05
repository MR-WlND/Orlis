@extends('layouts.client')

@section('title', 'Tài khoản của tôi - Orlis')

@section('styles')
<style>
    .customer-wrap { max-width: 1060px; margin: 0 auto; padding: 100px 20px 60px; display: grid; grid-template-columns: 220px 1fr; gap: 32px; }
    .sidebar-nav { position: sticky; top: 90px; height: fit-content; }
    .sidebar-nav .user-info { padding-bottom: 20px; border-bottom: 1px solid #e8e8e8; margin-bottom: 16px; }
    .avatar-circle { width: 56px; height: 56px; border-radius: 50%; object-fit: cover; background: #f0ece6; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 20px; color: var(--primary); margin-bottom: 10px; overflow: hidden; }
    .user-name { font-weight: 600; font-size: 15px; margin-bottom: 2px; }
    .user-level { font-size: 12px; color: #999; }
    .nav-link { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 6px; text-decoration: none; color: #555; font-size: 14px; transition: all 0.15s; margin-bottom: 2px; }
    .nav-link:hover, .nav-link.active { background: #f5f0ea; color: var(--primary); font-weight: 500; }
    .nav-link svg { width: 16px; height: 16px; stroke: currentColor; fill: none; stroke-width: 2; flex-shrink: 0; }
    .kpi-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 28px; }
    .kpi-card { background: white; border-radius: 8px; padding: 18px 20px; border: 1px solid #efefef; text-align: center; }
    .kpi-value { font-family: var(--font-serif); font-size: 28px; font-weight: 600; color: var(--primary); }
    .kpi-label { font-size: 12px; color: #999; margin-top: 4px; text-transform: uppercase; letter-spacing: 0.5px; }
    .section-title { font-family: var(--font-serif); font-size: 18px; font-weight: 500; margin-bottom: 18px; }
    .order-row { display: flex; gap: 14px; align-items: center; padding: 14px; background: white; border-radius: 8px; border: 1px solid #efefef; margin-bottom: 10px; }
    .order-row-code { font-weight: 600; font-size: 14px; flex: 1; }
    .order-row-total { font-weight: 700; font-size: 14px; white-space: nowrap; }
    .order-row-date { font-size: 12px; color: #aaa; }
    .status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
    .btn-link { color: var(--primary); text-decoration: none; font-size: 13px; font-weight: 500; }
    @media(max-width: 768px) { .customer-wrap { grid-template-columns: 1fr; } .sidebar-nav { position: static; } }
</style>
@endsection

@section('content')
<div style="background: #f8f7f5; min-height: 100vh;">
<div class="customer-wrap">

    {{-- Sidebar Nav --}}
    <div class="sidebar-nav">
        <div class="user-info">
            <div class="avatar-circle">
                @if($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" style="width:100%;height:100%;object-fit:cover;">
                @else
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                @endif
            </div>
            <div class="user-name">{{ $user->name }}</div>
            <div class="user-level">{{ \App\Models\User::MEMBERSHIPS[$user->membership_level] ?? 'Classic' }} Member</div>
        </div>
        <a href="{{ route('customer.dashboard') }}" class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect></svg>
            Tổng quan
        </a>
        <a href="{{ route('customer.orders') }}" class="nav-link {{ request()->routeIs('customer.orders*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 01-8 0"></path></svg>
            Đơn hàng của tôi
        </a>
        <a href="{{ route('customer.wishlist') }}" class="nav-link {{ request()->routeIs('customer.wishlist') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"></path></svg>
            Yêu thích
        </a>
        <a href="{{ route('customer.addresses') }}" class="nav-link {{ request()->routeIs('customer.addresses*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Địa chỉ của tôi
        </a>
        <a href="{{ route('customer.profile') }}" class="nav-link {{ request()->routeIs('customer.profile') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            Hồ sơ cá nhân
        </a>
        <form method="POST" action="{{ route('logout') }}" style="margin-top: 8px;">
            @csrf
            <button type="submit" class="nav-link" style="width:100%;text-align:left;background:none;border:none;cursor:pointer;font-size:14px;color:#e74c3c;">
                <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                Đăng xuất
            </button>
        </form>
    </div>

    {{-- Content --}}
    <div>
        @if(session('success'))
            <div style="background:#d4edda;color:#155724;padding:12px 16px;border-radius:6px;margin-bottom:20px;font-size:13px;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div style="background:#f8d7da;color:#721c24;padding:12px 16px;border-radius:6px;margin-bottom:20px;font-size:13px;">{{ session('error') }}</div>
        @endif

        <h2 class="section-title">Xin chào, {{ $user->name }}!</h2>

        <div class="kpi-row">
            <div class="kpi-card">
                <div class="kpi-value">{{ $totalOrders }}</div>
                <div class="kpi-label">Tổng đơn hàng</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-value" style="color: #faad14;">{{ $pendingOrders }}</div>
                <div class="kpi-label">Đang xử lý</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-value" style="color: #52c41a;">{{ $completedOrders }}</div>
                <div class="kpi-label">Đã hoàn thành</div>
            </div>
        </div>

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
            <h3 style="font-size: 15px; font-weight: 600;">Đơn hàng gần nhất</h3>
            <a href="{{ route('customer.orders') }}" class="btn-link">Xem tất cả →</a>
        </div>

        @forelse($recentOrders as $order)
        <div class="order-row">
            <div>
                <div class="order-row-code">
                    <a href="{{ route('customer.order-detail', $order) }}" style="color:inherit;text-decoration:none;">{{ $order->order_code }}</a>
                </div>
                <div class="order-row-date">{{ $order->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <span class="status-badge" style="color:{{ $order->status_color }};background:{{ $order->status_color }}22;">
                {{ $order->status_label }}
            </span>
            <div class="order-row-total">{{ number_format($order->grand_total, 0, ',', '.') }}₫</div>
            <a href="{{ route('customer.order-detail', $order) }}" class="btn-link">Chi tiết</a>
        </div>
        @empty
        <div style="text-align:center;padding:40px 0;color:#aaa;font-size:14px;font-style:italic;">
            Bạn chưa có đơn hàng nào.
            <br><a href="{{ route('catalog') }}" style="color:var(--primary);text-decoration:none;font-weight:500;margin-top:8px;display:inline-block;">Khám phá sản phẩm →</a>
        </div>
        @endforelse
    </div>

</div>
</div>
@endsection
