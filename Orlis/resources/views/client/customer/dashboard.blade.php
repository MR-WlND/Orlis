@extends('layouts.customer')

@section('customer_title', 'Tổng Quan – Orlis Privé')

@section('customer_content')

{{-- Breadcrumb + Title --}}
<div class="db-breadcrumb">{{ __('messages.home_customer_guide') }}</div>
<h1 class="db-page-title">{{ __('messages.greetings') }} <em>{{ $user->name }}</em></h1>
<p class="db-page-subtitle">{{ __('messages.dashboard_welcome_msg') }}</p>

{{-- ===== MOBILE USER INFO (visible only on mobile) ===== --}}
<div class="db-mobile-user-card">
    <div class="db-mu-avatar">
        @if($user->avatar)
            <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}">
        @else
            {{ strtoupper(substr($user->name, 0, 2)) }}
        @endif
    </div>
    <div class="db-mu-info">
        <div class="db-mu-name">{{ $user->name }}</div>
        <div class="db-mu-id">#{{ str_pad($user->id, 9, '0', STR_PAD_LEFT) }}</div>
    </div>
    <div class="db-mu-arrow">
        <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"></polyline></svg>
    </div>
</div>



{{-- ===== VIP CARD ===== --}}
@php
    $membershipLabel = \App\Models\User::MEMBERSHIPS[$user->membership_level ?? 'classic'] ?? 'Classic';
    $loyaltyPoints = $user->loyalty_points ?? 0;
    $nextTier = match($user->membership_level ?? 'classic') {
        'classic' => ['label' => 'Silver', 'target' => 2000],
        'silver'  => ['label' => 'Gold',   'target' => 5000],
        'gold'    => ['label' => 'Diamond','target' => 10000],
        default   => ['label' => '–',      'target' => 0],
    };
    $progress = $nextTier['target'] > 0 ? min(100, round($loyaltyPoints / $nextTier['target'] * 100)) : 100;
@endphp
<div class="db-vip-card">
    <div class="db-vip-band">
        <span>{{ __('messages.orlis_prive_lounge') }}</span>
        <span>{{ __('messages.brand_membership_code') }}<br><strong>{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}-{{ $user->id * 1234567 % 9000000 + 1000000 }}</strong></span>
    </div>

    <h2 class="db-vip-title">{{ __('messages.orlis_prive_membership_card') }} {{ $membershipLabel }}</h2>

    <div class="db-vip-grid">
        <div class="db-vip-item">
            <div class="db-vip-lbl">Tên hội viên</div>
            <div class="db-vip-val">{{ $user->name }}</div>
            <div class="db-vip-sub">Orlis Privé {{ $membershipLabel }}</div>
        </div>
        <div class="db-vip-item">
            <div class="db-vip-lbl">Điểm tích lũy hiện tại</div>
            <div class="db-vip-val">{{ number_format($loyaltyPoints) }} Privé Points</div>
            <div class="db-vip-sub">Hạng thành viên: {{ $membershipLabel }}</div>
        </div>
        <div class="db-vip-item">
            <div class="db-vip-lbl">Cấp bậc hiện tại</div>
            <div class="db-vip-val db-vip-gold">Privé {{ $membershipLabel }}</div>
            @if($nextTier['target'] > 0)
            <div class="db-vip-sub">Mốc điểm kế tiếp: {{ number_format($nextTier['target']) }} điểm</div>
            @else
            <div class="db-vip-sub">Đỉnh cao hội viên</div>
            @endif
        </div>
    </div>

    <div class="db-vip-progress-box">
        <div class="db-vip-ptitle">ĐẶC QUYỀN VÀ CỘT MỐC HẠNG HỘI VIÊN</div>
        <div class="db-vip-steps">
            <div class="db-vip-step db-vip-step-active">
                <div class="db-vstep-title">Vận chuyển Hỏa Tốc</div>
                <div class="db-vstep-desc">Miễn phí giao hàng chuẩn Orlis trên toàn quốc</div>
            </div>
            <div class="db-vip-step">
                <div class="db-vstep-title">Quà Tặng Sinh Nhật</div>
                <div class="db-vstep-desc">Nhận quà tặng độc quyền vào tháng sinh nhật của Quý khách</div>
            </div>
            <div class="db-vip-step">
                <div class="db-vstep-title">Trải Nghiệm Trước</div>
                <div class="db-vstep-desc">Cơ hội đặt trước các sản phẩm giới hạn & bộ sưu tập mới</div>
            </div>
        </div>
    </div>
