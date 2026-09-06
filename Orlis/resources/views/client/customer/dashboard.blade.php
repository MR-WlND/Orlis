@extends('layouts.customer')

@section('customer_title', 'Tổng quan độc quyền - Orlis')

@section('customer_styles')
<style>
    /* VIP Card */
    .vip-card { background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%); color: #fff; padding: 40px; border-radius: 4px; margin-bottom: 50px; position: relative; overflow: hidden; }
    .vip-card::after { content: ''; position: absolute; top: 0; right: 0; width: 300px; height: 100%; background: radial-gradient(circle, rgba(255,215,0,0.1) 0%, transparent 70%); }
    .vip-top { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 10px; text-transform: uppercase; letter-spacing: 1.5px; color: #d4af37; }
    .vip-title { font-family: var(--font-serif); font-size: 28px; font-weight: 400; margin-bottom: 40px; }
    .vip-info-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px; }
    .vip-info-item .label { font-size: 11px; color: #999; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 1px; }
    .vip-info-item .value { font-size: 16px; font-weight: 500; }
    
    .vip-progress-box { background: rgba(255,255,255,0.05); padding: 20px; border-radius: 4px; }
    .progress-title { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #d4af37; margin-bottom: 15px; }
    .progress-steps { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .step-item { border-left: 2px solid #444; padding-left: 15px; }
    .step-item.active { border-color: #d4af37; }
    .step-title { font-size: 12px; font-weight: 600; margin-bottom: 4px; }
    .step-desc { font-size: 11px; color: #888; }
    
    /* Advisor Card */
    .advisor-box { display: flex; align-items: center; gap: 30px; background: #fbfbfb; border: 1px solid #eee; padding: 30px; margin-bottom: 50px; }
    .advisor-img { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; }
    .advisor-content { flex: 1; }
    .advisor-name { font-family: var(--font-serif); font-size: 20px; font-weight: 500; margin-bottom: 5px; }
    .advisor-role { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #666; margin-bottom: 10px; }
    .advisor-quote { font-size: 13px; font-style: italic; color: #555; }
    .advisor-actions { display: flex; gap: 15px; }
    
    /* Product Cards */
    .product-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 50px; }
    .product-card { border: 1px solid #eee; padding: 15px; transition: 0.3s; }
    .product-card:hover { box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
    .product-img { width: 100%; height: 200px; object-fit: contain; margin-bottom: 15px; background: #f8f8f8; }
    .product-cat { font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: #888; margin-bottom: 5px; }
    .product-name { font-size: 14px; font-weight: 600; margin-bottom: 10px; min-height: 40px; }
    .product-bot { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #eee; padding-top: 15px; margin-top: 15px; }
    .product-price { font-weight: 600; font-size: 13px; }
    
    /* Services Grid */
    .service-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 50px; }
    .service-card { border: 1px solid #eee; padding: 30px; text-align: left; }
    .service-icon { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border: 1px solid #eee; border-radius: 50%; margin-bottom: 20px; color: #111; }
    .service-title { font-family: var(--font-serif); font-size: 16px; font-weight: 500; margin-bottom: 15px; }
    .service-text { font-size: 13px; color: #666; margin-bottom: 25px; line-height: 1.5; }
    
    /* Orders Empty */
    .orders-empty { text-align: center; padding: 60px 20px; background: #fbfbfb; border: 1px solid #eee; }
    .empty-icon { width: 48px; height: 48px; margin: 0 auto 20px; color: #ccc; }
    .empty-text { font-size: 14px; color: #555; margin-bottom: 25px; font-style: italic; line-height: 1.6; }
    
    @media(max-width: 768px) { .vip-info-grid, .progress-steps, .product-grid, .service-grid { grid-template-columns: 1fr; } .advisor-box { flex-direction: column; text-align: center; } .advisor-actions { width: 100%; flex-direction: column; } }
</style>
@endsection

@section('customer_content')
<div class="dashboard-header">
    <div>
        <div class="breadcrumb">TRANG CHỦ / HƯỚNG DẪN KHÁCH HÀNG ORLIS</div>
        <h2 class="welcome-title">Kính chào, {{ $user->name }}</h2>
        <div class="welcome-subtext">Chào mừng Quý khách đến với hệ thống quản lý tài khoản và hạng thẻ thành viên độc quyền của chúng tôi.</div>
    </div>
</div>

{{-- VIP Card --}}
<div class="vip-card">
    <div class="vip-top">
        <span>ORLIS HAUTE PARFUMERIE & SIGNATURE | PRIVÉ LOUUNGE</span>
        <span>MÃ HỘI VIÊN THƯƠNG HIỆU<br>005 - 8921356</span>
    </div>
    <h3 class="vip-title">Thẻ Hội Viên Orlis Privé Étoile</h3>
    
    <div class="vip-info-grid">
        <div class="vip-info-item">
            <div class="label">Mã số hội viên</div>
            <div class="value">{{ $user->name }}</div>
            <div class="label" style="font-size:9px;margin-top:4px;">Orlis Privé Étoile</div>
        </div>
        <div class="vip-info-item">
            <div class="label">Điểm tích lũy hiện tại</div>
            <div class="value">4.500 Privé Points</div>
            <div class="label" style="font-size:9px;margin-top:4px;">Hạng thành viên: Étoile</div>
        </div>
        <div class="vip-info-item">
            <div class="label">Cấp bậc hiện tại</div>
            <div class="value" style="color:#d4af37;">Privé Étoile (Quý Khách Hàng)</div>
            <div class="label" style="font-size:9px;margin-top:4px;">Mốc điểm kế tiếp: 10.000 điểm</div>
        </div>
    </div>
    
    <div class="vip-progress-box">
        <div class="progress-title">ĐẶC QUYỀN VÀ CỘT MỐC HẠNG HỘI VIÊN</div>
        <div class="progress-steps">
            <div class="step-item active">
                <div class="step-title">Vận chuyển Hỏa Tốc</div>
                <div class="step-desc">Miễn phí giao hàng chuẩn Orlis trên toàn quốc</div>
            </div>
            <div class="step-item">
                <div class="step-title">Quà Tặng Sinh Nhật</div>
                <div class="step-desc">Nhận quà tặng độc quyền vào tháng sinh nhật của Quý khách</div>
            </div>
            <div class="step-item">
                <div class="step-title">Trải Nghiệm Trước</div>
                <div class="step-desc">Cơ hội đặt trước các sản phẩm giới hạn & bộ sưu tập mới</div>
            </div>
        </div>
    </div>
</div>

{{-- Advisor --}}
<div class="section-header" style="border:none; margin-bottom:10px;">
    <div class="subtitle">DỊCH VỤ CHĂM SÓC KHÁCH HÀNG THƯƠNG HIỆU</div>
</div>
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <h3 class="section-title">Cố Vấn Phong Cách & Haute Parfumerie Riêng</h3>
    <a href="#" class="btn-outline" style="background:#fff;border-color:#eee;color:#111;">+ THÊM CỐ VẤN</a>
</div>

<div class="advisor-box">
    <img src="https://i.pravatar.cc/150?img=47" alt="Advisor" class="advisor-img">
    <div class="advisor-content">
        <div class="advisor-name">Hélène Dubois</div>
        <div class="advisor-role">CỐ VẤN HAUTE PARFUMERIE & PHONG CÁCH CÁ NHÂN</div>
        <div class="advisor-quote">"Kính chào Quý khách. Tôi rất hân hạnh được đồng hành và tư vấn những mùi hương phù hợp nhất với phong cách và cá tính riêng của Quý khách trong từng trải nghiệm tại Orlis."</div>
    </div>
    <div class="advisor-actions">
        <a href="#" class="btn-dark"><svg style="width:14px;height:14px;vertical-align:middle;margin-right:6px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>NHẮN TIN CỐ VẤN</a>
        <a href="#" class="btn-outline" style="padding: 12px 24px; font-weight:600;">ĐẶT HẸN TẠI BOUTIQUE</a>
    </div>
</div>

{{-- Recommendations --}}
<div class="section-header">
    <div>
        <div class="subtitle">GỢI Ý MUA SẮM</div>
        <h3 class="section-title">Tuyển Chọn Riêng Cho Quý Khách</h3>
    </div>
    <a href="{{ route('catalog') }}" class="btn-link">KHÁM PHÁ BỘ SƯU TẬP →</a>
</div>

<div class="product-grid">
    {{-- Product 1 --}}
    <div class="product-card">
        <div class="product-cat" style="background:#111;color:#fff;display:inline-block;padding:3px 8px;font-size:9px;margin-bottom:10px;">NƯỚC HOA NỮ</div>
        <img src="https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=400&q=80" alt="Perfume" class="product-img">
        <div class="product-cat">HAUTE PARFUMERIE • 75 ML</div>
        <div class="product-name">L'Or Impérial Flacon d'Art</div>
        <div style="font-size:12px;color:#666;margin-bottom:15px;line-height:1.4;">Hương thơm sang trọng, quý phái vương giả với hoa hồng và trầm hương.</div>
        <div class="product-bot">
            <span class="product-price">9.800.000 ₫</span>
            <a href="#" class="btn-link" style="border:none;font-weight:600;">CHI TIẾT →</a>
        </div>
    </div>
    {{-- Product 2 --}}
    <div class="product-card">
        <div class="product-cat" style="background:#d4af37;color:#fff;display:inline-block;padding:3px 8px;font-size:9px;margin-bottom:10px;">PHỤ KIỆN CAO CẤP</div>
        <img src="https://images.unsplash.com/photo-1584916201218-f4242ceb4809?auto=format&fit=crop&w=400&q=80" alt="Bag" class="product-img">
        <div class="product-cat">MAROQUINERIE BOUTIQUE</div>
        <div class="product-name">Ví Cầm Tay Da Bê Họa Tiết Étoile</div>
        <div style="font-size:12px;color:#666;margin-bottom:15px;line-height:1.4;">Chế tác thủ công từ da bê cao cấp, điểm nhấn khóa logo đính đá thanh lịch.</div>
        <div class="product-bot">
            <span class="product-price">24.500.000 ₫</span>
            <a href="#" class="btn-link" style="border:none;font-weight:600;">CHI TIẾT →</a>
        </div>
    </div>
    {{-- Product 3 --}}
    <div class="product-card">
        <div class="product-cat" style="background:#111;color:#fff;display:inline-block;padding:3px 8px;font-size:9px;margin-bottom:10px;">NƯỚC HOA TRONG NHÀ</div>
        <img src="https://images.unsplash.com/photo-1602928321679-560bb453f190?auto=format&fit=crop&w=400&q=80" alt="Candle" class="product-img">
        <div class="product-cat">ART DE VIVRE • 250 G</div>
        <div class="product-name">Nến Thơm Gốm Sứ Nuit D'Ambre</div>
        <div style="font-size:12px;color:#666;margin-bottom:15px;line-height:1.4;">Sự ấm áp của hổ phách và gỗ tuyết tùng mang lại không gian thư giãn.</div>
        <div class="product-bot">
            <span class="product-price">3.900.000 ₫</span>
            <a href="#" class="btn-link" style="border:none;font-weight:600;">CHI TIẾT →</a>
        </div>
    </div>
</div>

{{-- Services --}}
<div class="section-header">
    <div>
        <div class="subtitle">NGHỆ THUẬT PHỤC VỤ</div>
        <h3 class="section-title">Dịch Vụ & Tiện Ích Độc Quyền</h3>
    </div>
</div>

<div class="service-grid">
    <div class="service-card">
        <div class="service-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg></div>
        <h4 class="service-title">Khắc Tên & Đóng Gói Quà Couture</h4>
        <div class="service-text">Nhận dấu ấn cá nhân mang đậm phong cách Pháp lên các sản phẩm nước hoa của Quý khách hoàn toàn miễn phí.</div>
        <a href="#" class="btn-link">KHÁM PHÁ DỊCH VỤ →</a>
    </div>
    <div class="service-card">
        <div class="service-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg></div>
        <h4 class="service-title">Bảo Dưỡng & Phục Hồi Sản Phẩm</h4>
        <div class="service-text">Đặc quyền làm sạch phụ kiện da, kiểm tra vòi xịt nước hoa cho các Quý Khách Hàng mua sắm tại Orlis.</div>
        <a href="#" class="btn-link">YÊU CẦU BẢO DƯỠNG →</a>
    </div>
    <div class="service-card">
        <div class="service-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg></div>
        <h4 class="service-title">Bộ Mẫu Thử Haute Parfumerie Theo Mùa</h4>
        <div class="service-text">Nhận bộ Discovery Set bản giới hạn của mùa mới nhất được tuyển chọn riêng cho phong cách của Quý khách.</div>
        <a href="#" class="btn-link">CHỌN BỘ MẪU THỬ CỦA BẠN →</a>
    </div>
</div>

{{-- Orders --}}
<div class="section-header">
    <div>
        <div class="subtitle">NHẬT KÝ MUA SẮM</div>
        <h3 class="section-title">Đơn Hàng Gần Nhất</h3>
    </div>
    <a href="{{ route('customer.orders') }}" class="btn-link">XEM LỊCH SỬ ĐƠN HÀNG →</a>
</div>

<div class="orders-empty">
    <svg class="empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 01-8 0"></path></svg>
    <div class="empty-text">Quý khách hiện chưa có đơn hàng nào đang chờ xử lý.<br>Hãy khám phá bộ sưu tập mới nhất để bổ sung vào bộ sưu tập cá nhân của Quý khách.</div>
    <a href="{{ route('catalog') }}" class="btn-dark">KHÁM PHÁ SẢN PHẨM MỚI NHẤT</a>
</div>
@endsection
