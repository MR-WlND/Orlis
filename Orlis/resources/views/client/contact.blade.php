@extends('layouts.client')
@section('title', 'Liên Hệ – Orlis Maison')
@section('content')

<div class="contact-page">

    <!-- ===== Hero ===== -->
    <div class="contact-hero">
        <div class="contact-hero-inner">
            <p class="contact-hero-sup">MAISON ORLIS • VIỆT NAM</p>
            <h1 class="contact-hero-title">Liên Hệ & Tư Vấn</h1>
            <p class="contact-hero-desc">Đội ngũ Concierge luôn sẵn sàng đồng hành cùng Quý khách.<br>Mọi nguyện vọng đều được lắng nghe và giải quyết chu đáo.</p>
        </div>
    </div>

    <!-- ===== Main Content ===== -->
    <div class="contact-main">
        <div class="contact-container">

            <!-- Left: Form -->
            <div class="contact-form-col">
                <div class="contact-section-label">GỬI TIN NHẮN CHO CHÚNG TÔI</div>
                <h2 class="contact-form-title">Chúng tôi sẽ phản hồi trong 2 – 4 giờ làm việc</h2>

                @if(session('contact_success'))
                <div class="contact-alert-success">
                    <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    Cảm ơn Quý khách! Chúng tôi sẽ liên lạc sớm nhất có thể.
                </div>
                @endif

                <form action="{{ route('contact.send') }}" method="POST" class="contact-form">
                    @csrf
                    <div class="cf-row">
                        <div class="cf-field">
                            <label class="cf-label">Họ & Tên <span>*</span></label>
                            <input type="text" name="name" class="cf-input" placeholder="Nguyễn Văn A" required value="{{ old('name') }}">
                            @error('name')<span class="cf-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="cf-field">
                            <label class="cf-label">Email <span>*</span></label>
                            <input type="email" name="email" class="cf-input" placeholder="email@example.com" required value="{{ old('email') }}">
                            @error('email')<span class="cf-error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="cf-row">
                        <div class="cf-field">
                            <label class="cf-label">Số điện thoại</label>
                            <input type="tel" name="phone" class="cf-input" placeholder="0912 345 678" value="{{ old('phone') }}">
                        </div>
                        <div class="cf-field">
                            <label class="cf-label">Chủ đề <span>*</span></label>
                            <div style="position: relative;">
                                <select name="subject" class="cf-input" style="appearance: none; padding-right: 36px;" required>
                                    <option value="">Chọn chủ đề...</option>
                                    <option value="Tư vấn sản phẩm" {{ old('subject') == 'Tư vấn sản phẩm' ? 'selected' : '' }}>Tư vấn sản phẩm</option>
                                    <option value="Đơn hàng & vận chuyển" {{ old('subject') == 'Đơn hàng & vận chuyển' ? 'selected' : '' }}>Đơn hàng & vận chuyển</option>
                                    <option value="Đổi trả & hoàn tiền" {{ old('subject') == 'Đổi trả & hoàn tiền' ? 'selected' : '' }}>Đổi trả & hoàn tiền</option>
                                    <option value="Hợp tác & kinh doanh" {{ old('subject') == 'Hợp tác & kinh doanh' ? 'selected' : '' }}>Hợp tác & kinh doanh</option>
                                    <option value="Khác" {{ old('subject') == 'Khác' ? 'selected' : '' }}>Khác</option>
                                </select>
                                <svg style="width:12px;height:12px;position:absolute;right:14px;top:50%;transform:translateY(-50%);pointer-events:none;stroke:#999;fill:none;stroke-width:2;" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                            </div>
                            @error('subject')<span class="cf-error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="cf-field">
                        <label class="cf-label">Nội dung <span>*</span></label>
                        <textarea name="message" rows="5" class="cf-input" placeholder="Mô tả chi tiết yêu cầu hoặc câu hỏi của Quý khách..." required style="resize: vertical;">{{ old('message') }}</textarea>
                        @error('message')<span class="cf-error">{{ $message }}</span>@enderror
                    </div>
                    <div style="display: flex; justify-content: flex-end; margin-top: 8px;">
                        <button type="submit" class="cf-submit">
                            GỬI TIN NHẮN
                            <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right: Info -->
            <div class="contact-info-col">

                <!-- Boutiques -->
                <div class="contact-section-label">SHOWROOM & BOUTIQUE</div>
                <div class="contact-boutiques">
                    <div class="boutique-item">
                        <div class="boutique-icon">
                            <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div>
                            <div class="boutique-name">Orlis Flagship – Hà Nội</div>
                            <div class="boutique-addr">36 Hàng Bài, Hoàn Kiếm, Hà Nội</div>
                            <div class="boutique-hours">T2 – CN: 09:00 – 21:00</div>
                        </div>
                    </div>
                    <div class="boutique-divider"></div>
                    <div class="boutique-item">
                        <div class="boutique-icon">
                            <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div>
                            <div class="boutique-name">Orlis Salon Privé – TP.HCM</div>
                            <div class="boutique-addr">Tầng 5, Diamond Plaza, Q.1, TP.HCM</div>
                            <div class="boutique-hours">T2 – CN: 09:00 – 21:30</div>
                        </div>
                    </div>
                </div>

                <!-- Divider -->
                <div class="boutique-divider" style="margin: 24px 0;"></div>

                <!-- Contact channels -->
                <div class="contact-section-label">KÊNH LIÊN LẠC TRỰC TIẾP</div>
                <div class="contact-channels">
                    <a href="tel:18006886" class="channel-item">
                        <div class="channel-icon">
                            <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.1 11.91 19.79 19.79 0 0 1 1.04 3.27 2 2 0 0 1 3 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.09 8.91A16 16 0 0 0 13 14.82l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21 16z"/></svg>
                        </div>
                        <div>
                            <div class="channel-label">Đường dây nóng Concierge</div>
                            <div class="channel-value">1800 6886 <span>(08:00 – 21:00)</span></div>
                        </div>
                    </a>
                    <a href="mailto:concierge@orlis.vn" class="channel-item">
                        <div class="channel-icon">
                            <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </div>
                        <div>
                            <div class="channel-label">Hộp thư điện tử</div>
                            <div class="channel-value">concierge@orlis.vn</div>
                        </div>
                    </a>
                    <a href="{{ route('tickets.create') }}" class="channel-item">
                        <div class="channel-icon" style="background: #e8f5e9;">
                            <svg viewBox="0 0 24 24" style="stroke: #27ae60;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        </div>
                        <div>
                            <div class="channel-label">Yêu cầu hỗ trợ trực tuyến</div>
                            <div class="channel-value" style="color: #27ae60;">Gửi Ticket Hỗ Trợ →</div>
                        </div>
                    </a>
                </div>

                <!-- Appointment CTA -->
                <a href="{{ route('appointments.create') }}" class="contact-appt-btn">
                    <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    {{ __('messages.book_appointment') }} Tại Boutique
                </a>

            </div>

        </div>
    </div>

