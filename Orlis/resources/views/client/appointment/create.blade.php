@extends('layouts.client')
@section('title', 'Đặt lịch hẹn - Orlis')
@section('styles')
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
