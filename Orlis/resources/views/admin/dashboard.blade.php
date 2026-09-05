@extends('layouts.admin')

@section('title', 'Tổng Quan')

@section('page-style')
<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }
    .card {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 20px 24px;
    }
    .card-title {
        font-size: 11px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 10px;
    }
    .card-value {
        font-family: var(--font-serif);
        font-size: 28px;
        font-weight: 600;
        color: var(--accent);
    }
    .card-trend {
        font-size: 12px;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .trend-up { color: #52c41a; }
    .trend-down { color: #f5222d; }
    .trend-neutral { color: var(--text-muted); }
    .bottom-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-top: 24px;
    }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { padding: 10px 0; text-align: left; border-bottom: 1px solid var(--border-color); font-size: 13px; }
    .table th { font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); font-weight: 600; }
    .table tbody tr:last-child td { border-bottom: none; }
    .status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; border-radius: 20px; font-size: 11px; font-weight: 600; }
    .chart-wrap { height: 220px; position: relative; }
    canvas { width: 100% !important; }
    .top-product { display: flex; gap: 12px; align-items: center; padding: 8px 0; border-bottom: 1px solid var(--border-color); }
    .top-product:last-child { border-bottom: none; }
    .top-product-img { width: 40px; height: 40px; border-radius: 4px; object-fit: cover; background: #f0f0f0; flex-shrink: 0; }
    .top-product-info { flex: 1; font-size: 13px; font-weight: 500; }
    .top-product-sold { font-size: 12px; color: var(--text-muted); }
    .top-product-rev { font-size: 13px; font-weight: 600; color: var(--accent); white-space: nowrap; }
</style>
@endsection

@section('content')

{{-- KPI Cards --}}
<div class="dashboard-grid">
    <div class="card">
        <div class="card-title">Doanh Thu Tháng Này</div>
        <div class="card-value">{{ number_format($revenueThisMonth, 0, ',', '.') }}₫</div>
        <div class="card-trend {{ $revenueChange >= 0 ? 'trend-up' : 'trend-down' }}">
            @if($revenueChange >= 0)
                <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline><polyline points="16 7 22 7 22 13"></polyline></svg>
                +{{ $revenueChange }}% so với tháng trước
            @else
                <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg>
                {{ $revenueChange }}% so với tháng trước
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-title">Đơn Hàng Tháng Này</div>
        <div class="card-value">{{ number_format($ordersThisMonth) }}</div>
        <div class="card-trend {{ $ordersChange >= 0 ? 'trend-up' : 'trend-down' }}">
            @if($ordersChange >= 0)
                <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline><polyline points="16 7 22 7 22 13"></polyline></svg>
                +{{ $ordersChange }}% so với tháng trước
            @else
                <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg>
                {{ $ordersChange }}% so với tháng trước
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-title">Khách Hàng Hoạt Động (30 ngày)</div>
        <div class="card-value">{{ number_format($activeCustomers) }}</div>
        <div class="card-trend {{ $customersChange >= 0 ? 'trend-up' : 'trend-down' }}">
            @if($customersChange >= 0)
                <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline><polyline points="16 7 22 7 22 13"></polyline></svg>
                +{{ $customersChange }}% so với tháng trước
            @else
                <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg>
                {{ $customersChange }}% so với tháng trước
            @endif
        </div>
    </div>
</div>

{{-- Revenue Chart --}}
<div class="card" style="margin-bottom: 24px;">
    <div class="card-title" style="margin-bottom: 16px;">Doanh Thu 7 Ngày Gần Nhất</div>
    <div class="chart-wrap">
        <canvas id="revenueChart"></canvas>
    </div>
</div>

{{-- Bottom Grid --}}
<div class="bottom-grid">
    {{-- Recent Orders --}}
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <div class="card-title" style="margin-bottom: 0;">Đơn Hàng Gần Nhất</div>
            <a href="{{ route('admin.orders.index') }}" style="font-size: 12px; color: var(--accent); text-decoration: none;">Xem tất cả →</a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                <tr>
                    <td><a href="{{ route('admin.orders.show', $order) }}" style="color: var(--accent); text-decoration: none; font-weight: 500;">{{ $order->order_code }}</a></td>
                    <td>{{ $order->recipient_name ?? ($order->user?->name ?? 'Khách vãng lai') }}</td>
                    <td style="font-weight: 600;">{{ number_format($order->grand_total, 0, ',', '.') }}₫</td>
                    <td>
                        <span class="status-badge" style="color: {{ $order->status_color }}; background: {{ $order->status_color }}22;">
                            {{ $order->status_label }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align: center; color: var(--text-muted); font-style: italic; padding: 20px 0;">Chưa có đơn hàng nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Top Products --}}
    <div class="card">
        <div class="card-title" style="margin-bottom: 16px;">Sản Phẩm Bán Chạy</div>
        @forelse($topProducts as $prod)
        <div class="top-product">
            @if($prod->thumbnail)
                <img class="top-product-img" src="{{ Storage::url($prod->thumbnail) }}" alt="{{ $prod->name }}">
            @else
                <div class="top-product-img" style="display:flex;align-items:center;justify-content:center;color:#ccc;font-size:18px;">🧴</div>
            @endif
            <div class="top-product-info">
                {{ Str::limit($prod->name, 30) }}
                <div class="top-product-sold">Đã bán: {{ number_format($prod->total_sold) }}</div>
            </div>
            <div class="top-product-rev">{{ number_format($prod->revenue, 0, ',', '.') }}₫</div>
        </div>
        @empty
        <p style="color: var(--text-muted); font-style: italic; font-size: 13px;">Chưa có dữ liệu.</p>
        @endforelse
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Doanh thu (₫)',
                data: @json($chartValues),
                borderColor: getComputedStyle(document.documentElement).getPropertyValue('--accent').trim() || '#8b6f47',
                backgroundColor: 'rgba(139,111,71,0.08)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#8b6f47',
                pointBorderWidth: 2,
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(ctx.raw)
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11 } } },
                y: {
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: {
                        font: { size: 11 },
                        callback: v => new Intl.NumberFormat('vi-VN', { notation: 'compact' }).format(v)
                    }
                }
            }
        }
    });
</script>
@endsection