</div>

{{-- ===== ADVISOR ===== --}}
<div class="db-section-sup">DỊCH VỤ CHĂM SÓC KHÁCH HÀNG THƯƠNG HIỆU</div>
<div class="db-section-head">
    <h2 class="db-section-title">Cố Vấn Phong Cách & Haute Parfumerie Riêng</h2>
</div>

@if($advisor)
<div class="db-advisor">
    <div class="db-advisor-avatar">
        @if($advisor->avatar)
            <img src="{{ Storage::url($advisor->avatar) }}" alt="{{ $advisor->name }}">
        @else
            {{ strtoupper(substr($advisor->name, 0, 2)) }}
        @endif
    </div>
    <div class="db-advisor-body">
        <div class="db-advisor-name">{{ $advisor->name }}</div>
        <div class="db-advisor-role">
            {{ \App\Models\Admin::ROLES[$advisor->role] ?? $advisor->role }} &mdash; Haute Parfumerie & Phong Cách Cá Nhân
        </div>
        <p class="db-advisor-quote">"Kính chào Quý khách. Tôi rất hân hạnh được đồng hành và tư vấn những mùi hương phù hợp nhất với phong cách và cá tính riêng của Quý khách trong từng trải nghiệm tại Orlis."</p>
    </div>
    <div class="db-advisor-actions">
        <a href="{{ route('tickets.create') }}" class="db-btn-dark">
            <svg viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
            NHẮN TIN CỐ VẤN
        </a>
        <a href="{{ route('appointments.create') }}" class="db-btn-outline">ĐẶT HẸN TẠI BOUTIQUE</a>
    </div>
</div>
@else
<div class="db-advisor db-advisor-empty">
    <div class="db-advisor-body">
        <div class="db-advisor-name">Chưa có cố vấn được chỉ định</div>
        <p class="db-advisor-quote">Đặt lịch hẹn tại Boutique để được phân công cố vấn phong cách cá nhân riêng cho Quý khách.</p>
    </div>
    <div class="db-advisor-actions">
        <a href="{{ route('appointments.create') }}" class="db-btn-dark">ĐẶT HẸN NGAY</a>
    </div>
</div>
@endif

{{-- ===== RECOMMENDATIONS ===== --}}
<div class="db-section-sep"></div>
<div class="db-section-sup">GỢI Ý MUA SẮM</div>
<div class="db-section-head">
    <h2 class="db-section-title">Tuyển Chọn Riêng Cho Quý Khách</h2>
    <a href="{{ route('catalog') }}" class="db-btn-link">KHÁM PHÁ BỘ SƯU TẬP →</a>
</div>
<div class="db-product-grid">
    <div class="db-product-card">
        <span class="db-product-tag" style="background:#111;color:#fff;">NƯỚC HOA NỮ</span>
        <img src="https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=400&q=80" alt="Perfume" class="db-product-img">
        <div class="db-product-cat">HAUTE PARFUMERIE • 75 ML</div>
        <div class="db-product-name">L'Or Impérial Flacon d'Art</div>
        <p class="db-product-desc">Hương thơm sang trọng, quý phái vương giả với hoa hồng và trầm hương.</p>
        <div class="db-product-bot">
            <span class="db-product-price">9.800.000 ₫</span>
            <a href="{{ route('catalog') }}" class="db-btn-link" style="border:none;">CHI TIẾT →</a>
        </div>
    </div>
    <div class="db-product-card">
        <span class="db-product-tag" style="background:#c8a97e;color:#fff;">PHỤ KIỆN CAO CẤP</span>
        <img src="https://images.unsplash.com/photo-1584916201218-f4242ceb4809?auto=format&fit=crop&w=400&q=80" alt="Bag" class="db-product-img">
        <div class="db-product-cat">MAROQUINERIE BOUTIQUE</div>
        <div class="db-product-name">Ví Cầm Tay Da Bê Họa Tiết Étoile</div>
        <p class="db-product-desc">Chế tác thủ công từ da bê cao cấp, điểm nhấn khóa logo đính đá thanh lịch.</p>
        <div class="db-product-bot">
            <span class="db-product-price">24.500.000 ₫</span>
            <a href="{{ route('catalog') }}" class="db-btn-link" style="border:none;">CHI TIẾT →</a>
        </div>
    </div>
    <div class="db-product-card">
        <span class="db-product-tag" style="background:#111;color:#fff;">NƯỚC HOA TRONG NHÀ</span>
        <img src="https://images.unsplash.com/photo-1602928321679-560bb453f190?auto=format&fit=crop&w=400&q=80" alt="Candle" class="db-product-img">
        <div class="db-product-cat">ART DE VIVRE • 250 G</div>
        <div class="db-product-name">Nến Thơm Gốm Sứ Nuit D'Ambre</div>
        <p class="db-product-desc">Sự ấm áp của hổ phách và gỗ tuyết tùng mang lại không gian thư giãn.</p>
        <div class="db-product-bot">
            <span class="db-product-price">3.900.000 ₫</span>
            <a href="{{ route('catalog') }}" class="db-btn-link" style="border:none;">CHI TIẾT →</a>
        </div>
    </div>
