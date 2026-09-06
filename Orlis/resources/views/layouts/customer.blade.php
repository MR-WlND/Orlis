@extends('layouts.client')

@section('title')
    @yield('customer_title', 'Tài khoản - Orlis')
@endsection

@section('styles')
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
