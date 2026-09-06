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
        <div>
            <div class="user-name">{{ $sidebarUser->name }}</div>
            <div class="user-level" style="text-transform: uppercase;">{{ \App\Models\User::MEMBERSHIPS[$sidebarUser->membership_level] ?? 'Classic' }} MEMBER</div>
        </div>
    </div>
    
    <div class="nav-links-wrapper">
        <a href="{{ route('customer.dashboard') }}" class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
            TỔNG QUAN ĐỘC QUYỀN
        </a>
        <a href="{{ route('customer.orders') }}" class="nav-link {{ request()->routeIs('customer.orders*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 01-8 0"></path></svg>
            ĐƠN HÀNG & GIAO DỊCH
        </a>
        <a href="{{ route('customer.appointments') }}" class="nav-link {{ request()->routeIs('customer.appointments*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            QUẢN LÝ LỊCH HẸN
        </a>
        <a href="{{ route('customer.addresses') }}" class="nav-link {{ request()->routeIs('customer.addresses') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Sổ địa chỉ
        </a>
        <a href="{{ route('customer.profile') }}" class="nav-link {{ request()->routeIs('customer.profile') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            THÔNG TIN CÁ NHÂN & THẺ
        </a>
        <form method="POST" action="{{ route('logout') }}" style="margin-top: 8px;">
            @csrf
            <button type="submit" class="nav-link" style="width:100%;text-align:left;background:none;border:none;cursor:pointer;">
                <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                ĐĂNG XUẤT
            </button>
        </form>
    </div>
</div>
