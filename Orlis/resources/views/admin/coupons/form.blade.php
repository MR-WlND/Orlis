@extends('layouts.admin')

@section('title', isset($coupon) ? 'Sửa Mã giảm giá' : 'Thêm Mã giảm giá')

@section('page-style')

@endsection

@section('content')
<div style="background: #fff; border-radius: 8px; padding: 30px; max-width: 800px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <h2 style="margin-top: 0; margin-bottom: 24px; font-size: 20px;">{{ isset($coupon) ? 'Sửa Mã: ' . $coupon->code : 'Tạo Mã giảm giá mới' }}</h2>

    @if ($errors->any())
        <div style="background: #fff1f0; color: #f5222d; padding: 15px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #ffa39e;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($coupon) ? route('admin.coupons.update', $coupon->id) : route('admin.coupons.store') }}" method="POST">
        @csrf
        @if(isset($coupon)) @method('PUT') @endif

        <div class="form-group">
            <label>Mã Code <span style="color:red">*</span></label>
            <input type="text" name="code" class="form-control" value="{{ old('code', $coupon->code ?? '') }}" placeholder="VD: SUMMER2026" required style="text-transform: uppercase;">
            <span class="hint">Khách hàng sẽ nhập mã này ở bước thanh toán.</span>
        </div>

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label>Giảm theo phần trăm (%)</label>
                    <input type="number" name="discount_percent" class="form-control" value="{{ old('discount_percent', $coupon->discount_percent ?? '') }}" placeholder="0 - 100" min="0" max="100">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label>Hoặc Giảm số tiền cố định (VNĐ)</label>
                    <input type="number" name="discount_amount" class="form-control" value="{{ old('discount_amount', $coupon->discount_amount ?? '') }}" placeholder="VD: 50000" min="0">
                </div>
            </div>
        </div>
        <div class="form-group" style="margin-top:-10px;">
            <span class="hint" style="color:#d48806;">Lưu ý: Chỉ điền 1 trong 2 ô trên.</span>
        </div>

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label>Giới hạn số lần sử dụng (Tổng số lượt)</label>
                    <input type="number" name="max_uses" class="form-control" value="{{ old('max_uses', $coupon->max_uses ?? '') }}" placeholder="Để trống nếu không giới hạn" min="1">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label>Ngày hết hạn</label>
                    <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at', isset($coupon) && $coupon->expires_at ? $coupon->expires_at->format('Y-m-d\TH:i') : '') }}">
                    <span class="hint">Để trống nếu mã có hiệu lực vĩnh viễn.</span>
                </div>
            </div>
        </div>

        <div style="margin-top: 30px; display: flex; gap: 15px;">
            <button type="submit" style="background: #000; color: #fff; border: none; padding: 10px 24px; border-radius: 4px; cursor: pointer; font-weight: 500;">{{ isset($coupon) ? 'Cập nhật' : 'Lưu mã giảm giá' }}</button>
            <a href="{{ route('admin.coupons.index') }}" style="display: inline-block; padding: 10px 24px; border: 1px solid #ddd; color: #333; text-decoration: none; border-radius: 4px;">Hủy</a>
        </div>
    </form>
</div>
@endsection
