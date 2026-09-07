@extends('layouts.client')

@section('title')
    @yield('customer_title', 'Tài khoản – Orlis')
@endsection

@section('styles')
@yield('customer_styles')
@endsection

@section('hideFooter')@endsection

@section('content')
<div class="cust-wrap">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="cust-sidebar">
        @include('client.customer._sidebar')
    </aside>

    {{-- ===== MAIN ===== --}}
    <main class="cust-main">
        @if(session('success'))
        <div class="cust-alert cust-alert-success">
            <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="cust-alert cust-alert-error">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ session('error') }}
        </div>
        @endif

        @yield('customer_content')
    </main>

</div>

<style>
.cust-wrap {
    display: grid;
    grid-template-columns: 240px 1fr;
    min-height: 100vh;
    background: #fafaf8;
    padding-top: 60px;
}
.cust-sidebar {
    background: white;
    border-right: 1px solid #f0f0f0;
    position: sticky;
    top: 60px;
    height: calc(100vh - 60px);
    overflow-y: auto;
}
.cust-main {
    padding: 36px 44px 80px;
    min-width: 0;
}
.cust-alert {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 13px 16px;
    font-size: 13px;
    margin-bottom: 24px;
    border-radius: 2px;
}
.cust-alert svg { width: 15px; height: 15px; stroke: currentColor; fill: none; stroke-width: 2.5; flex-shrink: 0; }
.cust-alert-success { background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
.cust-alert-error   { background: #fff3f3; color: #c0392b; border: 1px solid #f5c6cb; }

@media (max-width: 900px) {
    .cust-wrap { grid-template-columns: 1fr; }
    .cust-sidebar { position: static; height: auto; }
    .cust-main { padding: 24px 20px 60px; }
}
</style>
@endsection
