@extends('layouts.client')
@section('title', 'Đặt lịch hẹn - Orlis')
@section('styles')
<style>
    .booking-wrap { max-width: 680px; margin: 0 auto; padding: 100px 20px 80px; }
    .page-hero { text-align: center; margin-bottom: 48px; }
    .page-hero h1 { font-family: var(--font-serif); font-size: 34px; font-weight: 400; margin-bottom: 12px; }
    .page-hero p { font-size: 15px; color: #777; line-height: 1.7; max-width: 500px; margin: 0 auto; }
    .form-card { background: white; border-radius: 12px; border: 1px solid #ebebeb; padding: 32px; margin-bottom: 20px; }
    .form-card-title { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #aaa; margin-bottom: 20px; }
    .form-group { margin-bottom: 18px; }
    .form-label { display: block; font-size: 12px; font-weight: 600; color: #888; text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 7px; }
    .form-input, .form-select, .form-textarea {
        width: 100%; padding: 12px 14px; border: 1px solid #d8d8d8; border-radius: 6px;
        font-size: 14px; color: #333; background: white; box-sizing: border-box;
        transition: border-color 0.2s;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus { border-color: var(--primary); outline: none; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .service-options { display: flex; flex-direction: column; gap: 10px; }
    .service-option {
        border: 2px solid #e8e8e8; border-radius: 8px; padding: 14px 16px;
        cursor: pointer; transition: all 0.2s; display: flex; gap: 14px; align-items: flex-start;
    }
    .service-option:has(input:checked) { border-color: var(--primary); background: rgba(139,111,71,0.03); }
    .service-option input[type="radio"] { accent-color: var(--primary); width: 16px; height: 16px; margin-top: 2px; flex-shrink: 0; }
    .service-name { font-size: 14px; font-weight: 600; margin-bottom: 3px; }
    .service-desc { font-size: 12px; color: #999; line-height: 1.5; }
    .timeslot-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; }
    .timeslot-btn {
        border: 1px solid #e0e0e0; border-radius: 6px; padding: 10px; text-align: center;
        cursor: pointer; font-size: 13px; font-weight: 500; transition: all 0.15s;
        background: white; color: #555;
    }
    .timeslot-btn:has(input:checked) { border-color: var(--primary); background: var(--primary); color: white; }
    .timeslot-btn input { display: none; }
    .btn-submit {
        width: 100%; padding: 15px; background: #333; color: white; border: none;
        border-radius: 6px; font-size: 15px; font-weight: 600; cursor: pointer;
        letter-spacing: 0.3px; transition: background 0.2s; margin-top: 8px;
    }
    .btn-submit:hover { background: #111; }
    .error-msg { color: #c0392b; font-size: 12px; margin-top: 4px; }
    .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-size: 13px; }
    .alert-success { background: #d4edda; color: #155724; }
    .alert-error { background: #f8d7da; color: #721c24; }
    @media(max-width: 600px) { .form-row { grid-template-columns: 1fr; } .timeslot-grid { grid-template-columns: repeat(3, 1fr); } }
</style>
@endsection

@section('content')
<div style="background: #f9f8f6; min-height: 100vh;">
<div class="booking-wrap">

    <div class="page-hero">
        <h1>Đặt Lịch Hẹn Trải Nghiệm</h1>
        <p>Khám phá thế giới nước hoa cùng chuyên gia tư vấn cá nhân tại các Showroom Orlis.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('appointments.store') }}">
        @csrf

        {{-- Chọn Showroom --}}
        <div class="form-card">
            <div class="form-card-title">1. Chọn Showroom</div>
            <div class="form-group">
                <label class="form-label">Cửa hàng *</label>
                <select name="store_id" class="form-select" required>
                    <option value="">-- Chọn showroom gần bạn --</option>
                    @foreach($stores as $store)
                        <option value="{{ $store->id }}" @selected(old('store_id') == $store->id)>
                            {{ $store->name }} — {{ $store->address }}
                            @if($store->opening_hours) ({{ $store->opening_hours }})@endif
                        </option>
                    @endforeach
                </select>
                @error('store_id')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Loại dịch vụ --}}
        <div class="form-card">
            <div class="form-card-title">2. Loại Dịch Vụ</div>
            <div class="service-options">
                <label class="service-option">
                    <input type="radio" name="service_type" value="consultation" @checked(old('service_type', 'consultation') === 'consultation')>
                    <div>
                        <div class="service-name">Tư vấn Nước hoa</div>
                        <div class="service-desc">Chuyên gia phân tích cá tính và hương thơm phù hợp với bạn (45–60 phút)</div>
                    </div>
                </label>
                <label class="service-option">
                    <input type="radio" name="service_type" value="trial" @checked(old('service_type') === 'trial')>
                    <div>
                        <div class="service-name">Thử Mùi Hương</div>
                        <div class="service-desc">Trải nghiệm các bộ sưu tập mới nhất cùng hướng dẫn viên tại Showroom (30 phút)</div>
                    </div>
                </label>
                <label class="service-option">
                    <input type="radio" name="service_type" value="vip_service" @checked(old('service_type') === 'vip_service')>
                    <div>
                        <div class="service-name">VIP Experience ✨</div>
                        <div class="service-desc">Dịch vụ trải nghiệm độc quyền dành cho thành viên Diamond & Gold (90 phút)</div>
                    </div>
                </label>
            </div>
            @error('service_type')<div class="error-msg" style="margin-top:8px;">{{ $message }}</div>@enderror
        </div>

        {{-- Ngày & Giờ --}}
        <div class="form-card">
            <div class="form-card-title">3. Chọn Ngày & Giờ</div>
            <div class="form-group">
                <label class="form-label">Ngày hẹn *</label>
                <input type="date" name="appointment_date" class="form-input"
                    value="{{ old('appointment_date') }}"
                    min="{{ now()->addDay()->format('Y-m-d') }}"
                    required>
                @error('appointment_date')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Khung giờ *</label>
                <div class="timeslot-grid">
                    @foreach($timeSlots as $slot)
                    <label class="timeslot-btn">
                        <input type="radio" name="time_slot" value="{{ $slot }}" @checked(old('time_slot') === $slot) required>
                        {{ $slot }}
                    </label>
                    @endforeach
                </div>
                @error('time_slot')<div class="error-msg" style="margin-top:6px;">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Ghi chú --}}
        <div class="form-card">
            <div class="form-card-title">4. Ghi Chú (Tùy Chọn)</div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Yêu cầu đặc biệt hoặc mùi hương quan tâm</label>
                <textarea name="note" class="form-textarea" rows="3"
                    placeholder="Ví dụ: Tôi muốn thử các mùi hương gỗ phương Đông, hoặc tìm nước hoa tặng quà...">{{ old('note') }}</textarea>
            </div>
        </div>

        <button type="submit" class="btn-submit">XÁC NHẬN ĐẶT LỊCH HẸN</button>

        <p style="text-align:center;font-size:12px;color:#aaa;margin-top:12px;line-height:1.6;">
            Chúng tôi sẽ liên hệ xác nhận lịch hẹn qua email hoặc số điện thoại đã đăng ký trong vòng 24 giờ.
        </p>
    </form>

</div>
</div>
@endsection
