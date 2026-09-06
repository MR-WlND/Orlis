<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Orlis - Thương Hiệu Thời Trang Cao Cấp')</title>
    @vite(['resources/css/client.css', 'resources/js/client.js'])
</head>
<body>
    @yield('styles')

    <!-- Header -->
    <header id="mainHeader" class="{{ request()->is('/') ? '' : (request()->is('beauty') ? 'header-dark-text' : 'header-light') }}">
        <div class="menu-icon" onclick="toggleDrawer()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 8h16M4 16h16"/></svg>
        </div>
        <a href="{{ session('department') === 'beauty' ? url('/beauty') : url('/') }}" class="logo">Orlis</a>
        <div class="action-icons">
            <a href="#" onclick="toggleSearchModal(); event.preventDefault();" title="{{ __('messages.search') }}" class="search-trigger" style="color: inherit;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35"/></svg>
            </a>

            <a href="{{ route('cart') }}" title="{{ __('messages.cart') }}" class="cart-icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                @if($cartCount > 0)
                    <span class="cart-badge">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
                @endif
            </a>
            
            @auth
                <div class="user-menu-wrap" tabindex="0">
                    <a href="#" onclick="event.preventDefault();" title="{{ __('messages.my_account') }}" class="user-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </a>
                    <div class="user-dropdown">
                        <a href="{{ route('customer.dashboard') }}">{{ __('messages.profile') }}</a>
                        <a href="{{ route('tickets.index') }}">{{ __('messages.customer_support') }}</a>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('messages.logout') }}</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            @else
                <a href="#" onclick="toggleLoginModal(event)" title="{{ __('messages.login') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </a>
            @endauth
        </div>
    </header>

    <main id="swup" class="transition-fade">
        @yield('content')
    </main>

    @unless(View::hasSection('hideFooter'))
    <!-- Footer -->
    <footer>
        <!-- Newsletter -->
        <div class="footer-newsletter">
            <p>{{ __('messages.newsletter_desc') }}</p>
            <form class="subscribe-form" onsubmit="return false;">
                <input type="email" placeholder="E-mail">
                <button type="submit">{{ __('messages.subscribe_btn') }}</button>
            </form>
        </div>

        <!-- Links grid -->
        <div class="footer-links">
            @if(isset($footerLinks) && $footerLinks->count() > 0)
                @foreach($footerLinks as $groupName => $links)
                <div class="link-column">
                    <h5>{{ $groupName }}</h5>
                    <ul>
                        @foreach($links as $link)
                        <li><a href="{{ $link->url ?? '#' }}">{{ $link->title }}</a></li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            @else
                <div class="link-column">
                    <h5>{{ __('messages.orlis_fashion_boutiques') }}</h5>
                    <ul>
                        <li><a href="#">{{ __('messages.christian_orlis_couture') }}</a></li>
                        <li><a href="#">{{ __('messages.parfums_christian_orlis') }}</a></li>
                        <li><a href="#">{{ __('messages.careers') }}</a></li>
                    </ul>
                </div>
                <div class="link-column">
                    <h5>{{ __('messages.customer_service') }}</h5>
                    <ul>
                        <li><a href="#">{{ __('messages.return_policy') }}</a></li>
                        <li><a href="#">{{ __('messages.size_guide') }}</a></li>
                        <li><a href="#">{{ __('messages.shipping_policy') }}</a></li>
                    </ul>
                </div>
                <div class="link-column">
                    <h5>{{ __('messages.house_of_orlis') }}</h5>
                    <ul>
                        <li><a href="#">{{ __('messages.quality_commitment') }}</a></li>
                        <li><a href="#">{{ __('messages.social_responsibility') }}</a></li>
                    </ul>
                </div>
                <div class="link-column">
                    <h5>{{ __('messages.legal_terms') }}</h5>
                    <ul>
                        <li><a href="#">{{ __('messages.privacy_notice') }}</a></li>
                        <li><a href="#">{{ __('messages.terms_of_use') }}</a></li>
                        <li><a href="#">{{ __('messages.payment_policy') }}</a></li>
                    </ul>
                </div>
            @endif
        </div>

        <!-- Bottom bar -->
        <div class="footer-bottom">
            <div class="social-links">
                <span>{{ __('messages.follow_us') }}</span>
                <a href="#">TikTok</a>
                <a href="#">X</a>
                <a href="#">Facebook</a>
                <a href="#">Instagram</a>
                <a href="#">Pinterest</a>
            </div>
            <a href="/" style="font-family: var(--font-serif); font-size: 28px; color: #111; letter-spacing: 0.15em; text-decoration: none; position: relative; left: 0; transform: none; text-align: center;">Orlis</a>

            <!-- Language popup -->
            <div class="lang-popup-wrap" id="langPopupWrap">
                <div class="lang-popup-menu" id="langPopupMenu">
                    <a href="{{ route('lang.switch', 'vi') }}" data-no-swup class="lang-option {{ app()->getLocale() == 'vi' ? 'active' : '' }}">
                        <span>{{ __('messages.vietnamese') }}</span>
                        @if(app()->getLocale() == 'vi')<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>@endif
                    </a>
                    <a href="{{ route('lang.switch', 'en') }}" data-no-swup class="lang-option {{ app()->getLocale() == 'en' ? 'active' : '' }}">
                        <span>{{ __('messages.english') }}</span>
                        @if(app()->getLocale() == 'en')<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>@endif
                    </a>
                    <a href="{{ route('lang.switch', 'fr') }}" data-no-swup class="lang-option {{ app()->getLocale() == 'fr' ? 'active' : '' }}">
                        <span>{{ __('messages.french') }}</span>
                        @if(app()->getLocale() == 'fr')<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>@endif
                    </a>
                    <a href="{{ route('lang.switch', 'ja') }}" data-no-swup class="lang-option {{ app()->getLocale() == 'ja' ? 'active' : '' }}">
                        <span>{{ __('messages.japanese') }}</span>
                        @if(app()->getLocale() == 'ja')<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>@endif
                    </a>
                </div>
                <button class="lang-popup-trigger" id="langPopupTrigger" onclick="toggleLangPopup()" type="button">
                    {{ __('messages.choose_language') }}
                    <svg id="regionChevron" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" style="transition: transform 0.25s;"><path d="M6 9l6 6 6-6"/></svg>
                </button>
            </div>
        </div>

        <script>
            function toggleLangPopup() {
                const wrap = document.getElementById('langPopupWrap');
                const chevron = document.getElementById('regionChevron');
                const isOpen = wrap.classList.contains('open');
                if (isOpen) {
                    wrap.classList.remove('open');
                    chevron.style.transform = 'rotate(0deg)';
                } else {
                    wrap.classList.add('open');
                    chevron.style.transform = 'rotate(180deg)';
                }
            }
            // Close on outside click
            document.addEventListener('click', function(e) {
                const wrap = document.getElementById('langPopupWrap');
                if (wrap && !wrap.contains(e.target)) {
                    wrap.classList.remove('open');
                    const chevron = document.getElementById('regionChevron');
                    if (chevron) chevron.style.transform = 'rotate(0deg)';
                }
            });
        </script>
    </footer>
    @endunless

    <!-- Drawer Menu -->
    <div class="drawer-overlay" id="drawerOverlay" onclick="toggleDrawer()"></div>
    <div class="drawer" id="sideDrawer">
        <div class="drawer-viewport">
            
            <!-- MAIN MENU PANEL -->
            <div class="drawer-panel active" id="panel-main">
                <div class="drawer-header" onclick="toggleDrawer()">
                    <svg viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    {{ __('messages.close') }}
                </div>
                <div class="drawer-content">
                    @foreach($globalCategories as $index => $level1)
                        @php 
                            $isActive = (session('department', 'fashion') === 'beauty' && str_contains($level1->name, 'Nước hoa')) || 
                                        (session('department', 'fashion') === 'fashion' && str_contains($level1->name, 'Thời trang'));
                        @endphp
                        <ul class="menu-list {{ $isActive ? 'active' : '' }}" id="menu-{{ $level1->slug }}">
                            @if(str_contains(mb_strtolower($level1->name), 'thời trang'))
                                <li onclick="window.location='{{ route('magazine.index', ['department' => 'fashion']) }}'">{{ __('messages.news_events') }}</li>
                            @elseif(str_contains(mb_strtolower($level1->name), 'nước hoa'))
                                <li onclick="window.location='{{ route('magazine.index', ['department' => 'beauty']) }}'">{{ __('messages.news') }}</li>
                            @endif

                            @foreach($level1->children as $level2)
                                @if($level2->children->count() > 0)
                                    <li onclick="openMegaMenu('{{ $level2->slug }}')">
                                @else
                                    <li onclick="window.location='/catalog/{{ $level2->slug }}'">
                                @endif
                                    {{ $level2->translated_name }} 
                                    @if($level2->children->count() > 0)
                                        <svg viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
                                    @endif
                                </li>
                            @endforeach
                            <li style="margin-top: 35px; border-top: 1px solid #ddd; padding-top: 15px;"><a href="#" style="text-decoration: none; color: inherit; display: block; width: 100%;">{{ __('messages.contact_us') }}</a></li>
                            <li><a href="{{ route('appointments.create') }}" style="text-decoration: none; color: inherit; display: block; width: 100%;">{{ Lang::has('messages.book_appointment') ? __('messages.book_appointment') : 'Đặt lịch hẹn' }}</a></li>
                            <li><a href="{{ route('track-order') }}" style="text-decoration: none; color: inherit; display: block; width: 100%;">{{ __('messages.track_order') }}</a></li>
                            <li class="mobile-only-item" style="border-top: 1px solid #eee; margin-top: 5px; padding-top: 10px;">
                                @auth
                                    <a href="{{ route('customer.dashboard') }}" style="text-decoration: none; color: inherit; display: block; width: 100%;">{{ __('messages.my_account') }}</a>
                                @else
                                    <a href="#" onclick="toggleDrawer(); toggleLoginModal(event)" style="text-decoration: none; color: inherit; display: block; width: 100%;">{{ __('messages.login') }}</a>
                                @endauth
                            </li>
                            <li class="mobile-only-item">
                                <a href="{{ route('cart') }}" style="text-decoration: none; color: inherit; display: flex; justify-content: space-between; align-items: center; width: 100%;">
                                    {{ __('messages.cart') }}
                                </a>
                            </li>
                            <li style="display: flex; flex-direction: column; align-items: flex-start; cursor: pointer;" onclick="toggleDrawerLangMenu(event)">
                                <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                                    <span>{{ __('messages.choose_language') }}</span>
                                    <svg class="drawer-lang-chevron" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" style="transition: transform 0.25s;"><path d="M6 9l6 6 6-6"/></svg>
                                </div>
                                <div class="drawer-lang-options" style="display: none; padding-top: 10px; padding-left: 10px; flex-direction: column; gap: 8px; width: 100%;">
                                    <a href="{{ route('lang.switch', 'vi') }}" data-no-swup style="font-size: 13px; color: {{ app()->getLocale() == 'vi' ? '#111; font-weight:600;' : '#666;' }} text-decoration: none; padding: 4px 0; display: flex; align-items: center; justify-content: space-between;">
                                        <span>{{ __('messages.vietnamese') }}</span>
                                        @if(app()->getLocale() == 'vi')<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>@endif
                                    </a>
                                    <a href="{{ route('lang.switch', 'en') }}" data-no-swup style="font-size: 13px; color: {{ app()->getLocale() == 'en' ? '#111; font-weight:600;' : '#666;' }} text-decoration: none; padding: 4px 0; display: flex; align-items: center; justify-content: space-between;">
                                        <span>{{ __('messages.english') }}</span>
                                        @if(app()->getLocale() == 'en')<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>@endif
                                    </a>
                                    <a href="{{ route('lang.switch', 'fr') }}" data-no-swup style="font-size: 13px; color: {{ app()->getLocale() == 'fr' ? '#111; font-weight:600;' : '#666;' }} text-decoration: none; padding: 4px 0; display: flex; align-items: center; justify-content: space-between;">
                                        <span>{{ __('messages.french') }}</span>
                                        @if(app()->getLocale() == 'fr')<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>@endif
                                    </a>
                                    <a href="{{ route('lang.switch', 'ja') }}" data-no-swup style="font-size: 13px; color: {{ app()->getLocale() == 'ja' ? '#111; font-weight:600;' : '#666;' }} text-decoration: none; padding: 4px 0; display: flex; align-items: center; justify-content: space-between;">
                                        <span>{{ __('messages.japanese') }}</span>
                                        @if(app()->getLocale() == 'ja')<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>@endif
                                    </a>
                                </div>
                            </li>
                        </ul>
                    @endforeach
                </div>
                <div class="drawer-footer">
                    <div class="tab-wrapper">
                        @foreach($globalCategories as $index => $level1)
                            @php 
                                $isActive = (session('department', 'fashion') === 'beauty' && str_contains($level1->name, 'Nước hoa')) || 
                                            (session('department', 'fashion') === 'fashion' && str_contains($level1->name, 'Thời trang'));
                            @endphp
                            <div class="tab-btn {{ $isActive ? 'active' : '' }}" id="tab-{{ $level1->slug }}" onclick="switchTab('{{ $level1->slug }}')">
                                {{ $level1->translated_name }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- MEGA MENUS DYNAMIC -->
            @foreach($globalCategories as $level1)
                @foreach($level1->children as $level2)
                    @if($level2->children->count() > 0)
                    <div class="drawer-panel mega-panel" id="panel-{{ $level2->slug }}">
                        <div class="mega-sidebar">
                            <div class="drawer-header" onclick="toggleDrawer()">
                                <svg viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                                {{ __('messages.close') }}
                            </div>
                            <div class="mega-title" onclick="closeMegaMenu()">
                                <svg viewBox="0 0 24 24"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
                                {{ $level2->translated_name }}
                            </div>
                            <ul class="mega-list menu-list active">
                                <li><a href="/catalog/{{ $level2->slug }}" style="text-decoration:none; color:inherit; display:block;">{{ __('messages.explore') }} {{ $level2->translated_name }}</a></li>
                                @foreach($level2->children as $level3)
                                    <li><a href="/catalog/{{ $level3->slug }}" style="text-decoration:none; color:inherit; display:block;">{{ $level3->translated_name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="mega-content">
                            <div class="mega-grid">
                                @foreach($level2->children as $level3)
                                    <div class="mega-card" onclick="window.location='/catalog/{{ $level3->slug }}'" style="cursor: pointer;">
                                        <img src="{{ $level3->image ?? 'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $level3->translated_name }}">
                                        <span class="mega-label">{{ $level3->translated_name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            @endforeach

        </div>
    </div>



    @include('components.search-modal')
    @include('components.login-modal')
    @include('components.register-modal')

    <script src="https://unpkg.com/swup@4"></script>
    <script>
        function toggleDrawerLangMenu(e) {
            if (e) e.stopPropagation();
            const target = e.currentTarget;
            const options = target.querySelector('.drawer-lang-options');
            const chevron = target.querySelector('.drawer-lang-chevron');
            if (options) {
                const isOpen = options.style.display === 'flex';
                options.style.display = isOpen ? 'none' : 'flex';
                if (chevron) chevron.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (!window.swup) {
                window.swup = new Swup({
                    containers: ['#swup', '#sideDrawer', '#mainHeader'],
                    cache: false
                });
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
