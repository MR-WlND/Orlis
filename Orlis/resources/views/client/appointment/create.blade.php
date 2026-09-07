@extends('layouts.client')
@section('title', __('messages.book_appointment') . ' Trải Nghiệm Boutique – Orlis')

@section('content')

<div class="appt-page">

    <!-- ===== Hero ===== -->
    <div class="appt-hero">
        <div class="appt-hero-inner">
            <p class="appt-hero-sup">BOUTIQUE ATELIER PRIVÉ</p>
            <h1 class="appt-hero-title">{{ __('messages.book_appointment') }} Trải Nghiệm Boutique</h1>
            <p class="appt-hero-desc">
                Khám phá thế giới nước hoa đỉnh cùng Chuyên giá tư vấn cá nhân tại các<br>
                Flagship Boutique Maison Orlis. Mỗi cuộc hẹn là một hành trình thấu hiểu<br>
                riêng dành cho phong cách của Quý khách.
            </p>
        </div>
    </div>

    <!-- ===== Main ===== -->
    <div class="appt-main">
        <div class="appt-container">

            <!-- ===== LEFT: Form ===== -->
            <div class="appt-left">

                @if(session('error'))
                <div class="appt-alert-error">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ session('error') }}
                </div>
                @endif

                <form method="POST" action="{{ route('appointments.store') }}">
                    @csrf

                    <!-- ===== Step 1: Chọn Showroom ===== -->
                    <div class="appt-step">
                        <div class="appt-step-head">
                            <div class="appt-step-num">1</div>
                            <div class="appt-step-label">CHỌN SHOWROOM FLAGSHIP</div>
                        </div>
                        <div class="appt-step-body">
                            <label class="appt-field-label">VỊ TRÍ <span>*</span></label>
                            <div style="position: relative; margin-bottom: 16px;">
                                <select name="store_id" id="storeSelect" class="appt-select" required onchange="updateStoreInfo()">
                                    <option value="">Chọn Boutique Flagship...</option>
                                    @foreach($stores as $store)
                                    <option value="{{ $store->id }}"
                                        data-address="{{ $store->address }}"
                                        data-phone="{{ $store->phone ?? '' }}"
                                        data-hours="{{ $store->opening_hours ?? '' }}"
                                        @selected(old('store_id') == $store->id)>
                                        {{ $store->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <svg class="appt-select-chevron" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                            </div>

                            <!-- Store info card -->
                            <div class="appt-store-info" id="storeInfo" style="display: none;">
                                <div class="appt-store-info-row">
                                    <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                    <span id="storeAddress">—</span>
                                </div>
                                <div class="appt-store-info-row" id="storeHoursRow">
                                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    <span id="storeHours">—</span>
                                </div>
                                <div class="appt-store-info-row" id="storePhoneRow">
                                    <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.1 11.91 19.79 19.79 0 0 1 1.04 3.27 2 2 0 0 1 3 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.09 8.91A16 16 0 0 0 13 14.82l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21 16z"/></svg>
                                    <span id="storePhone">—</span>
                                    <a href="#" id="storePhoneLink" class="appt-phone-link">Gọi ngay</a>
                                </div>
                            </div>
                            @error('store_id')<p class="appt-error">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- ===== Step 2: Loại Dịch Vụ ===== -->
                    <div class="appt-step">
                        <div class="appt-step-head">
                            <div class="appt-step-num">2</div>
                            <div class="appt-step-label">CHỌN DỊCH VỤ TRẢI NGHIỆM</div>
                        </div>
                        <div class="appt-step-body">
                            <div class="appt-service-list">

                                <label class="appt-service-card {{ old('service_type', 'consultation') === 'consultation' ? 'selected' : '' }}">
                                    <input type="radio" name="service_type" value="consultation"
                                        @checked(old('service_type', 'consultation') === 'consultation')>
                                    <div class="appt-service-inner">
                                        <div class="appt-service-top">
                                            <div>
                                                <div class="appt-service-name">Tư Vấn Nghệ Thuật Thổi Hương Bespoke</div>
                                                <div class="appt-service-sub">45 phút • Miễn phí</div>
                                            </div>
                                            <div class="appt-service-badge appt-badge-gold">CHỌN THỬ</div>
                                        </div>
                                        <p class="appt-service-desc">Chuyên gia phân tích cá tính, ký ức và phong cách của Quý khách để đề xuất các mùi hương phù hợp riêng biệt (45–60 phút).</p>
                                    </div>
                                </label>

                                <label class="appt-service-card {{ old('service_type') === 'trial' ? 'selected' : '' }}">
                                    <input type="radio" name="service_type" value="trial"
                                        @checked(old('service_type') === 'trial')>
                                    <div class="appt-service-inner">
                                        <div class="appt-service-top">
                                            <div>
                                                <div class="appt-service-name">Thử Mùi Hương & Khám Bộ Sưu Tập Thời Mới</div>
                                                <div class="appt-service-sub">30 phút • Miễn phí</div>
                                            </div>
                                            <div class="appt-service-badge appt-badge-silver">CHỌN THỬ</div>
                                        </div>
                                        <p class="appt-service-desc">Trải nghiệm các bộ sưu tập mới nhất cùng hướng dẫn viên tại Showroom và khám phá cách phối hương đa tầng. (30 phút)</p>
                                    </div>
                                </label>

                                <label class="appt-service-card {{ old('service_type') === 'vip_service' ? 'selected' : '' }}">
                                    <input type="radio" name="service_type" value="vip_service"
                                        @checked(old('service_type') === 'vip_service')>
                                    <div class="appt-service-inner">
                                        <div class="appt-service-top">
                                            <div>
                                                <div class="appt-service-name">VIP Private Atelier Experience ✦</div>
                                                <div class="appt-service-sub">90 phút • Dành cho hội viên VIP</div>
                                            </div>
                                            <div class="appt-service-badge appt-badge-vip">ĐĂNG KÝ VIP</div>
                                        </div>
                                        <p class="appt-service-desc">Dịch vụ trải nghiệm độc quyền dành cho thành viên Diamond & Gold: thử mẫu độc quyền chưa ra mắt, tư vấn cá nhân riêng tư & ưu tiên đặt trước. (90 phút)</p>
                                    </div>
                                </label>

                            </div>
                            @error('service_type')<p class="appt-error">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- ===== Step 3: Ngày & Giờ ===== -->
                    <div class="appt-step">
                        <div class="appt-step-head">
                            <div class="appt-step-num">3</div>
                            <div class="appt-step-label">CHỌN NGÀY & KHUNG GIỜ</div>
                        </div>
                        <div class="appt-step-body">
                            <div class="appt-date-row">
                                <div>
                                    <label class="appt-field-label">NGÀY HẸN <span>*</span></label>
                                    <input type="date" name="appointment_date" class="appt-date-input"
                                        value="{{ old('appointment_date') }}"
                                        min="{{ now()->addDay()->format('Y-m-d') }}"
                                        required>
                                    @error('appointment_date')<p class="appt-error">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <label class="appt-field-label" style="margin-top: 20px; display: block;">KHUNG GIỜ <span>*</span> <span class="appt-field-hint">– múi giờ Việt Nam (GMT+7)</span></label>
                            <div class="appt-timeslot-grid">
                                @foreach($timeSlots as $slot)
                                <label class="appt-timeslot {{ old('time_slot') === $slot ? 'selected' : '' }}">
                                    <input type="radio" name="time_slot" value="{{ $slot }}"
                                        @checked(old('time_slot') === $slot) required>
                                    <span class="appt-timeslot-time">{{ $slot }}</span>
                                    <span class="appt-timeslot-ampm">
                                        @php $h = (int) explode(':', $slot)[0]; @endphp
                                        {{ $h < 12 ? 'SA' : ($h < 18 ? 'CH' : 'TT') }}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                            @error('time_slot')<p class="appt-error">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- ===== Step 4: Thông tin & Ghi chú ===== -->
                    <div class="appt-step">
                        <div class="appt-step-head">
                            <div class="appt-step-num">4</div>
                            <div class="appt-step-label">THÔNG TIN QUÝ KHÁCH & GHI CHÚ</div>
                        </div>
                        <div class="appt-step-body">
                            <div class="appt-info-grid">
                                <div>
                                    <label class="appt-field-label">HỌ & TÊN <span>*</span></label>
                                    <div style="display: flex; gap: 8px;">
                                        <select name="salutation" class="appt-select appt-salutation">
                                            <option value="Ông">Ông</option>
                                            <option value="Bà">Bà</option>
                                            <option value="Ms">Ms</option>
                                        </select>
                                        <input type="text" name="contact_name" class="appt-input"
                                            placeholder="Họ tên của bạn" value="{{ old('contact_name', auth()->user()->name ?? '') }}" required style="flex: 1;">
                                    </div>
                                </div>
                                <div>
                                    <label class="appt-field-label">SỐ ĐIỆN THOẠI XÁC NHẬN <span>*</span></label>
                                    <input type="tel" name="contact_phone" class="appt-input"
                                        placeholder="VD: 0912 345 678" value="{{ old('contact_phone', auth()->user()->phone ?? '') }}" required>
                                </div>
                            </div>
                            <div style="margin-top: 16px;">
                                <label class="appt-field-label">ĐỊA CHỈ EMAIL NHẬN XÁC NHẬN LỊCH HẸN <span>*</span></label>
                                <input type="email" name="contact_email" class="appt-input"
                                    placeholder="Vd: vy.annuyen@example.com" value="{{ old('contact_email', auth()->user()->email ?? '') }}" required>
                            </div>
                            <div style="margin-top: 16px;">
                                <label class="appt-field-label">YÊU CẦU ĐẶC BIỆT HOẶC MÙI HƯƠNG QUAN TÂM</label>
                                <textarea name="note" rows="3" class="appt-input"
                                    style="resize: vertical;"
                                    placeholder="Ví dụ: Tôi muốn tìm nước hoa gỗ phương Đông, hoặc tìm nước hoa tặng quà sinh nhật cho người thân của mình.">{{ old('note') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="appt-submit-wrap">
                        <button type="submit" class="appt-submit-btn">
                            XÁC NHẬN ĐẶT LỊCH HẸN TRẢI NGHIỆM →
                        </button>
                        <p class="appt-submit-note">Chúng tôi sẽ liên hệ xác nhận lịch hẹn qua email hoặc số điện thoại đã đăng ký trong vòng 24 giờ.</p>
                    </div>

                </form>
            </div><!-- /appt-left -->

            <!-- ===== RIGHT: Sidebar ===== -->
            <div class="appt-right">

                <!-- Card: Đặc quyền -->
                <div class="appt-sidebar-card">
                    <div class="appt-sc-label">ĐẶC QUYỀN DÀNH CHO QUÝ KHÁCH</div>
                    <ul class="appt-perk-list">
                        <li>
                            <div class="appt-perk-icon">
                                <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                            <div>
                                <div class="appt-perk-title">Không Gian Salon Privé</div>
                                <p class="appt-perk-desc">Trải nghiệm không gian, riêng tư và nghệ thuật đô thị sang trọng dành riêng cho Quý khách.</p>
                            </div>
                        </li>
                        <li>
                            <div class="appt-perk-icon">
                                <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                            <div>
                                <div class="appt-perk-title">Nghệ Thuật Thưởng Thức Xa Hội</div>
                                <p class="appt-perk-desc">Thưởng thức trà và Báo thức tại Atelier, được giới thiệu bởi Chuyên gia Perfumer về nghệ thuật Olfactive.</p>
                            </div>
                        </li>
                        <li>
                            <div class="appt-perk-icon">
                                <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                            <div>
                                <div class="appt-perk-title">Ưu Đãi Đặt Trước Độc Quyền</div>
                                <p class="appt-perk-desc">Quý khách tham dự buổi hẹn sẽ được ưu đãi đặc biệt khi đặt trước sản phẩm & nhận quà tặng phiên bản giới hạn.</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Card: Lưu ý -->
                <div class="appt-sidebar-card appt-note-card">
                    <div class="appt-sc-label">LƯU Ý TRƯỚC KHI ĐẾN BOUTIQUE</div>
                    <ul class="appt-note-list">
                        <li>Vui lòng đến đúng giờ hoặc trước 5 phút để đảm bảo trải nghiệm đầy đủ.</li>
                        <li>Tránh sử dụng nước hoa khác trước buổi hẹn để giác quan được tinh tế nhất.</li>
                        <li>Quý khách VIP vui lòng xuất trình mã thẻ hội viên khi đến Boutique.</li>
                    </ul>
                </div>

                <!-- Card: CTA gọi điện -->
                <div class="appt-sidebar-card appt-cta-card">
                    <div class="appt-sc-label">CONCIERGE PRIVÉ ASSISTANCE</div>
                    <p class="appt-cta-title">Cần Thợ Đặt Lịch Gấp?</p>
                    <p class="appt-cta-desc">Đội ngũ Concierge Orlis hỗ trợ sắp xếp lịch ưu tiên cho Quý khách qua điện thoại 24/7.</p>
                    <a href="tel:18006886" class="appt-cta-phone">
                        <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.1 11.91 19.79 19.79 0 0 1 1.04 3.27 2 2 0 0 1 3 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.09 8.91A16 16 0 0 0 13 14.82l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21 16z"/></svg>
                        HOTLINE: 1800 6886
                    </a>
                    <div class="appt-cta-hours">08:00 – 21:00 • Tất cả các ngày trong tuần</div>
                </div>

            </div><!-- /appt-right -->

        </div><!-- /appt-container -->
    </div><!-- /appt-main -->

</div><!-- /appt-page -->

<style>
/* ===== PAGE ===== */
.appt-page {
    font-family: var(--font-sans, 'Inter', sans-serif);
    background: #f6f6f4;
}

/* ===== HERO ===== */
.appt-hero {
    background: linear-gradient(160deg, #1a1a1a 0%, #2d2620 60%, #1a1a1a 100%);
    padding: 110px 24px 60px;
    text-align: center;
}
.appt-hero-inner { max-width: 640px; margin: 0 auto; }
.appt-hero-sup {
    font-size: 9px;
    letter-spacing: 3px;
    color: #c8a97e;
    font-weight: 600;
    margin-bottom: 14px;
}
.appt-hero-title {
    font-family: var(--font-serif, Georgia, serif);
    font-size: 38px;
    font-weight: 400;
    color: #fff;
    margin: 0 0 18px;
    line-height: 1.25;
    letter-spacing: 0.01em;
}
.appt-hero-desc {
    font-size: 13px;
    color: #999;
    line-height: 1.8;
    margin: 0;
}

/* ===== MAIN ===== */
.appt-main {
    padding: 40px 24px 80px;
}
.appt-container {
    max-width: 1100px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 28px;
    align-items: start;
}

/* ===== ALERTS ===== */
.appt-alert-error {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 16px;
    background: #fff3f3;
    border: 1px solid #f5c6cb;
    color: #c0392b;
    font-size: 13px;
    margin-bottom: 20px;
    border-radius: 2px;
}
.appt-alert-error svg {
    width: 16px; height: 16px;
    stroke: #c0392b; fill: none; stroke-width: 2;
    flex-shrink: 0;
}

/* ===== STEPS ===== */
.appt-step {
    background: white;
    border: 1px solid #ebebeb;
    margin-bottom: 16px;
    overflow: hidden;
}
.appt-step-head {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 18px 24px;
    border-bottom: 1px solid #f5f5f5;
    background: #fafafa;
}
.appt-step-num {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #111;
    color: white;
    font-size: 11px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.appt-step-label {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1.5px;
    color: #555;
}
.appt-step-body { padding: 24px; }

/* ===== FIELD LABEL ===== */
.appt-field-label {
    display: block;
    font-size: 9.5px;
    font-weight: 700;
    letter-spacing: 1px;
    color: #888;
    margin-bottom: 8px;
    text-transform: uppercase;
}
.appt-field-label span { color: #cda873; }
.appt-field-hint { font-weight: 400; letter-spacing: 0; text-transform: none; color: #bbb; }

/* ===== SELECT ===== */
.appt-select {
    width: 100%;
    padding: 11px 36px 11px 14px;
    border: 1px solid #e5e5e5;
    background: white;
    font-family: inherit;
    font-size: 13px;
    color: #111;
    outline: none;
    transition: border-color 0.2s;
    border-radius: 2px;
    box-sizing: border-box;
    appearance: none;
}
.appt-select:focus { border-color: #cda873; }
.appt-select-chevron {
    width: 12px; height: 12px;
    position: absolute; right: 14px; top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    stroke: #999; fill: none; stroke-width: 2;
}
.appt-salutation {
    width: auto;
    min-width: 70px;
    flex-shrink: 0;
}

/* ===== INPUT ===== */
.appt-input {
    width: 100%;
    padding: 11px 14px;
    border: 1px solid #e5e5e5;
    background: white;
    font-family: inherit;
    font-size: 13px;
    color: #111;
    outline: none;
    transition: border-color 0.2s;
    border-radius: 2px;
    box-sizing: border-box;
}
.appt-input:focus { border-color: #cda873; }
.appt-input::placeholder { color: #bbb; }

/* ===== DATE INPUT ===== */
.appt-date-input {
    padding: 11px 14px;
    border: 1px solid #e5e5e5;
    background: white;
    font-family: inherit;
    font-size: 13px;
    color: #111;
    outline: none;
    transition: border-color 0.2s;
    border-radius: 2px;
    box-sizing: border-box;
    width: 100%;
    max-width: 220px;
}
.appt-date-input:focus { border-color: #cda873; }
.appt-date-row { display: flex; gap: 20px; flex-wrap: wrap; }

/* ===== STORE INFO ===== */
.appt-store-info {
    background: #fafafa;
    border: 1px solid #f0f0f0;
    padding: 14px 16px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    border-radius: 2px;
}
.appt-store-info-row {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: #555;
}
.appt-store-info-row svg {
    width: 13px; height: 13px;
    stroke: #9a7c5a; fill: none; stroke-width: 1.8;
    flex-shrink: 0;
}
.appt-phone-link {
    margin-left: auto;
    font-size: 11px;
    font-weight: 600;
    color: #cda873;
    text-decoration: underline;
}

/* ===== SERVICE CARDS ===== */
.appt-service-list { display: flex; flex-direction: column; gap: 10px; }
.appt-service-card {
    display: block;
    border: 1.5px solid #e8e8e8;
    cursor: pointer;
    transition: border-color 0.2s, background 0.15s;
    border-radius: 2px;
    background: white;
}
.appt-service-card input[type="radio"] { display: none; }
.appt-service-card.selected,
.appt-service-card:has(input:checked) {
    border-color: #111;
    background: #fafafa;
}
.appt-service-card:hover { border-color: #ccc; }
.appt-service-inner { padding: 16px 18px; }
.appt-service-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 8px;
    gap: 12px;
}
.appt-service-name {
    font-size: 13.5px;
    font-weight: 600;
    color: #111;
    margin-bottom: 3px;
}
.appt-service-sub {
    font-size: 11px;
    color: #aaa;
}
.appt-service-badge {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 0.8px;
    padding: 4px 10px;
    white-space: nowrap;
    flex-shrink: 0;
    border-radius: 2px;
}
.appt-badge-gold   { background: #fdf4e3; color: #9a6f30; border: 1px solid #e8d5a3; }
.appt-badge-silver { background: #f0f4f8; color: #5b7fa6; border: 1px solid #c5d5e8; }
.appt-badge-vip    { background: #111; color: #cda873; border: 1px solid #333; }
.appt-service-desc { font-size: 12px; color: #888; line-height: 1.6; margin: 0; }

/* ===== TIMESLOTS ===== */
.appt-timeslot-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
    margin-top: 10px;
}
.appt-timeslot {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px 8px;
    border: 1.5px solid #e8e8e8;
    cursor: pointer;
    transition: all 0.15s;
    background: white;
    border-radius: 2px;
}
.appt-timeslot input[type="radio"] { display: none; }
.appt-timeslot.selected,
.appt-timeslot:has(input:checked) {
    border-color: #111;
    background: #111;
}
.appt-timeslot:hover:not(.selected) { border-color: #ccc; }
.appt-timeslot-time {
    font-size: 14px;
    font-weight: 700;
    color: #111;
    transition: color 0.15s;
}
.appt-timeslot.selected .appt-timeslot-time,
.appt-timeslot:has(input:checked) .appt-timeslot-time { color: white; }
.appt-timeslot-ampm {
    font-size: 9px;
    color: #aaa;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-top: 2px;
    transition: color 0.15s;
}
.appt-timeslot.selected .appt-timeslot-ampm,
.appt-timeslot:has(input:checked) .appt-timeslot-ampm { color: #cda873; }

/* ===== INFO GRID ===== */
.appt-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

/* ===== ERROR ===== */
.appt-error {
    font-size: 11.5px;
    color: #c0392b;
    margin: 6px 0 0;
}

/* ===== SUBMIT ===== */
.appt-submit-wrap { text-align: center; margin-top: 8px; }
.appt-submit-btn {
    display: inline-block;
    width: 100%;
    padding: 18px 32px;
    background: #111;
    color: white;
    border: none;
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1.5px;
    cursor: pointer;
    transition: background 0.2s;
    border-radius: 2px;
}
.appt-submit-btn:hover { background: #333; }
.appt-submit-note {
    font-size: 11.5px;
    color: #aaa;
    margin-top: 14px;
    line-height: 1.6;
}

/* ===== SIDEBAR ===== */
.appt-sidebar-card {
    background: white;
    border: 1px solid #ebebeb;
    padding: 24px;
    margin-bottom: 16px;
}
.appt-sc-label {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 1.5px;
    color: #bbb;
    margin-bottom: 16px;
}

/* Perks */
.appt-perk-list {
    list-style: none;
    padding: 0; margin: 0;
    display: flex; flex-direction: column; gap: 18px;
}
.appt-perk-list li { display: flex; gap: 12px; align-items: flex-start; }
.appt-perk-icon {
    width: 22px; height: 22px;
    border-radius: 50%;
    background: #f2ede4;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; margin-top: 1px;
}
.appt-perk-icon svg {
    width: 11px; height: 11px;
    stroke: #9a7c5a; fill: none; stroke-width: 2.5;
}
.appt-perk-title { font-size: 12.5px; font-weight: 600; color: #111; margin-bottom: 4px; }
.appt-perk-desc { font-size: 11.5px; color: #888; line-height: 1.55; margin: 0; }

/* Note card */
.appt-note-card { background: #fafaf8; }
.appt-note-list {
    list-style: none; padding: 0; margin: 0;
    display: flex; flex-direction: column; gap: 10px;
}
.appt-note-list li {
    font-size: 11.5px; color: #777; line-height: 1.6;
    padding-left: 14px; position: relative;
}
.appt-note-list li::before {
    content: '—';
    position: absolute; left: 0; color: #ccc;
}

/* CTA card */
.appt-cta-card { background: #111; border-color: #111; }
.appt-cta-card .appt-sc-label { color: #6a6a6a; }
.appt-cta-title {
    font-family: var(--font-serif, Georgia, serif);
    font-size: 18px; font-weight: 400; color: white;
    margin: 0 0 10px; line-height: 1.3;
}
.appt-cta-desc { font-size: 11.5px; color: #888; line-height: 1.6; margin: 0 0 20px; }
.appt-cta-phone {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 13px 18px;
    background: #cda873;
    color: #111;
    text-decoration: none;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.8px;
    transition: background 0.2s;
    border-radius: 2px;
    margin-bottom: 10px;
}
.appt-cta-phone svg {
    width: 15px; height: 15px;
    stroke: #111; fill: none; stroke-width: 1.8;
}
.appt-cta-phone:hover { background: #d4ac72; }
.appt-cta-hours { font-size: 10.5px; color: #666; text-align: center; }

/* ===== RESPONSIVE ===== */
@media (max-width: 900px) {
    .appt-container { grid-template-columns: 1fr; }
    .appt-right { order: -1; }
    .appt-timeslot-grid { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 600px) {
    .appt-hero-title { font-size: 26px; }
    .appt-info-grid { grid-template-columns: 1fr; }
    .appt-timeslot-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>

<script>
// Service card selection
document.querySelectorAll('.appt-service-card input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.appt-service-card').forEach(c => c.classList.remove('selected'));
        this.closest('.appt-service-card').classList.add('selected');
    });
});

// Timeslot selection
document.querySelectorAll('.appt-timeslot input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.appt-timeslot').forEach(t => t.classList.remove('selected'));
        this.closest('.appt-timeslot').classList.add('selected');
    });
});

// Store info update
function updateStoreInfo() {
    const sel = document.getElementById('storeSelect');
    const opt = sel.options[sel.selectedIndex];
    const info = document.getElementById('storeInfo');

    if (!opt.value) {
        info.style.display = 'none';
        return;
    }

    document.getElementById('storeAddress').textContent = opt.dataset.address || '—';

    const hours = opt.dataset.hours;
    document.getElementById('storeHours').textContent = hours || '—';
    document.getElementById('storeHoursRow').style.display = hours ? 'flex' : 'none';

    const phone = opt.dataset.phone;
    document.getElementById('storePhone').textContent = phone || '—';
    document.getElementById('storePhoneLink').href = phone ? 'tel:' + phone.replace(/\s/g, '') : '#';
    document.getElementById('storePhoneRow').style.display = phone ? 'flex' : 'none';

    info.style.display = 'flex';
}
// Trigger on page load if old value exists
window.addEventListener('DOMContentLoaded', updateStoreInfo);
</script>

@endsection
