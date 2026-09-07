@extends('layouts.admin')
@section('title', 'Tổng quan nghiệp vụ')

@section('page-style')
<style>
/* Reset & Fonts */
body {
    background-color: #fafafa;
    color: #333;
}
.font-serif {
    font-family: "Playfair Display", Georgia, serif;
}
.text-gold {
    color: #cda873;
}
.text-green {
    color: #1f7a54;
}
.bg-green-light {
    background-color: #e8f5e9;
}
.text-red {
    color: #d32f2f;
}
.bg-red-light {
    background-color: #fce4ec;
}

/* Banner */
.luxury-banner {
    background-color: #111;
    color: #fff;
    border-radius: 12px;
    padding: 40px;
    position: relative;
    overflow: hidden;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}
.banner-bg-text {
    position: absolute;
    right: -20px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 180px;
    font-family: "Playfair Display", serif;
    color: rgba(255,255,255,0.03);
    pointer-events: none;
    letter-spacing: 20px;
    z-index: 0;
}
.banner-content {
    position: relative;
    z-index: 1;
}
.banner-tag {
    display: inline-block;
    padding: 6px 16px;
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 20px;
    font-size: 12px;
    margin-bottom: 20px;
    color: #ccc;
    background: rgba(0,0,0,0.3);
}
.banner-title {
    font-size: 36px;
    font-family: "Playfair Display", serif;
    margin-bottom: 15px;
    font-weight: 400;
}
.banner-info {
    display: flex;
    gap: 30px;
    font-size: 13px;
    color: #999;
    margin-bottom: 30px;
}
.banner-info strong {
    color: #fff;
    font-weight: 500;
}
.banner-actions {
    display: flex;
    gap: 15px;
}
.btn-gold {
    background-color: #dcb382;
    color: #111;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 13px;
    cursor: pointer;
    transition: opacity 0.2s;
}
.btn-gold:hover {
    opacity: 0.9;
}
.btn-dark-outline {
    background-color: rgba(255,255,255,0.05);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.2);
    padding: 12px 24px;
    border-radius: 6px;
    font-size: 13px;
    cursor: pointer;
    transition: background 0.2s;
}
.btn-dark-outline:hover {
    background-color: rgba(255,255,255,0.1);
}

/* Section Titles */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}
.section-title {
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: #666;
    font-weight: 600;
}
.section-subtitle {
    font-size: 12px;
    color: #999;
    font-style: italic;
}