</div>

<style>
/* ===== PAGE ===== */
.contact-page {
    font-family: var(--font-sans, 'Inter', sans-serif);
}

/* ===== HERO ===== */
.contact-hero {
    background: #111;
    padding: 120px 24px 70px;
    text-align: center;
}
.contact-hero-inner { max-width: 600px; margin: 0 auto; }
.contact-hero-sup {
    font-size: 9px;
    letter-spacing: 3px;
    color: #c8a97e;
    font-weight: 600;
    margin-bottom: 16px;
}
.contact-hero-title {
    font-family: var(--font-serif, Georgia, serif);
    font-size: 42px;
    font-weight: 400;
    color: #fff;
    margin: 0 0 18px;
    letter-spacing: 0.02em;
}
.contact-hero-desc {
    font-size: 13.5px;
    color: #999;
    line-height: 1.8;
    margin: 0;
}

/* ===== MAIN ===== */
.contact-main {
    background: #f6f6f4;
    padding: 60px 24px 80px;
}
.contact-container {
    max-width: 1100px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 36px;
    align-items: start;
}

/* ===== SECTION LABEL ===== */
.contact-section-label {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 2px;
    color: #bbb;
    margin-bottom: 10px;
}

/* ===== FORM COLUMN ===== */
.contact-form-col {
    background: white;
    padding: 40px;
    border: 1px solid #ebebeb;
}
.contact-form-title {
    font-family: var(--font-serif, Georgia, serif);
    font-size: 20px;
    font-weight: 400;
    color: #111;
    margin: 0 0 28px;
    line-height: 1.4;
}
.contact-alert-success {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 16px;
    background: #e8f5e9;
    border: 1px solid #c8e6c9;
    color: #2e7d32;
    font-size: 13px;
    margin-bottom: 24px;
    border-radius: 2px;
}
.contact-alert-success svg {
    width: 16px;
    height: 16px;
    stroke: #2e7d32;
    fill: none;
    stroke-width: 2.5;
    flex-shrink: 0;
}

