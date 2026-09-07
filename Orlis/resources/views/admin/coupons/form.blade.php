@extends('layouts.admin')

@section('title', isset($coupon) ? 'Sửa Mã giảm giá' : 'Thêm Mã giảm giá')

@section('page-style')

@endsection

@section('content')
<form action="{{ isset($coupon) ? route('admin.coupons.update', $coupon->id) : route('admin.coupons.store') }}" method="POST">
    @csrf
    @if(isset($coupon)) @method('PUT') @endif
    
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ isset($coupon) ? 'Sửa Mã: ' . $coupon->code : 'Thêm Mã giảm giá' }}</h1>
            <p class="page-subtitle">Quản lý mã code, giảm giá và giới hạn sử dụng.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.coupons.index') }}" class="btn-cancel">Hủy</a>
            <button type="submit" class="btn-submit">{{ isset($coupon) ? 'Cập nhật' : 'Lưu mã' }}</button>
        </div>
    </div>

    @if ($errors->any())
        <div style="background: #fff0f0; color: #d00; padding: 15px; margin-bottom: 30px; border: 1px solid #ffcccc; font-size: 13px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="edit-grid">
        <!-- Cột trái: Thông tin chính -->
        <div class="form-card">
            <h2 class="card-title">Cài đặt chung</h2>
            
            <div class="form-group">
                <label>Mã Code <span style="color:red">*</span></label>
                <input type="text" name="code" class="form-control" value="{{ old('code', $coupon->code ?? '') }}" placeholder="VD: SUMMER2026" required style="text-transform: uppercase;">
                <span class="hint">Khách hàng sẽ nhập mã này ở bước thanh toán.</span>
            </div>

            <div class="form-group">
                <label>Giới hạn số lần sử dụng (Tổng số lượt)</label>
                <input type="number" name="max_uses" class="form-control" value="{{ old('max_uses', $coupon->max_uses ?? '') }}" placeholder="Để trống nếu không giới hạn" min="1">
            </div>

            <div class="form-group">
                <label>Ngày hết hạn</label>
                <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at', isset($coupon) && $coupon->expires_at ? $coupon->expires_at->format('Y-m-d\TH:i') : '') }}">
                <span class="hint">Để trống nếu mã có hiệu lực vĩnh viễn.</span>
            </div>
        </div>

        <!-- Cột phải: Giá trị giảm -->
        <div class="right-col">
            <div class="form-card">
                <h2 class="card-title">Giá trị giảm</h2>
                
                <div class="form-group">
                    <label>Giảm theo phần trăm (%)</label>
                    <input type="number" name="discount_percent" class="form-control" value="{{ old('discount_percent', $coupon->discount_percent ?? '') }}" placeholder="0 - 100" min="0" max="100">
                </div>
                
                <div class="form-group">
                    <label>Hoặc Giảm số tiền cố định (VNĐ)</label>
                    <input type="number" name="discount_amount" class="form-control" value="{{ old('discount_amount', $coupon->discount_amount ?? '') }}" placeholder="VD: 50000" min="0">
                </div>
                
                <div class="form-group" style="margin-top:-10px;">
                    <span class="hint" style="color:#d48806;">Lưu ý: Chỉ điền 1 trong 2 ô trên.</span>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
