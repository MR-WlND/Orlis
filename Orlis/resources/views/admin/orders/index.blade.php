@extends('layouts.admin')
@section('title', 'Quản lý Đơn Hàng')
@section('content')

@if(session('success'))
<div style="padding: 15px 20px; background: #fff; border: 1px solid #000; color: #000; margin-bottom: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">{{ session('success') }}</div>
@endif
@if(session('error'))
<div style="padding: 15px 20px; background: #fff; border: 1px solid #d93025; color: #d93025; margin-bottom: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">{{ session('error') }}</div>
@endif

<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <div class="header-text">
        <h2 class="page-title">Quản Lý Đơn Hàng</h2>
        <p class="page-subtitle">Theo dõi và xử lý toàn bộ đơn hàng trong hệ thống.</p>
    </div>
</div>

{{-- Stats --}}
<div class="stats-row" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 30px;">
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
        <div class="stat-value" style="color: #1890ff;">{{ number_format($stats['shipping']) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Doanh thu (đã giao)</div>
        <div class="stat-value">{{ number_format($stats['revenue'], 0, ',', '.') }}₫</div>
    </div>
</div>

{{-- Filter --}}
<form method="GET" action="{{ route('admin.orders.index') }}" style="display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; align-items: center;">
    <input type="text" name="search" class="form-control" placeholder="Mã đơn, tên, SĐT..." value="{{ request('search') }}" style="width: 240px; background: transparent;">
    <select name="status" class="form-control" style="width: 180px; background: transparent;">
        <option value="">-- Tất cả trạng thái --</option>
        @foreach($statuses as $key => $label)
            <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
        @endforeach
    </select>
    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" style="width: 150px; background: transparent;">
    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" style="width: 150px; background: transparent;">
    <button type="submit" class="btn-submit" style="width: auto; padding: 0 20px;">LỌC DỮ LIỆU</button>
    @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
        <a href="{{ route('admin.orders.index') }}" class="btn-cancel" style="padding: 0 20px; display: flex; align-items: center;">XÓA LỌC</a>
    @endif
</form>

{{-- Table --}}
<div class="table-container">
    <table class="luxury-table">
        <thead>
            <tr>
                <th>MÃ ĐƠN</th>
                <th>KHÁCH HÀNG</th>
                <th style="text-align: center;">SỐ LƯỢNG</th>
                <th style="text-align: right;">TỔNG TIỀN</th>
                <th style="text-align: center;">TRẠNG THÁI</th>
                <th style="text-align: right;">NGÀY ĐẶT</th>
                <th style="text-align: right;">THAO TÁC</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td><a href="{{ route('admin.orders.show', $order) }}" style="font-weight: 700; font-size: 13px; color: #111; font-family: monospace; text-decoration: none;">{{ $order->order_code }}</a></td>
                <td>
                    <div style="font-size: 13px; font-weight: 600; color: #111;">{{ $order->recipient_name }}</div>
                    @if($order->user)
                        <div style="font-size: 11px; color: #999;">{{ $order->user->email }}</div>
                    @endif
                </td>
                <td style="text-align: center; font-size: 13px; font-weight: 600;">{{ $order->items->sum('quantity') }}</td>
                <td style="text-align: right; font-weight: 700; font-size: 13px; font-family: var(--font-sans);">{{ number_format($order->grand_total, 0, ',', '.') }}₫</td>
                <td style="text-align: center;">
                    <span class="status-badge" style="color: {{ $order->status_color }}; background: {{ $order->status_color }}22; border: none;">
                        {{ $order->status_label }}
                    </span>
                </td>
                <td style="text-align: right; font-size: 12px; color: #999;">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td style="text-align: right; white-space: nowrap;">
                    <div style="display: flex; gap: 10px; justify-content: flex-end; align-items: center;">
                        <a href="{{ route('admin.orders.show', $order) }}" style="color: #666; font-size: 12px; font-weight: 600; text-decoration: none;">CHI TIẾT</a>
                        @if(in_array(auth('admin')->user()->role, ['admin', 'manager', 'staff']))
                            @if($order->order_status == 'pending')
                                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" style="margin: 0;">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="order_status" value="processing">
                                    <button type="submit" class="btn-submit" style="height: 28px; padding: 0 12px; font-size: 11px; cursor: pointer;">XÁC NHẬN</button>
                                </form>
                            @elseif($order->order_status == 'processing')
                                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" style="margin: 0;">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="order_status" value="shipping">
                                    <button type="submit" class="btn-submit" style="height: 28px; padding: 0 12px; font-size: 11px; cursor: pointer;">GIAO ĐVVC</button>
                                </form>
                            @endif
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px; color: #999;">Không có đơn hàng nào.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 20px;">
    {{ $orders->links('vendor.pagination.admin') }}
</div>

@endsection
