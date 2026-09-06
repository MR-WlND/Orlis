@extends('layouts.client')

@section('title')
    @yield('customer_title', 'Tài khoản - Orlis')
@endsection

@section('styles')
<style>
    .customer-wrap { max-width: 1200px; margin: 0 auto; padding: 80px 20px 60px; display: grid; grid-template-columns: 260px 1fr; gap: 40px; }
    .sidebar-nav { position: sticky; top: 90px; height: fit-content; background: #fff; padding: 20px 0; }
    .sidebar-nav .user-info { display: flex; align-items: center; gap: 15px; padding-bottom: 30px; border-bottom: 1px solid #eee; margin-bottom: 20px; }
    .avatar-circle { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; background: #111; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 18px; color: #fff; overflow: hidden; }
    .user-name { font-weight: 600; font-size: 14px; margin-bottom: 4px; }
    .user-level { font-size: 12px; color: #666; font-weight: 500; }
    
    .nav-links-wrapper { display: flex; flex-direction: column; gap: 4px; }
    .nav-link { display: flex; align-items: center; gap: 12px; padding: 14px 20px; border-radius: 0; text-decoration: none; color: #555; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; transition: all 0.2s; }
    .nav-link:hover:not(.active) { background: #f9f9f9; color: #111; }
    .nav-link.active { background: #111; color: #fff; }
    .nav-link svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 1.5; flex-shrink: 0; }
    
    /* Global Customer UI utilities */
    .section-header { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 25px; border-bottom: 1px solid #eee; padding-bottom: 15px; }
    .section-title { font-family: var(--font-serif); font-size: 22px; font-weight: 400; color: #111; margin: 0; }
    .section-header .subtitle { font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: #888; }
    .btn-link { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #111; text-decoration: none; border-bottom: 1px solid #111; padding-bottom: 2px; }
    .btn-dark { background: #111; color: #fff; padding: 12px 24px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; text-decoration: none; font-weight: 600; transition: 0.3s; border: none; cursor: pointer; display: inline-block; }
    .btn-dark:hover { background: #333; }
    .btn-outline { border: 1px solid #ddd; padding: 8px 16px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #333; text-decoration: none; transition: 0.3s; background: transparent; cursor: pointer; display: inline-block; }
    .btn-outline:hover { border-color: #111; background: #111; color: #fff; }
    
    @media(max-width: 768px) { .customer-wrap { grid-template-columns: 1fr; } }
</style>
@yield('customer_styles')
@endsection

@section('content')
<div style="background: #fff; min-height: 100vh;">
<div class="customer-wrap">

    {{-- Sidebar Nav --}}
    @include('client.customer._sidebar')

    {{-- Content --}}
    <div>
        @if(session('success'))
            <div style="background:#d4edda;color:#155724;padding:12px 16px;border-radius:0;margin-bottom:20px;font-size:13px; border-left: 3px solid #28a745;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div style="background:#f8d7da;color:#721c24;padding:12px 16px;border-radius:0;margin-bottom:20px;font-size:13px; border-left: 3px solid #dc3545;">{{ session('error') }}</div>
        @endif

        @yield('customer_content')
    </div>

</div>
</div>
@endsection
