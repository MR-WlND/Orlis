@extends('layouts.customer')
@section('customer_title', __('messages.create_support_ticket'))
@section('customer_content')
<div>
    <div class="section-header">
        <div>
            <div class="subtitle">{{ __('messages.support_requests_caps') }}</div>
            <h2 class="section-title" style="margin-bottom:0;">{{ __('messages.submit_new_support_request') }}</h2>
        </div>
        <a href="{{ route('tickets.index') }}" class="btn-primary-sm">← Quay lại danh sách</a>
    </div>

    <div style="padding: 20px 0;">

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px; align-items: start;">
            
            <!-- Left Column: Form -->
            <div style="background: white; padding: 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); border: 1px solid #f0f0f0;">
                <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Subject/Topic (Radio Cards) -->
                    <div style="margin-bottom: 25px;">
                        <label style="display: block; font-size: 11px; font-weight: 700; letter-spacing: 1px; color: #333; margin-bottom: 12px; text-transform: uppercase;">{{ __('messages.support_topic') }} <span style="color: #cda873;">*</span></label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                            
                            <label class="topic-card">
                                <input type="radio" name="subject" value="{{ __('messages.order_shipping_topic') }}" required checked style="display: none;">
                                <div class="card-content">
                                    <div class="radio-circle"></div>
                                    <span style="font-size: 13px; font-weight: 500;">{{ __('messages.order_shipping_topic') }}</span>
                                </div>
                            </label>
                            
                            <label class="topic-card">
                                <input type="radio" name="subject" value="{{ __('messages.product_craft_topic') }}" required style="display: none;">
                                <div class="card-content">
                                    <div class="radio-circle"></div>
                                    <span style="font-size: 13px; font-weight: 500;">{{ __('messages.product_craft_topic') }}</span>
                                </div>
                            </label>
                            
                            <label class="topic-card">
                                <input type="radio" name="subject" value="{{ __('messages.return_refund_topic') }}" required style="display: none;">
                                <div class="card-content">
                                    <div class="radio-circle"></div>
                                    <span style="font-size: 13px; font-weight: 500;">{{ __('messages.return_refund_topic') }}</span>
                                </div>
                            </label>
                            
                            <label class="topic-card">
                                <input type="radio" name="subject" value="{{ __('messages.boutique_appointment_topic') }}" required style="display: none;">
                                <div class="card-content">
                                    <div class="radio-circle"></div>
                                    <span style="font-size: 13px; font-weight: 500;">{{ __('messages.boutique_appointment_topic') }}</span>
                                </div>
                            </label>

                            <label class="topic-card">
                                <input type="radio" name="subject" value="{{ __('messages.vip_privilege_topic') }}" required style="display: none;">
                                <div class="card-content">
                                    <div class="radio-circle"></div>
                                    <span style="font-size: 13px; font-weight: 500;">{{ __('messages.vip_privilege_topic') }}</span>
                                </div>
                            </label>
                            
                            <label class="topic-card">
                                <input type="radio" name="subject" value="{{ __('messages.other_issues_topic') }}" required style="display: none;">
                                <div class="card-content">
                                    <div class="radio-circle"></div>
                                    <span style="font-size: 13px; font-weight: 500;">{{ __('messages.other_issues_topic') }}</span>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
                        <!-- Order ID (Optional) -->
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 700; letter-spacing: 1px; color: #333; margin-bottom: 10px; text-transform: uppercase;">{{ __('messages.order_id_label') }} <span style="color: #999; font-weight: 400; text-transform: none; letter-spacing: 0;">{{ __('messages.not_required') }}</span></label>
                            <input type="text" name="order_id" placeholder="Ví dụ: ORL-2025-ED891" class="luxury-input">
                        </div>
                        
                        <!-- Priority -->
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 700; letter-spacing: 1px; color: #333; margin-bottom: 10px; text-transform: uppercase;">{{ __('messages.priority_level') ?? 'Mức độ ưu tiên' }} <span style="color: #cda873;">*</span></label>
                            <div style="position: relative;">
                                <select name="priority" class="luxury-input" style="appearance: none; padding-right: 30px;">
                                    <option value="normal">{{ __('messages.priority_normal') ?? 'Bình thường (Tiêu chuẩn 4 - 8 giờ)' }}</option>
                                    <option value="high">{{ __('messages.priority_high_desc') ?? 'Gấp (Ưu tiên xử lý dưới 4 giờ)' }}</option>
                                    <option value="low">{{ __('messages.priority_low') ?? 'Thấp (Trong vòng 24 giờ)' }}</option>
                                </select>
                                <svg style="width: 12px; height: 12px; position: absolute; right: 15px; top: 50%; transform: translateY(-50%); pointer-events: none; stroke: #999; fill: none; stroke-width: 2;" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"></polyline></svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Subject Summary -->
                    <div style="margin-bottom: 25px;">
                        <label style="display: block; font-size: 11px; font-weight: 700; letter-spacing: 1px; color: #333; margin-bottom: 10px; text-transform: uppercase;">{{ __('messages.subject_label') ?? '{{ __('messages.request_subject') }}' }} <span style="color: #cda873;">*</span></label>
                        <input type="text" name="subject_summary" required placeholder="{{ __('messages.subject_placeholder') ?? 'Tóm tắt ngắn gọn nguyện vọng hoặc vấn đề cần hỗ trợ...' }}" class="luxury-input">
                    </div>
                    
                    <!-- Detailed Message -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 10px;">
                            <label style="font-size: 11px; font-weight: 700; letter-spacing: 1px; color: #333; text-transform: uppercase;">{{ __('messages.detailed_message') ?? 'Nội dung chi tiết' }} <span style="color: #cda873;">*</span></label>
                            <span style="font-size: 10px; color: #999;">Tối thiểu 20 ký tự</span>
                        </div>
                        <textarea name="message" rows="5" required minlength="20" placeholder="{{ __('messages.message_placeholder') ?? 'Mô tả chi tiết câu hỏi hoặc vấn đề Quý khách gặp phải để Giám tuyển Orlis chuẩn bị phương án chu đáo nhất...' }}" class="luxury-input" style="resize: vertical;"></textarea>
                    </div>

                    <!-- File Upload -->
                    <div style="margin-bottom: 30px;">
                        <label style="display: block; font-size: 11px; font-weight: 700; letter-spacing: 1px; color: #333; margin-bottom: 10px; text-transform: uppercase;">Tài liệu & Hình ảnh đính kèm</label>
                        <div style="border: 1px dashed #e0e0e0; background: #fdfdfd; padding: 35px 20px; text-align: center; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.borderColor='#ccc'" onmouseout="this.style.borderColor='#e0e0e0'">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: #f5f5f5; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px;">
                                <svg style="width: 16px; height: 16px; stroke: #666; fill: none; stroke-width: 1.5;" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                            </div>
                            <p style="font-size: 12px; color: #333; margin-bottom: 5px; font-weight: 500;">Kéo thả tập tin vào đây hoặc <span style="color: #cda873;">chọn tệp từ thiết bị</span></p>
                            <p style="font-size: 11px; color: #999;">Định dạng hỗ trợ: JPG, PNG, WEBP, PDF (Dung lượng tối đa 10MB)</p>
                            <input type="file" name="attachment" style="display: none;" accept=".jpg,.jpeg,.png,.webp,.pdf">
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 15px; justify-content: flex-end; align-items: center; padding-top: 20px; border-top: 1px solid #f0f0f0;">
                        <a href="{{ route('tickets.index') }}" class="btn-ghost">{{ __('messages.cancel') ?? 'HỦY BỎ' }}</a>
                        <button type="submit" class="btn-solid">
                            {{ __('messages.submit_request') ?? 'GỬI {{ __('messages.support_requests_caps') }}' }} 
                            <svg style="width: 14px; height: 14px; margin-left: 8px; stroke: currentColor; fill: none; stroke-width: 2;" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Right Column: Info Cards -->
            <div style="display: flex; flex-direction: column; gap: 20px;">
                
                <!-- Card 1: Cam kết -->
                <div class="ticket-info-card">
                    <div class="ticket-card-header">
                        <div class="icon-circle"><svg viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"></path></svg></div>
                        <h3 style="font-size: 11px; font-weight: 700; letter-spacing: 1.5px; color: #111; margin: 0;">CAM KẾT DỊCH VỤ ORLIS</h3>
                    </div>
                    <ul class="ticket-info-list">
                        <li><span style="font-weight: 600; color: #222;">Phản hồi tiêu chuẩn:</span> Trong vòng 2 - 4 giờ làm việc bởi chuyên viên quản lý tài khoản cá nhân.</li>
                        <li><span style="font-weight: 600; color: #222;">Bảo mật tuyệt đối:</span> Mọi chứng từ và thông tin giao dịch tuân thủ chuẩn mã hóa quốc tế SSL 256-bit.</li>
                        <li><span style="font-weight: 600; color: #222;">Chính sách đổi trả:</span> Đổi sản phẩm trong vòng 14 ngày nếu giữ nguyên seal và bao bì nguyên bản.</li>
                    </ul>
                </div>
                
                <!-- Card 2: Liên hệ -->
                <div class="ticket-info-card">
                    <div class="ticket-card-header" style="margin-bottom: 20px;">
                        <h3 style="font-size: 11px; font-weight: 700; letter-spacing: 1.5px; color: #111; margin: 0;">KÊNH LIÊN HỆ KHẨN CẤP</h3>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 15px;">
                        <div style="display: flex; align-items: flex-start; gap: 15px;">
                            <div class="ticket-icon-box"><svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg></div>
                            <div>
                                <div style="font-size: 9px; color: #999; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">Đường dây nóng Concierge</div>
                                <div style="font-size: 13px; font-weight: 600; color: #222;">1800 6886 <span style="font-weight: 400; color: #999; font-size: 10px;">(08:00 - 21:00)</span></div>
                            </div>
                        </div>
                        <div style="width: 100%; height: 1px; background: #f5f5f5;"></div>
                        <div style="display: flex; align-items: flex-start; gap: 15px;">
                            <div class="ticket-icon-box"><svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg></div>
                            <div>
                                <div style="font-size: 9px; color: #999; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">Hộp thư điện tử</div>
                                <div style="font-size: 12px; font-weight: 600; color: #222;">concierge@orlis.vn</div>
                            </div>
                        </div>
                        <div style="width: 100%; height: 1px; background: #f5f5f5;"></div>
                        <div style="display: flex; align-items: flex-start; gap: 15px;">
                            <div class="ticket-icon-box"><svg viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg></div>
                            <div>
                                <div style="font-size: 9px; color: #999; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">Trò chuyện trực tiếp</div>
                                <div style="font-size: 11px; font-weight: 700; color: #111; text-decoration: underline; cursor: pointer;">Khởi tạo phiên Live Chat</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Card 3: FAQ -->
                <div class="ticket-info-card">
                    <div class="ticket-card-header" style="margin-bottom: 15px;">
                        <h3 style="font-size: 11px; font-weight: 700; letter-spacing: 1.5px; color: #111; margin: 0;">CÂU HỎI THƯỜNG GẶP</h3>
                    </div>
                    <ul class="ticket-faq-list">
                        <li>1. Thời gian giao hàng tại Hà Nội & TP.HCM mất bao lâu?</li>
                        <li>2. Tôi có thể kiểm tra tem niêm phong và thử mẫu trước khi nhận?</li>
                        <li>3. Điều kiện để khắc tên thủ công lên chai nước hoa Orlis?</li>
                    </ul>
                </div>
                
            </div>
            
        </div>
    </div>