</div>

{{-- ===== SERVICES ===== --}}
<div class="db-section-sep"></div>
<div class="db-section-sup">NGHỆ THUẬT PHỤC VỤ</div>
<div class="db-section-head">
    <h2 class="db-section-title">Dịch Vụ & Tiện Ích Độc Quyền</h2>
</div>
<div class="db-service-grid">
    <div class="db-service-card">
        <div class="db-service-icon">
            <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        </div>
        <h3 class="db-service-title">Khắc Tên & {{ __('messages.closed_status') }} Gói Quà Couture</h3>
        <p class="db-service-desc">Nhận dấu ấn cá nhân mang đậm phong cách Pháp lên các sản phẩm nước hoa của Quý khách hoàn toàn miễn phí.</p>
        <a href="{{ route('tickets.create') }}" class="db-btn-link">KHÁM PHÁ DỊCH VỤ →</a>
    </div>
    <div class="db-service-card">
        <div class="db-service-icon">
            <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <h3 class="db-service-title">Bảo Dưỡng & Phục Hồi Sản Phẩm</h3>
        <p class="db-service-desc">Đặc quyền làm sạch phụ kiện da, kiểm tra vòi xịt nước hoa cho các Quý Khách Hàng mua sắm tại Orlis.</p>
        <a href="{{ route('tickets.create') }}" class="db-btn-link">YÊU CẦU BẢO DƯỠNG →</a>
    </div>
    <div class="db-service-card">
        <div class="db-service-icon">
            <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
        </div>
        <h3 class="db-service-title">Bộ Mẫu Thử Haute Parfumerie Theo Mùa</h3>
        <p class="db-service-desc">Nhận bộ Discovery Set bản giới hạn của mùa mới nhất được tuyển chọn riêng cho phong cách của Quý khách.</p>
        <a href="{{ route('catalog') }}" class="db-btn-link">CHỌN BỘ MẪU THỬ CỦA BẠN →</a>
    </div>
</div>

{{-- ===== RECENT ORDERS ===== --}}
<div class="db-section-sep"></div>
<div class="db-section-sup">NHẬT KÝ MUA SẮM</div>
<div class="db-section-head">
    <h2 class="db-section-title">Đơn Hàng Gần Nhất</h2>
    <a href="{{ route('customer.orders') }}" class="db-btn-link">XEM LỊCH SỬ ĐƠN HÀNG →</a>
</div>

