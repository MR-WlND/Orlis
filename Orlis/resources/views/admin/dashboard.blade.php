@extends('layouts.admin')

@section('title', 'Tổng Quan')

@section('page-style')
<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        margin-bottom: 24px;
    }

    .card {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 24px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }

    .card-title {
        font-size: 13px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 10px;
    }

    .card-value {
        font-family: var(--font-serif);
        font-size: 32px;
        font-weight: 600;
        color: var(--accent);
    }

    .card-trend {
        font-size: 13px;
        margin-top: 10px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .trend-up { color: #52c41a; }
    .trend-down { color: #f5222d; }

    .chart-placeholder {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        font-family: var(--font-serif);
        font-style: italic;
    }
</style>
@endsection

@section('content')
<div class="dashboard-grid">
    <div class="card">
        <div class="card-title">Tổng Doanh Thu</div>
        <div class="card-value">$124,500</div>
        <div class="card-trend trend-up">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline><polyline points="16 7 22 7 22 13"></polyline></svg>
            +12.5% so với tháng trước
        </div>
    </div>
    
    <div class="card">
        <div class="card-title">Đơn Hàng</div>
        <div class="card-value">1,432</div>
        <div class="card-trend trend-up">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline><polyline points="16 7 22 7 22 13"></polyline></svg>
            +5.2% so với tháng trước
        </div>
    </div>

    <div class="card">
        <div class="card-title">Khách Hàng Hoạt Động</div>
        <div class="card-value">856</div>
        <div class="card-trend trend-down">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg>
            -2.1% so với tháng trước
        </div>
    </div>
</div>

<div class="chart-placeholder">
    Biểu Đồ Doanh Số (Minh họa)
</div>
@endsection