</div>

<style>
    /* Radio Cards */
    .topic-card {
        display: block;
        cursor: pointer;
    }
    .topic-card .card-content {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 15px;
        border: 1px solid #e8e8e8;
        border-radius: 2px;
        transition: all 0.2s;
    }
    .topic-card:hover .card-content {
        border-color: #d0d0d0;
        background-color: #fafafa;
    }
    .topic-card input:checked + .card-content {
        border-color: #111;
        background-color: #fff;
    }
    .radio-circle {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 1.5px solid #ccc;
        position: relative;
        flex-shrink: 0;
    }
    .topic-card input:checked + .card-content .radio-circle {
        border-color: #111;
    }
    .topic-card input:checked + .card-content .radio-circle::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 5px;
        height: 5px;
        background: #111;
        border-radius: 50%;
    }
    
    /* Luxury Inputs */
    .luxury-input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #e8e8e8;
        background: #fff;
        font-family: var(--font-sans);
        font-size: 13px;
        color: #111;
        outline: none;
        transition: all 0.2s;
        border-radius: 2px;
    }
    .luxury-input:focus {
        border-color: #cda873;
    }
    .luxury-input::placeholder {
        color: #bbb;
    }
    
    /* Buttons */
    .btn-ghost {
        padding: 12px 30px;
        background: transparent;
        border: 1px solid #e0e0e0;
        color: #777;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-ghost:hover {
        background: #f5f5f5;
        color: #111;
        border-color: #d0d0d0;
    }
    .btn-solid {
        padding: 12px 30px;
        background: #000;
        border: 1px solid #000;
        color: #fff;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        cursor: pointer;
        display: flex;
        align-items: center;
        transition: all 0.2s;
    }
    .btn-solid:hover {
        background: #222;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    /* Info Cards */
    .ticket-info-card {
        background: white;
        padding: 25px;
        border: 1px solid #f0f0f0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.015);
        border-radius: 2px;
    }
    .ticket-card-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }
    .icon-circle {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #f2ede4;
        color: #8c734b;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .icon-circle svg {
        width: 10px;
        height: 10px;
        stroke: currentColor;
        fill: none;
        stroke-width: 2.5;
        stroke-linecap: round;
        stroke-linejoin: round;
    }
    .ticket-info-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    .ticket-info-list li {
        font-size: 11.5px;
        color: #666;
        line-height: 1.6;
        position: relative;
        padding-left: 10px;
        margin-bottom: 0;
    }
    .ticket-info-list li::before {
        content: '-';
        position: absolute;
        left: 0;
        color: #666;
    }
    
    .ticket-icon-box {
        width: 32px;
        height: 32px;
        background: #f7f7f7;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .ticket-icon-box svg {
        width: 14px;
        height: 14px;
        stroke: #777;
        fill: none;
        stroke-width: 1.5;
    }
    
    .ticket-faq-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .ticket-faq-list li {
        font-size: 11.5px;
        color: #666;
        line-height: 1.6;
        margin-bottom: 0;
    }
</style>
@endsection