@if($recentOrders->count() > 0)
<div class="db-orders-list">
    @foreach($recentOrders as $order)
    @php
        $statusColors = [
            'pending'    => ['bg'=>'#fffbf0','color'=>'#d4940a'],
            'confirmed'  => ['bg'=>'#e8f4ff','color'=>'#1565c0'],
            'processing' => ['bg'=>'#f3e5ff','color'=>'#7b1fa2'],
            'shipping'   => ['bg'=>'#e0f7fa','color'=>'#00796b'],
            'delivered'  => ['bg'=>'#e8f5e9','color'=>'#2e7d32'],
            'cancelled'  => ['bg'=>'#fff3f3','color'=>'#c0392b'],
        ];
        $sc = $statusColors[$order->order_status] ?? ['bg'=>'#f5f5f5','color'=>'#777'];
    @endphp
    <div class="db-order-row">
        <div class="db-order-info">
            <a href="{{ route('customer.orders') }}" class="db-order-code">{{ $order->order_code }}</a>
            <div class="db-order-date">{{ $order->created_at->format('d/m/Y') }}</div>
        </div>
        <div class="db-order-status" style="background:{{ $sc['bg'] }};color:{{ $sc['color'] }};">
            {{ $order->status_label }}
        </div>
        <div class="db-order-total">{{ number_format($order->grand_total, 0, ',', '.') }}₫</div>
        <a href="{{ route('customer.orders') }}" class="db-btn-link" style="font-size:10px;">XEM CHI TIẾT →</a>
    </div>
    @endforeach
</div>
@else
<div class="db-orders-empty">
    <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
    <p>Quý khách hiện chưa có đơn hàng nào đang chờ xử lý.<br>Hãy khám phá bộ sưu tập mới nhất để bổ sung vào bộ sưu tập cá nhân.</p>
    <a href="{{ route('catalog') }}" class="db-btn-dark">KHÁM PHÁ SẢN PHẨM MỚI NHẤT</a>
</div>
@endif

<style>
/* ===== DASHBOARD ===== */
.db-breadcrumb {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 1.5px;
    color: #bbb;
    margin-bottom: 8px;
}
.db-page-title {
    font-family: var(--font-serif, Georgia, serif);
    font-size: 26px;
    font-weight: 400;
    color: #111;
    margin: 0 0 32px;
}
.db-page-title em { font-style: normal; }

