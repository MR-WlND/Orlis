@php $sidebarUser = auth()->user(); @endphp

<div class="csb-wrap">
    <!-- User info -->
    <div class="csb-user">
        <div class="csb-avatar">
            @if($sidebarUser->avatar)
                <img src="{{ Storage::url($sidebarUser->avatar) }}" alt="{{ $sidebarUser->name }}">
            @else
                {{ strtoupper(substr($sidebarUser->name, 0, 2)) }}
            @endif
        </div>
        <div>
            <div class="csb-name">{{ $sidebarUser->name }}</div>
            <div class="csb-level">{{ \App\Models\User::MEMBERSHIPS[$sidebarUser->membership_level ?? 'classic'] ?? 'Classic' }} MEMBER</div>
        </div>
    </div>

    <!-- Nav -->
    <nav class="csb-nav">
        <a href="{{ route('customer.dashboard') }}" class="csb-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            {{ __('messages.exclusive_overview') }}
        </a>
        <a href="{{ route('customer.orders') }}" class="csb-link {{ request()->routeIs('customer.orders*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
            {{ __('messages.orders_transactions') }}
        </a>
        <a href="{{ route('customer.appointments') }}" class="csb-link {{ request()->routeIs('customer.appointments*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            {{ __('messages.manage_appointments') }}
        </a>
        <a href="{{ route('tickets.index') }}" class="csb-link {{ request()->routeIs('tickets*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            {{ __('messages.support_requests') }}
        </a>
        <a href="{{ route('customer.addresses') }}" class="csb-link {{ request()->routeIs('customer.addresses') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            {{ __('messages.address_book') }}
        </a>
        <a href="{{ route('customer.profile') }}" class="csb-link {{ request()->routeIs('customer.profile') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            {{ __('messages.personal_information') }}
        </a>

        <div class="csb-divider"></div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="csb-link csb-logout">
                <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                {{ __('messages.logout_caps') }}
            </button>
        </form>
    </nav>
</div>

<style>
.csb-wrap {
    padding: 28px 0 20px;
    height: 100%;
    display: flex;
    flex-direction: column;
}
.csb-user {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 0 20px 22px;
    border-bottom: 1px solid #f0f0f0;
    margin-bottom: 14px;
}
.csb-avatar {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    background: #111;
    color: white;
    font-size: 16px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
}
.csb-avatar img { width: 100%; height: 100%; object-fit: cover; }
.csb-name { font-size: 13.5px; font-weight: 600; color: #111; margin-bottom: 3px; line-height: 1.2; }
.csb-level { font-size: 9.5px; font-weight: 700; letter-spacing: 0.8px; color: #c8a97e; text-transform: uppercase; }

.csb-nav {
    display: flex;
    flex-direction: column;
    gap: 2px;
    padding: 0 10px;
    flex: 1;
}
.csb-link {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 11px 14px;
    font-size: 10.5px;
    font-weight: 600;
    letter-spacing: 0.6px;
    text-transform: uppercase;
    color: #666;
    text-decoration: none;
    transition: all 0.15s;
    border-radius: 2px;
    border: none;
    background: none;
    cursor: pointer;
    width: 100%;
    text-align: left;
    font-family: inherit;
}
.csb-link svg {
    width: 15px; height: 15px;
    stroke: currentColor; fill: none; stroke-width: 1.8;
    flex-shrink: 0;
}
.csb-link:hover:not(.active) {
    background: #f5f5f3;
    color: #111;
}
.csb-link.active {
    background: #111;
    color: white;
}

.csb-divider {
    height: 1px;
    background: #f0f0f0;
    margin: 10px 4px;
}
.csb-logout { color: #aaa; margin-top: auto; }
.csb-logout:hover { color: #c0392b !important; background: #fff3f3 !important; }

/* Responsive Mobile Navigation */
@media (max-width: 900px) {
    .csb-wrap {
        padding: 0;
        border-bottom: 1px solid #ebebeb;
        background: #fff;
        margin-bottom: 16px;
    }
    .csb-user {
        display: none;
    }
    .csb-nav {
        flex-direction: row;
        overflow-x: auto;
        padding: 0 20px;
        gap: 24px;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        align-items: center;
        height: 52px;
    }
    .csb-nav::-webkit-scrollbar {
        display: none;
    }
    .csb-link {
        flex: 0 0 auto;
        width: auto;
        padding: 0;
        border: none;
        border-radius: 0;
        background: transparent !important;
        color: #888;
        scroll-snap-align: start;
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        height: 100%;
        position: relative;
    }
    .csb-link.active {
        color: #111;
    }
    .csb-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 1px;
        background: #111;
    }
    .csb-link:hover:not(.active) {
        background: transparent !important;
        color: #111;
    }
    .csb-link svg {
        display: none; /* Hide icons on mobile for a cleaner text-only tab look */
    }
    .csb-divider {
        display: none;
    }
    .csb-logout {
        margin-top: 0;
    }
}
</style>
