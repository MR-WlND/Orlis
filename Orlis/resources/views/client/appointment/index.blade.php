@extends('layouts.customer')
@section('customer_title', 'Lịch hẹn của tôi - Orlis')
@section('customer_styles')
<style>
    .apt-card { background: white; border: 1px solid #eee; margin-bottom: 20px; transition: 0.3s; }
    .apt-card:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.03); border-color: #ddd; }
    .apt-card-head { display: grid; grid-template-columns: 1fr auto; gap: 15px; align-items: center; padding: 20px 25px; border-bottom: 1px solid #f9f9f9; }
    .apt-code { font-weight: 600; font-size: 14px; color: #111; letter-spacing: 0.5px; }
    .apt-date { font-size: 12px; color: #888; margin-top: 5px; }
    .apt-body { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; padding: 25px; font-size: 13px; }
    .apt-field-label { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #888; margin-bottom: 6px; }
    .apt-field-value { font-weight: 500; color: #111; }
    .apt-foot { display: flex; justify-content: flex-end; padding: 15px 25px; gap: 10px; background: #fbfbfb; border-top: 1px solid #f9f9f9; }
    .status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 6px 14px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
    .btn-danger-sm { padding: 8px 16px; border: 1px solid #f5c6c6; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; color: #c0392b; background: transparent; cursor: pointer; transition: 0.3s; }
    .btn-danger-sm:hover { background: #c0392b; color: white; border-color: #c0392b; }
    .btn-primary-sm { display: inline-block; padding: 12px 25px; background: #111; color: white; border: none; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; text-decoration: none; transition: 0.3s; cursor: pointer; }
    .btn-primary-sm:hover { background: #333; }
    @media(max-width: 768px) { .apt-body { grid-template-columns: 1fr; } }
</style>
@endsection
@section('customer_content')
@php
$serviceLabels = ['consultation' => 'Tư vấn Nước hoa', 'trial' => 'Thử Mùi Hương', 'vip_service' => 'VIP Experience ✨'];
$statusColors = ['pending' => '#faad14', 'confirmed' => '#1890ff', 'cancelled' => '#f5222d', 'completed' => '#52c41a'];
$statusLabels = ['pending' => 'Chờ xác nhận', 'confirmed' => 'Đã xác nhận', 'cancelled' => 'Đã hủy', 'completed' => 'Hoàn thành'];
@endphp

<div class="section-header">
    <div>
        <div class="subtitle">QUẢN LÝ LỊCH HẸN</div>
        <h2 class="section-title" style="margin-bottom:0;">Lịch hẹn của tôi</h2>
    </div>
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
        <span class="status-badge" style="color:{{ $color }};background:{{ $color }}15;border: 1px solid {{ $color }}33;">
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
        <form method="POST" action="{{ route('appointments.cancel', $apt) }}" onsubmit="return confirm('Quý khách chắc chắn muốn hủy lịch hẹn này?')">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn-danger-sm">Hủy lịch hẹn</button>
        </form>
    </div>
    @endif
</div>
@empty
<div style="text-align:center;padding:80px 20px;background:#fbfbfb;border:1px solid #eee;">
    <svg class="empty-icon" viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="#ccc" stroke-width="1.5" style="margin: 0 auto 20px; display: block;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
    <div class="empty-text" style="font-size: 14px; color: #555; margin-bottom: 25px; font-style: italic; line-height: 1.6;">Quý khách chưa có lịch hẹn nào.</div>
    <a href="{{ route('appointments.create') }}" class="btn-primary-sm" style="text-transform: none; letter-spacing: normal;">Đặt lịch hẹn ngay</a>
</div>
@endforelse

@if($appointments->hasPages())
<div style="display:flex;justify-content:center;gap:10px;margin-top:40px;">
    @if(!$appointments->onFirstPage())
        <a href="{{ $appointments->previousPageUrl() }}" style="padding:10px 18px;border:1px solid #ddd;font-size:11px;text-transform:uppercase;letter-spacing:1px;text-decoration:none;color:#555;transition:0.3s;" onmouseover="this.style.background='#111';this.style.color='white';" onmouseout="this.style.background='transparent';this.style.color='#555';">← Trước</a>
    @endif
    @if($appointments->hasMorePages())
        <a href="{{ $appointments->nextPageUrl() }}" style="padding:10px 18px;border:1px solid #ddd;font-size:11px;text-transform:uppercase;letter-spacing:1px;text-decoration:none;color:#555;transition:0.3s;" onmouseover="this.style.background='#111';this.style.color='white';" onmouseout="this.style.background='transparent';this.style.color='#555';">Sau →</a>
    @endif
</div>
@endif
@endsection
