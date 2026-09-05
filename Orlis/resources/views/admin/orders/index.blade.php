@extends('layouts.admin')

@section('title', 'Quản lý Đơn Hàng')

@section('page-style')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 16px 20px;
    }
    .stat-label { font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }
    .stat-value { font-family: var(--font-serif); font-size: 24px; font-weight: 600; color: var(--accent); margin-top: 4px; }
    .filter-bar {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
        align-items: center;
    }
    .filter-bar input, .filter-bar select {
        padding: 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        font-size: 13px;
        background: var(--bg-card);
        color: var(--text-primary);
    }
    .btn { padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 500; cursor: pointer; border: none; }
    .btn-primary { background-color: var(--accent); color: white; }
    .btn-sm { padding: 5px 10px; font-size: 12px; }
    .btn-outline { background: transparent; border: 1px solid var(--border-color); color: var(--text-primary); }
    .table { width: 100%; border-collapse: collapse; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; }
    .table th, .table td { padding: 12px 16px; text-align: left; border-bottom: 1px solid var(--border-color); font-size: 13px; }
    .table th { font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); background: var(--bg-card); }
    .table tbody tr:last-child td { border-bottom: none; }
    .table tbody tr:hover { background: rgba(0,0,0,0.02); }
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.3px;
    }
    .status-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .pagination-container { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; }
    .pagination-info { font-size: 12px; color: var(--text-muted); }
    .pagination-buttons { display: flex; gap: 8px; }
    .btn-page { padding: 8px 14px; font-size: 11px; font-weight: 600; color: var(--text-primary); text-transform: uppercase; letter-spacing: 1px; border: 1px solid var(--border-color); background: var(--bg-card); cursor: pointer; transition: all 0.2s; text-decoration: none; border-radius: 4px; }
    .btn-page:hover { background: var(--accent); color: white; border-color: var(--accent); }
    .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; font-size: 13px; }
    .alert-success { background: #d4edda; color: #155724; }
    .alert-error { background: #f8d7da; color: #721c24; }
</style>
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
