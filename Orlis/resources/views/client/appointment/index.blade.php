@extends('layouts.client')
@section('title', 'Lịch hẹn của tôi - Orlis')
@section('styles')
<style>
    .customer-wrap { max-width: 1060px; margin: 0 auto; padding: 100px 20px 60px; display: grid; grid-template-columns: 220px 1fr; gap: 32px; }
    .sidebar-nav { position: sticky; top: 90px; height: fit-content; }
    .sidebar-nav .user-info { padding-bottom: 20px; border-bottom: 1px solid #e8e8e8; margin-bottom: 16px; }
    .avatar-circle { width: 56px; height: 56px; border-radius: 50%; background: #f0ece6; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 20px; color: var(--primary); margin-bottom: 10px; overflow: hidden; }
    .user-name { font-weight: 600; font-size: 15px; margin-bottom: 2px; }
    .user-level { font-size: 12px; color: #999; }
    .nav-link { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 6px; text-decoration: none; color: #555; font-size: 14px; transition: all 0.15s; margin-bottom: 2px; }
    .nav-link:hover, .nav-link.active { background: #f5f0ea; color: var(--primary); font-weight: 500; }
    .nav-link svg { width: 16px; height: 16px; stroke: currentColor; fill: none; stroke-width: 2; flex-shrink: 0; }
    .section-title { font-family: var(--font-serif); font-size: 18px; font-weight: 500; margin-bottom: 18px; }
    .apt-card { background: white; border-radius: 10px; border: 1px solid #efefef; margin-bottom: 14px; overflow: hidden; }
    .apt-card-head { display: grid; grid-template-columns: 1fr auto; gap: 12px; align-items: center; padding: 16px 20px; border-bottom: 1px solid #f5f5f5; }
    .apt-code { font-weight: 700; font-size: 13px; color: var(--primary); letter-spacing: 0.5px; }
    .apt-date { font-size: 13px; color: #666; margin-top: 3px; }
    .apt-body { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; padding: 14px 20px; font-size: 13px; }
    .apt-field-label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #aaa; margin-bottom: 4px; }
    .apt-field-value { font-weight: 500; color: #333; }
    .apt-foot { display: flex; justify-content: flex-end; padding: 10px 20px; gap: 10px; }
    .status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; }
    .btn-outline-sm { padding: 6px 16px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 12px; font-weight: 500; text-decoration: none; color: #444; cursor: pointer; background: transparent; transition: all 0.15s; }
    .btn-outline-sm:hover { border-color: var(--primary); color: var(--primary); }
    .btn-danger-sm { padding: 6px 16px; border: 1px solid #f5c6c6; border-radius: 4px; font-size: 12px; font-weight: 500; color: #c0392b; background: transparent; cursor: pointer; transition: all 0.15s; }
    .btn-danger-sm:hover { background: #c0392b; color: white; border-color: #c0392b; }
    .btn-primary-sm { display: inline-block; padding: 10px 22px; background: #333; color: white; border-radius: 6px; font-size: 13px; font-weight: 600; text-decoration: none; }
    .service-labels { 'consultation': 'Tư vấn Nước hoa', 'trial': 'Thử Mùi Hương', 'vip_service': 'VIP Experience' }
    @media(max-width: 768px) { .customer-wrap { grid-template-columns: 1fr; } .sidebar-nav { position: static; } .apt-body { grid-template-columns: 1fr 1fr; } }
</style>
@endsection
@section('content')
@php
$serviceLabels = ['consultation' => 'Tư vấn Nước hoa', 'trial' => 'Thử Mùi Hương', 'vip_service' => 'VIP Experience ✨'];
$statusColors = ['pending' => '#faad14', 'confirmed' => '#1890ff', 'cancelled' => '#f5222d', 'completed' => '#52c41a'];
$statusLabels = ['pending' => 'Chờ xác nhận', 'confirmed' => 'Đã xác nhận', 'cancelled' => 'Đã hủy', 'completed' => 'Hoàn thành'];
@endphp
<div style="background: #f8f7f5; min-height: 100vh;">
<div class="customer-wrap">
    @include('client.customer._sidebar')
    <div>
        @if(session('success'))
            <div style="background:#d4edda;color:#155724;padding:12px 16px;border-radius:6px;margin-bottom:20px;font-size:13px;">{{ session('success') }}</div>
        @endif

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <h2 class="section-title" style="margin-bottom:0;">Lịch hẹn của tôi</h2>
            <a href="{{ route('appointments.create') }}" class="btn-primary-sm">+ Đặt lịch mới</a>
        </div>

        @forelse($appointments as $apt)
        <div class="apt-card">
            <div class="apt-card-head">
                <div>
                    <div class="apt-code">{{ $apt->appointment_code }}</div>
                    <div class="apt-date">
                        Ngày {{ \Carbon\Carbon::parse($apt->appointment_date)->format('d/m/Y') }} — {{ $apt->time_slot }}
                    </div>
                </div>
                @php $color = $statusColors[$apt->status] ?? '#999'; @endphp
                <span class="status-badge" style="color:{{ $color }};background:{{ $color }}22;">
                    {{ $statusLabels[$apt->status] ?? $apt->status }}
                </span>
            </div>
            <div class="apt-body">
                <div>
                    <div class="apt-field-label">Showroom</div>
                    <div class="apt-field-value">{{ $apt->store?->name }}</div>
                </div>
                <div>
                    <div class="apt-field-label">Dịch vụ</div>
                    <div class="apt-field-value">{{ $serviceLabels[$apt->service_type] ?? $apt->service_type }}</div>
                </div>
                <div>
                    <div class="apt-field-label">Đặt lúc</div>
                    <div class="apt-field-value">{{ $apt->created_at->format('d/m/Y') }}</div>
                </div>
            </div>
            @if(in_array($apt->status, ['pending', 'confirmed']))
            <div class="apt-foot">
                <form method="POST" action="{{ route('appointments.cancel', $apt) }}" onsubmit="return confirm('Bạn chắc chắn muốn hủy lịch hẹn này?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-danger-sm">Hủy lịch hẹn</button>
                </form>
            </div>
            @endif
        </div>
        @empty
        <div style="text-align:center;padding:60px 0;color:#aaa;font-size:14px;">
            <p style="font-style:italic;margin-bottom:16px;">Bạn chưa có lịch hẹn nào.</p>
            <a href="{{ route('appointments.create') }}" class="btn-primary-sm">Đặt lịch hẹn ngay</a>
        </div>
        @endforelse

        @if($appointments->hasPages())
        <div style="display:flex;justify-content:center;gap:8px;margin-top:20px;">
            @if(!$appointments->onFirstPage())
                <a href="{{ $appointments->previousPageUrl() }}" style="padding:8px 14px;border:1px solid #e0e0e0;border-radius:4px;font-size:12px;text-decoration:none;color:#555;">← Trước</a>
            @endif
            @if($appointments->hasMorePages())
                <a href="{{ $appointments->nextPageUrl() }}" style="padding:8px 14px;border:1px solid #e0e0e0;border-radius:4px;font-size:12px;text-decoration:none;color:#555;">Sau →</a>
            @endif
        </div>
        @endif
    </div>
</div>
</div>
@endsection
