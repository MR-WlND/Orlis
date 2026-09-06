@extends('layouts.admin')

@section('title', 'Quản lý Đơn Hàng')

@section('page-style')

@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

<div class="page-header">
    <h2 style="font-family: var(--font-serif); font-size: 22px;">Quản lý Đơn Hàng</h2>
</div>

{{-- Stats --}}
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-label">Tổng đơn hàng</div>
        <div class="stat-value">{{ number_format($stats['total']) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Chờ xác nhận</div>
        <div class="stat-value" style="color: #faad14;">{{ number_format($stats['pending']) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Đang giao</div>
        <div class="stat-value" style="color: #13c2c2;">{{ number_format($stats['shipping']) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Doanh thu (đã giao)</div>
        <div class="stat-value">{{ number_format($stats['revenue'], 0, ',', '.') }}₫</div>
    </div>
</div>

{{-- Filter --}}
<form method="GET" action="{{ route('admin.orders.index') }}" class="filter-bar">
    <input type="text" name="search" placeholder="Mã đơn, tên, SĐT..." value="{{ request('search') }}" style="min-width: 220px;">
    <select name="status">
        <option value="">-- Tất cả trạng thái --</option>
        @foreach($statuses as $key => $label)
            <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
        @endforeach
    </select>
    <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="Từ ngày">
    <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="Đến ngày">
    <button type="submit" class="btn btn-primary">Lọc</button>
    @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline">Xóa lọc</a>
    @endif
</form>

{{-- Table --}}
<table class="table">
    <thead>
        <tr>
            <th>Mã đơn</th>
            <th>Khách hàng</th>
            <th>Số lượng</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
            <th>Ngày đặt</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $order)
        <tr>
            <td>
                <a href="{{ route('admin.orders.show', $order) }}" style="font-weight: 600; color: var(--accent); text-decoration: none;">
                    {{ $order->order_code }}
                </a>
            </td>
            <td>
                <div>{{ $order->recipient_name }}</div>
                @if($order->user)
                    <div style="font-size: 11px; color: var(--text-muted);">{{ $order->user->email }}</div>
                @endif
            </td>
            <td>{{ $order->items->sum('quantity') }} sản phẩm</td>
            <td style="font-weight: 600;">{{ number_format($order->grand_total, 0, ',', '.') }}₫</td>
            <td>
                <span class="status-badge" style="color: {{ $order->status_color }}; background: {{ $order->status_color }}22;">
                    <span class="status-dot"></span>
                    {{ $order->status_label }}
                </span>
            </td>
            <td style="font-size: 12px; color: var(--text-muted);">{{ $order->created_at->format('d/m/Y H:i') }}</td>
            <td>
                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline">Chi tiết</a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="text-align: center; padding: 40px; color: var(--text-muted); font-style: italic;">
                Không có đơn hàng nào.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="pagination-container">
    <div class="pagination-info">
        Hiển thị {{ $orders->firstItem() ?? 0 }} – {{ $orders->lastItem() ?? 0 }} / {{ $orders->total() }} đơn hàng
    </div>
    <div class="pagination-buttons">
        @if($orders->onFirstPage())
            <button class="btn-page" disabled style="opacity:.4; cursor:not-allowed;">← Trang trước</button>
        @else
            <a href="{{ $orders->previousPageUrl() }}" class="btn-page">← Trang trước</a>
        @endif
        @if($orders->hasMorePages())
            <a href="{{ $orders->nextPageUrl() }}" class="btn-page">Trang sau →</a>
        @else
            <button class="btn-page" disabled style="opacity:.4; cursor:not-allowed;">Trang sau →</button>
        @endif
    </div>
</div>

@endsection