/* VIP Card */
.db-vip-card {
    background: linear-gradient(135deg, #1a1a1a 0%, #2c2520 60%, #1a1a1a 100%);
    color: #fff;
    padding: 36px 40px;
    margin-bottom: 44px;
    position: relative;
    overflow: hidden;
}
.db-vip-card::after {
    content: '';
    position: absolute;
    top: 0; right: 0;
    width: 280px; height: 100%;
    background: radial-gradient(circle, rgba(200,169,126,0.12) 0%, transparent 70%);
    pointer-events: none;
}
.db-vip-band {
    display: flex;
    justify-content: space-between;
    font-size: 9px;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: #c8a97e;
    margin-bottom: 12px;
    line-height: 1.5;
}
.db-vip-title {
    font-family: var(--font-serif, Georgia, serif);
    font-size: 26px;
    font-weight: 400;
    margin: 0 0 32px;
    color: #fff;
}
.db-vip-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    margin-bottom: 32px;
}
.db-vip-lbl { font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: #888; margin-bottom: 6px; }
.db-vip-val { font-size: 15px; font-weight: 500; color: #fff; margin-bottom: 4px; }
.db-vip-sub { font-size: 10px; color: #666; }
.db-vip-gold { color: #c8a97e !important; }

.db-vip-progress-box {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.07);
    padding: 20px 24px;
}
.db-vip-ptitle { font-size: 9px; text-transform: uppercase; letter-spacing: 1.5px; color: #c8a97e; margin-bottom: 16px; }
.db-vip-steps { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
.db-vip-step { border-left: 2px solid #333; padding-left: 14px; }
.db-vip-step-active { border-color: #c8a97e; }
.db-vstep-title { font-size: 12px; font-weight: 600; color: #ddd; margin-bottom: 4px; }
.db-vstep-desc  { font-size: 11px; color: #666; line-height: 1.5; }

/* Section utilities */
.db-section-sep { height: 1px; background: #efefef; margin: 40px 0 32px; }
.db-section-sup { font-size: 9px; font-weight: 700; letter-spacing: 1.5px; color: #bbb; margin-bottom: 10px; }
.db-section-head {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    margin-bottom: 24px;
}
.db-section-title {
    font-family: var(--font-serif, Georgia, serif);
    font-size: 21px;
    font-weight: 400;
    color: #111;
    margin: 0;
}

/* Buttons */
.db-btn-link {
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    color: #111;
    text-decoration: none;
    border-bottom: 1px solid #111;
    padding-bottom: 1px;
    transition: color 0.15s;
    white-space: nowrap;
}
.db-btn-link:hover { color: #c8a97e; border-color: #c8a97e; }
.db-btn-dark {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 11px 22px;
    background: #111;
    color: white;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.8px;
    text-decoration: none;
    text-transform: uppercase;
    transition: background 0.2s;
    border-radius: 2px;
    border: none;
    cursor: pointer;
}
.db-btn-dark svg { width: 13px; height: 13px; stroke: white; fill: none; stroke-width: 2; }
.db-btn-dark:hover { background: #333; }
.db-btn-outline {
    display: inline-block;
    padding: 9px 18px;
    border: 1px solid #ddd;
    color: #555;
    font-size: 10.5px;
    font-weight: 600;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    text-decoration: none;
    transition: all 0.2s;
    border-radius: 2px;
}
.db-btn-outline:hover { background: #111; color: white; border-color: #111; }

/* Advisor */
.db-advisor {
    display: flex;
    align-items: center;
    gap: 28px;
    background: #fafaf8;
    border: 1px solid #eee;
    padding: 28px;
    margin-bottom: 0;
}
.db-advisor-avatar {
    width: 72px; height: 72px;
    border-radius: 50%;
    background: #111;
    color: white;
    font-size: 20px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
    letter-spacing: 1px;
}
.db-advisor-avatar img {
    width: 100%; height: 100%;
    object-fit: cover;
}
.db-advisor-body { flex: 1; }
.db-advisor-name {
    font-family: var(--font-serif, Georgia, serif);
    font-size: 18px; font-weight: 500;
    color: #111; margin-bottom: 3px;
}
.db-advisor-role {
    font-size: 9.5px; text-transform: uppercase;
    letter-spacing: 1px; color: #999; margin-bottom: 8px;
}
.db-advisor-quote { font-size: 12.5px; font-style: italic; color: #666; line-height: 1.6; margin: 0; }
.db-advisor-actions { display: flex; flex-direction: column; gap: 10px; flex-shrink: 0; }
.db-advisor-empty { opacity: 0.7; }

/* Products */
.db-product-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}
.db-product-card { border: 1px solid #ebebeb; padding: 16px; background: white; transition: box-shadow 0.2s; }
.db-product-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.06); }
.db-product-tag {
    display: inline-block;
    font-size: 8.5px;
    font-weight: 700;
    letter-spacing: 0.8px;
    padding: 3px 8px;
    margin-bottom: 12px;
    border-radius: 1px;
}
.db-product-img { width: 100%; height: 180px; object-fit: cover; background: #f8f8f6; margin-bottom: 12px; display: block; }
.db-product-cat { font-size: 9.5px; text-transform: uppercase; letter-spacing: 1px; color: #aaa; margin-bottom: 5px; }
.db-product-name { font-size: 13.5px; font-weight: 600; color: #111; margin-bottom: 8px; min-height: 36px; line-height: 1.35; }
.db-product-desc { font-size: 11.5px; color: #888; line-height: 1.55; margin-bottom: 14px; }
.db-product-bot { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f0f0f0; padding-top: 12px; }
.db-product-price { font-size: 13px; font-weight: 700; color: #111; }

/* Services */
.db-service-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}
.db-service-card { border: 1px solid #ebebeb; padding: 28px; background: white; }
.db-service-icon {
    width: 38px; height: 38px;
    border: 1px solid #eee;
    border-radius: 50%;
    display: flex;
    align-items: center; justify-content: center;
    margin-bottom: 18px;
    color: #111;
}
.db-service-icon svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 1.5; }
.db-service-title { font-family: var(--font-serif, Georgia, serif); font-size: 15px; font-weight: 500; color: #111; margin-bottom: 12px; line-height: 1.35; }
.db-service-desc { font-size: 12px; color: #777; line-height: 1.6; margin-bottom: 20px; }

/* Orders */
.db-orders-list { display: flex; flex-direction: column; gap: 0; }
.db-order-row {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 16px 20px;
    background: white;
    border: 1px solid #ebebeb;
    border-bottom: none;
}
.db-order-row:last-child { border-bottom: 1px solid #ebebeb; }
.db-order-info { flex: 1; }
.db-order-code { font-size: 13px; font-weight: 700; color: #111; text-decoration: none; display: block; }
.db-order-date { font-size: 11px; color: #aaa; margin-top: 2px; }
.db-order-status {
    font-size: 9.5px; font-weight: 700; letter-spacing: 0.8px;
    padding: 4px 10px; border-radius: 2px; white-space: nowrap;
}
.db-order-total { font-size: 13px; font-weight: 600; color: #111; min-width: 120px; text-align: right; }

.db-orders-empty {
    text-align: center;
    padding: 56px 20px;
    background: white;
    border: 1px solid #ebebeb;
}
.db-orders-empty svg {
    width: 42px; height: 42px;
    stroke: #ccc; fill: none; stroke-width: 1;
    margin: 0 auto 16px;
    display: block;
}
.db-orders-empty p { font-size: 13px; color: #888; line-height: 1.7; margin-bottom: 24px; font-style: italic; }

/* Responsive */
.db-page-subtitle {
    font-size: 13px;
    color: #666;
    line-height: 1.5;
    margin-top: -20px;
    margin-bottom: 24px;
}
.db-mobile-user-card { display: none; }
.db-mobile-actions { display: none; }

@media (max-width: 768px) {
    .db-page-title {
        font-size: 22px;
        margin-bottom: 8px;
    }
    .db-page-subtitle {
        margin-top: 0;
        margin-bottom: 16px;
    }
    
    /* User Info Card */
    .db-mobile-user-card {
        display: flex;
        align-items: center;
        background: transparent;
        padding: 12px 0 20px;
        margin-bottom: 0;
    }
    .db-mu-avatar {
        width: 52px;
        height: 52px;
        background: #111;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 700;
        margin-right: 16px;
        flex-shrink: 0;
    }
    .db-mu-avatar img {
        width: 100%; height: 100%; object-fit: cover; border-radius: 50%;
    }
    .db-mu-info {
        flex: 1;
    }
    .db-mu-name {
        font-family: var(--font-serif);
        font-size: 19px;
        font-weight: 600;
        color: #111;
        margin-bottom: 4px;
    }
    .db-mu-id {
        font-size: 12.5px;
        color: #777;
        letter-spacing: 0.5px;
    }
    .db-mu-arrow svg {
        width: 22px;
        height: 22px;
        stroke: #999;
        fill: none;
        stroke-width: 1.5;
    }

    /* Action Buttons */
    .db-mobile-actions {
        display: flex;
        gap: 24px;
        margin-bottom: 36px;
        justify-content: flex-start;
        padding: 0 4px;
    }
    .db-mobile-actions a {
        padding: 0 0 6px 0;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        white-space: nowrap;
        border-bottom: 1px solid #111;
        color: #111;
        transition: opacity 0.2s;
    }
    .db-mobile-actions a:hover {
        opacity: 0.7;
    }
    .db-mobile-actions a svg {
        width: 13px;
        height: 13px;
        stroke: currentColor;
        fill: none;
        stroke-width: 1.5;
    }
    .db-btn-pill-dark {
        background: transparent;
    }
    .db-btn-pill-light {
        background: transparent;
    }

    /* VIP Card adjustments */
    .db-vip-card {
        padding: 24px;
        margin-bottom: 32px;
        border-radius: 0;
    }
    .db-vip-band {
        flex-direction: column;
        gap: 8px;
        font-size: 9px;
    }
    .db-vip-band span:last-child {
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px solid rgba(200, 169, 126, 0.2);
    }
    .db-vip-title {
        font-size: 20px;
        margin: 24px 0 24px;
    }
    .db-vip-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    /* Product Slider */
    .db-product-grid {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        gap: 16px;
        padding-bottom: 20px;
        scroll-snap-type: x mandatory;
        margin-right: -20px;
        padding-right: 20px;
    }
    .db-product-grid::-webkit-scrollbar {
        display: none;
    }
    .db-product-card {
        flex: 0 0 85%;
        min-width: 280px;
        scroll-snap-align: start;
    }
}

@media (max-width: 900px) {
    .db-vip-grid, .db-vip-steps, .db-product-grid, .db-service-grid { grid-template-columns: 1fr; }
    .db-advisor { flex-direction: column; text-align: center; }
    .db-advisor-actions { flex-direction: row; justify-content: center; width: 100%; }
    .db-order-row { flex-wrap: wrap; }
}
</style>
@endsection
