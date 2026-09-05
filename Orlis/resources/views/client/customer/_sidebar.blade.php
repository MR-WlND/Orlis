<div class="sidebar-nav">
    @php $sidebarUser = auth()->user(); @endphp
    <div class="user-info">
        <div class="avatar-circle">
            @if($sidebarUser->avatar)
                <img src="{{ Storage::url($sidebarUser->avatar) }}" alt="{{ $sidebarUser->name }}" style="width:100%;height:100%;object-fit:cover;">
            @else
                {{ strtoupper(substr($sidebarUser->name, 0, 2)) }}
            @endif
        </div>
        <div class="user-name">{{ $sidebarUser->name }}</div>
        <div class="user-level">{{ \App\Models\User::MEMBERSHIPS[$sidebarUser->membership_level] ?? 'Classic' }} Member</div>
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
