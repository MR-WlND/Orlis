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
    <h2 style="font-family: var(--font-serif); font-size: 22px;">Quản lý Lịch Hẹn</h2>
</div>

<div class="stats-row">
    <div class="stat-card"><div class="stat-label">Tổng lịch hẹn</div><div class="stat-value">{{ number_format($stats['total']) }}</div></div>
    <div class="stat-card"><div class="stat-label">Chờ xác nhận</div><div class="stat-value" style="color:#faad14;">{{ number_format($stats['pending']) }}</div></div>
    <div class="stat-card"><div class="stat-label">Hôm nay</div><div class="stat-value" style="color:#1890ff;">{{ number_format($stats['today']) }}</div></div>
    <div class="stat-card"><div class="stat-label">Đã xác nhận</div><div class="stat-value" style="color:#52c41a;">{{ number_format($stats['confirmed']) }}</div></div>
</div>

<form method="GET" action="{{ route('admin.appointments.index') }}" class="filter-bar">
    <input type="text" name="search" placeholder="Mã hẹn, tên khách..." value="{{ request('search') }}" style="min-width:200px;">
    <select name="status">
        <option value="">-- Tất cả trạng thái --</option>
        @foreach($statuses as $key => $label)
            <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
        @endforeach
    </select>
    <input type="date" name="date" value="{{ request('date') }}" placeholder="Ngày hẹn">
    <button type="submit" class="btn btn-primary">Lọc</button>
    @if(request()->hasAny(['search', 'status', 'date']))
        <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline">Xóa lọc</a>
    @endif
</form>

<table class="table">
    <thead>
        <tr>
            <th>Mã hẹn</th>
            <th>Khách hàng</th>
            <th>Showroom</th>
            <th>Ngày & Giờ</th>
            <th>Nhân viên</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @forelse($appointments as $apt)
        <tr>
            <td style="font-weight:600;color:var(--accent);">{{ $apt->appointment_code }}</td>
            <td>
                <div>{{ $apt->user?->name }}</div>
                <div style="font-size:11px;color:var(--text-muted);">{{ $apt->user?->phone }}</div>
            </td>
            <td>{{ $apt->store?->name }}</td>
            <td>
                <div>{{ \Carbon\Carbon::parse($apt->appointment_date)->format('d/m/Y') }}</div>
                <div style="font-size:11px;color:var(--text-muted);">{{ $apt->time_slot }}</div>
            </td>
            <td>{{ $apt->staff?->name ?? '—' }}</td>
            <td>
                @php $color = $statusColors[$apt->status] ?? '#999'; @endphp
                <span class="status-badge" style="color:{{ $color }};background:{{ $color }}22;">
                    {{ $statuses[$apt->status] ?? $apt->status }}
                </span>
            </td>
            <td>
                <a href="{{ route('admin.appointments.show', $apt) }}" class="btn btn-sm btn-outline">Chi tiết</a>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted);font-style:italic;">Không có lịch hẹn nào.</td></tr>
        @endforelse
    </tbody>
</table>

<div style="display:flex;justify-content:space-between;align-items:center;margin-top:20px;">
    <div style="font-size:12px;color:var(--text-muted);">{{ $appointments->firstItem() ?? 0 }}–{{ $appointments->lastItem() ?? 0 }} / {{ $appointments->total() }}</div>
    <div style="display:flex;gap:8px;">
        @if(!$appointments->onFirstPage())<a href="{{ $appointments->previousPageUrl() }}" class="btn btn-outline" style="font-size:11px;padding:6px 12px;">← Trước</a>@endif
        @if($appointments->hasMorePages())<a href="{{ $appointments->nextPageUrl() }}" class="btn btn-outline" style="font-size:11px;padding:6px 12px;">Sau →</a>@endif
    </div>
</div>
@endsection