/* Stats Cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}
.stat-card {
    background: #fff;
    border-radius: 8px;
    border: 1px solid #eaeaea;
    padding: 24px;
    display: flex;
    flex-direction: column;
    box-shadow: 0 2px 10px rgba(0,0,0,0.02);
}
.stat-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
}
.stat-label {
    font-size: 12px;
    color: #666;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
    width: 70%;
}
.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.stat-value {
    font-family: "Playfair Display", serif;
    font-size: 28px;
    color: #111;
    margin-bottom: 20px;
    display: flex;
    align-items: baseline;
    gap: 5px;
}
.stat-unit {
    font-size: 14px;
    color: #cda873;
    font-family: "Playfair Display", serif;
}
.stat-footer {
    margin-top: auto;
    padding-top: 15px;
    border-top: 1px solid #f0f0f0;
    font-size: 11px;
    color: #888;
    display: flex;
    justify-content: space-between;
}

/* Task Card */
.task-card {
    background: #fff;
    border-radius: 8px;
    border: 1px solid #eaeaea;
    padding: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.task-label {
    font-size: 12px;
    color: #cda873;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 1px;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.task-label::before {
    content: '';
    display: block;
    width: 6px;
    height: 6px;
    background: #cda873;
    border-radius: 50%;
}
.task-title {
    font-family: "Playfair Display", serif;
    font-size: 22px;
    color: #111;
    margin-bottom: 15px;
}
.task-desc {
    font-size: 13px;
    color: #666;
    line-height: 1.6;
    max-width: 600px;
}
.btn-black {
    background: #111;
    color: #fff;
    border: none;
    padding: 14px 28px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: background 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-black:hover {
    background: #333;
    color: #fff;
}
</style>
@endsection

@section('content')
<div class="container-fluid" style="padding: 20px 40px;">
    
    <!-- Banner -->
    <div class="luxury-banner">
        <div class="banner-bg-text">ORLIS</div>
        <div class="banner-content">
            <div class="banner-tag">
                <svg style="width: 14px; height: 14px; vertical-align: text-bottom; margin-right: 4px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                Tiêu chuẩn giao vận bảo an 5 sao &bull; Maison Orlis
            </div>
            <h1 class="banner-title">Kính chào Quý Shipper Orlis,</h1>
            <div class="banner-info">
                <div>Tuyến phụ trách: <strong>{{ auth()->user()->shippingMethod ? auth()->user()->shippingMethod->name : 'Toàn bộ Tuyến Trung Tâm' }}</strong></div>
                <div>Trạng thái hòm đồ: <strong style="color: #4ade80;">Niêm phong an toàn</strong></div>
            </div>
            <div class="banner-actions">
                <button class="btn-gold">Bắt đầu ca giao hỏa tốc</button>
                <button class="btn-dark-outline">Xem bản đồ số lộ trình tối ưu</button>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="section-header">
        <div class="section-title">Chỉ số ca làm việc hôm nay</div>
        <div class="section-subtitle">Cập nhật theo thời gian thực ({{ now()->format('H:i A') }})</div>
    </div>

    <div class="stats-grid">
        <!-- Tiền Đang Giữ -->
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-label">Tiền đang giữ (COD)</div>
                <div class="stat-icon" style="background: #fdfaf3; color: #cda873; border: 1px solid #f5ebd9;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2"></rect><circle cx="12" cy="12" r="2"></circle><path d="M6 12h.01M18 12h.01"></path></svg>
                </div>
            </div>
            <div class="stat-value">
                {{ number_format($stats['cod_held'], 0, ',', '.') }}<span class="stat-unit">đ</span>
            </div>
            <div class="stat-footer">
                <span>Đối soát tự động</span>
                <span class="text-green">Đã kết toán ca trước</span>
            </div>
        </div>

        <!-- Đang Giao -->
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-label">Đơn hàng đang giao</div>
                <div class="stat-icon" style="background: #f0f7ff; color: #4096ff; border: 1px solid #d6e8ff;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                </div>
            </div>
            <div class="stat-value">
                {{ number_format($stats['shipping']) }}<span class="stat-unit">kiện</span>
            </div>
            <div class="stat-footer">
                <span>Hẹn phát: Trước 11:30</span>
                <span style="color: #d48806;">Cần ưu tiên</span>
            </div>
        </div>

        <!-- Thành công -->
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-label">Giao thành công</div>
                <div class="stat-icon bg-green-light text-green" style="border: 1px solid #c8e6c9;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
            </div>
            <div class="stat-value">
                {{ number_format($stats['delivered']) }}<span class="stat-unit">kiện</span>
            </div>
            <div class="stat-footer">
                <span>Tỷ lệ hoàn thành</span>
                <span class="text-green">100% đúng hẹn</span>
            </div>
        </div>

        <!-- Thất bại -->
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-label">Giao thất bại / Hoàn</div>
                <div class="stat-icon bg-red-light text-red" style="border: 1px solid #f8bbd0;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                </div>
            </div>
            <div class="stat-value">
                {{ number_format($stats['failed']) }}<span class="stat-unit">yêu cầu</span>
            </div>
            <div class="stat-footer">
                <span>Lý do phổ biến</span>
                <span class="text-red">Khách đổi giờ hẹn</span>
            </div>
        </div>
    </div>

    <!-- Task -->
    <div class="task-card">
        <div>
            <div class="task-label">Nhiệm vụ trực tiếp tiếp theo</div>
            <div class="task-title">Tiếp tục quy trình giao vận: Bạn có {{ str_pad($stats['shipping'], 2, '0', STR_PAD_LEFT) }} đơn hàng đang chờ giao tới khách hàng Privé</div>
            <div class="task-desc">Đơn hàng mang mã vận đơn <strong>#ORL-HẢI TRÌNH</strong> đã hoàn tất chuẩn bị tại Boutique Flagship. Hãy chuyển sang màn hình danh sách đơn hàng để kiểm tra niêm phong, số điện thoại khách hàng và cập nhật tiến độ chuyển phát.</div>
        </div>
        <div>
            <a href="{{ route('shipper.orders') }}" class="btn-black">
                Truy cập Danh sách Đơn hàng
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>
    </div>

</div>
@endsection