.contact-form { display: flex; flex-direction: column; gap: 18px; }
.cf-row { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
.cf-field { display: flex; flex-direction: column; gap: 7px; }
.cf-label {
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 0.8px;
    color: #333;
    text-transform: uppercase;
}
.cf-label span { color: #cda873; }
.cf-input {
    width: 100%;
    padding: 11px 14px;
    border: 1px solid #e5e5e5;
    background: #fafafa;
    font-family: inherit;
    font-size: 13px;
    color: #111;
    outline: none;
    transition: border-color 0.2s, background 0.2s;
    border-radius: 2px;
    box-sizing: border-box;
}
.cf-input:focus { border-color: #cda873; background: white; }
.cf-input::placeholder { color: #bbb; }
.cf-error { font-size: 11px; color: #c0392b; }
.cf-submit {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 13px 32px;
    background: #111;
    color: white;
    border: none;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.2px;
    cursor: pointer;
    font-family: inherit;
    transition: background 0.2s;
    border-radius: 2px;
}
.cf-submit svg {
    width: 14px;
    height: 14px;
    stroke: white;
    fill: none;
    stroke-width: 2;
}
.cf-submit:hover { background: #333; }

/* ===== INFO COLUMN ===== */
.contact-info-col { display: flex; flex-direction: column; }

/* Boutiques */
.contact-boutiques { display: flex; flex-direction: column; gap: 0; }
.boutique-item {
    display: flex;
    gap: 14px;
    align-items: flex-start;
    padding: 18px 0;
}
.boutique-divider { height: 1px; background: #eee; }
.boutique-icon {
    width: 36px;
    height: 36px;
    background: #f5f0e8;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.boutique-icon svg {
    width: 16px;
    height: 16px;
    stroke: #9a7c5a;
    fill: none;
    stroke-width: 1.8;
}
.boutique-name {
    font-size: 13px;
    font-weight: 600;
    color: #111;
    margin-bottom: 3px;
}
.boutique-addr { font-size: 12px; color: #666; margin-bottom: 3px; }
.boutique-hours { font-size: 11px; color: #aaa; }

/* Channels */
.contact-channels { display: flex; flex-direction: column; gap: 0; }
.channel-item {
    display: flex;
    gap: 14px;
    align-items: center;
    padding: 14px 0;
    border-bottom: 1px solid #f0f0f0;
    text-decoration: none;
    transition: opacity 0.15s;
}
.channel-item:last-child { border-bottom: none; }
.channel-item:hover { opacity: 0.75; }
.channel-icon {
    width: 36px;
    height: 36px;
    background: #f7f5f2;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.channel-icon svg {
    width: 15px;
    height: 15px;
    stroke: #9a7c5a;
    fill: none;
    stroke-width: 1.8;
}
.channel-label {
    font-size: 9.5px;
    font-weight: 700;
    letter-spacing: 1px;
    color: #bbb;
    margin-bottom: 3px;
    text-transform: uppercase;
}
.channel-value {
    font-size: 13px;
    font-weight: 600;
    color: #222;
}
.channel-value span { font-size: 10.5px; color: #aaa; font-weight: 400; }

/* Appointment button */
.contact-appt-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-top: 24px;
    padding: 14px 20px;
    background: #111;
    color: white;
    text-decoration: none;
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: 0.8px;
    transition: background 0.2s;
    border-radius: 2px;
}
.contact-appt-btn svg {
    width: 15px;
    height: 15px;
    stroke: white;
    fill: none;
    stroke-width: 1.8;
}
.contact-appt-btn:hover { background: #333; }

/* ===== RESPONSIVE ===== */
@media (max-width: 900px) {
    .contact-container { grid-template-columns: 1fr; gap: 24px; }
    .contact-info-col { order: -1; margin-bottom: 10px; }
}
@media (max-width: 768px) {
    .contact-hero { padding: 100px 20px 50px; }
    .contact-main { padding: 40px 20px 60px; }
    .contact-hero-title { font-size: 28px; }
    .contact-hero-desc { font-size: 13px; }
    .contact-form-col { padding: 20px; }
    .cf-row { grid-template-columns: 1fr; gap: 15px; }
}
</style>

@endsection
