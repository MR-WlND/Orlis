@extends('layouts.admin')
@section('title', 'Quản lý Lịch Hẹn')
@section('page-style')

@endsection
@section('content')
@php
$statusColors = ['pending' => '#faad14', 'confirmed' => '#1890ff', 'cancelled' => '#f5222d', 'completed' => '#52c41a'];
@endphp

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-error">{{ session('error') }}</div>@endif

<div class="page-header">
    <div class="header-text">
        <h2 class="page-title">Quản Lý Lịch Hẹn</h2>
        <p class="page-subtitle">Theo dõi và phân bổ nhân sự cho các lịch hẹn với khách hàng.</p>
    </div>
</div>

<div class="stats-row" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
    <div class="form-card" style="padding: 20px; text-align: center;">
        <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; color: #888; margin-bottom: 10px;">TỔNG LỊCH HẸN</div>
        <div style="font-family: var(--font-serif); font-size: 28px; color: #111;">{{ number_format($stats['total']) }}</div>
    </div>
    <div class="form-card" style="padding: 20px; text-align: center;">
        <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; color: #888; margin-bottom: 10px;">CHỜ XÁC NHẬN</div>
        <div style="font-family: var(--font-serif); font-size: 28px; color: #d93025;">{{ number_format($stats['pending']) }}</div>
    </div>
    <div class="form-card" style="padding: 20px; text-align: center;">
        <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; color: #888; margin-bottom: 10px;">HÔM NAY</div>
        <div style="font-family: var(--font-serif); font-size: 28px; color: #111;">{{ number_format($stats['today']) }}</div>
    </div>
    <div class="form-card" style="padding: 20px; text-align: center;">
        <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; color: #888; margin-bottom: 10px;">ĐÃ XÁC NHẬN</div>
        <div style="font-family: var(--font-serif); font-size: 28px; color: #111;">{{ number_format($stats['confirmed']) }}</div>
    </div>
</div>

<form method="GET" action="{{ route('admin.appointments.index') }}" style="display: flex; gap: 10px; margin-bottom: 20px;">
    <input type="text" name="search" class="form-control" placeholder="Mã hẹn, tên khách..." value="{{ request('search') }}" style="width: 250px; background: transparent;">
    
    <select name="store_id" class="form-control" style="width: 200px; background: transparent;">
        <option value="">-- Tất cả cửa hàng --</option>
        @foreach($stores as $store)
            <option value="{{ $store->id }}" @selected(request('store_id') == $store->id)>{{ $store->name }}</option>
        @endforeach
    </select>

    <select name="status" class="form-control" style="width: 150px; background: transparent;">
        <option value="">-- Tất cả trạng thái --</option>
        @foreach($statuses as $key => $label)
            <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
        @endforeach
    </select>
    <input type="date" name="date" class="form-control" value="{{ request('date') }}" style="width: 150px; background: transparent;">
    <button type="submit" class="btn-submit" style="width: auto; padding: 0 20px;">LỌC DỮ LIỆU</button>
    @if(request()->hasAny(['search', 'store_id', 'status', 'date']))
        <a href="{{ route('admin.appointments.index') }}" class="btn-cancel" style="padding: 0 20px; display: flex; align-items: center;">XÓA LỌC</a>
    @endif
</form>

<div class="table-container">
    <table class="luxury-table">
        <thead>
            <tr>
                <th>MÃ HẸN</th>
                <th>KHÁCH HÀNG</th>
                <th>SHOWROOM</th>
                <th>NGÀY & GIỜ</th>
                <th>NHÂN VIÊN</th>
                <th>TRẠNG THÁI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($appointments as $apt)
            <tr onclick="window.location.href='{{ route('admin.appointments.show', $apt) }}'" style="cursor: pointer;" onmouseover="this.style.backgroundColor='#fafafa'" onmouseout="this.style.backgroundColor='transparent'">
                <td><span style="font-size: 13px; font-weight: 600;">{{ $apt->appointment_code }}</span></td>
                <td>
                    <div style="font-weight: 500; color: #111;">{{ $apt->user?->name }}</div>
                    <div style="font-size: 11px; color: #666;">{{ $apt->user?->phone }}</div>
                </td>
                <td style="font-size: 13px;">{{ $apt->store?->name }}</td>
                <td>
                    <div style="font-weight: 500; color: #111;">{{ \Carbon\Carbon::parse($apt->appointment_date)->format('d/m/Y') }}</div>
                    <div style="font-size: 11px; color: #666;">{{ $apt->time_slot }}</div>
                </td>
                <td style="font-size: 13px;">{{ $apt->staff?->name ?? '—' }}</td>
                <td>
                    @if($apt->status == 'pending') <span class="status-badge" style="background: #fff; color: #d93025; border-color: #d93025;">{{ $statuses[$apt->status] ?? $apt->status }}</span>
                    @elseif($apt->status == 'confirmed') <span class="status-badge">{{ $statuses[$apt->status] ?? $apt->status }}</span>
                    @else <span class="status-badge inactive">{{ $statuses[$apt->status] ?? $apt->status }}</span> @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px; color: #999;">Không có lịch hẹn nào.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($appointments->hasPages())
<div style="margin-top: 20px;">
    {{ $appointments->links('vendor.pagination.admin') }}
</div>
@endif
@endsection
