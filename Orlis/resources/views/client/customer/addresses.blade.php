@extends('layouts.customer')
@section('customer_title', 'Sổ địa chỉ - Orlis')
@section('customer_styles')
@endsection
@section('customer_content')
<div class="section-header">
    <div>
        <div class="subtitle">THÔNG TIN LIÊN HỆ GIAO HÀNG</div>
        <h2 class="section-title">Sổ địa chỉ của tôi</h2>
    </div>
</div>

@foreach($addresses as $addr)
<div class="address-card {{ $addr->is_default ? 'default' : '' }}">
    <div class="address-card-head">
        <div class="address-name">{{ $addr->recipient_name }}</div>
        @if($addr->is_default)<span class="default-badge">Mặc định</span>@endif
    </div>
    <div class="address-phone">{{ $addr->phone }}</div>
    <div class="address-text">{{ $addr->full_address }}</div>
    <div class="address-actions">
        @if(!$addr->is_default)
        <form method="POST" action="{{ route('customer.addresses.default', $addr) }}">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn-sm btn-outline-action">Đặt làm mặc định</button>
        </form>
        @endif
        <form method="POST" action="{{ route('customer.addresses.destroy', $addr) }}" onsubmit="return confirm('Quý khách có chắc chắn muốn xóa địa chỉ này?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-sm btn-danger">Xóa</button>
        </form>
    </div>
</div>
@endforeach

<div class="add-address-card" onclick="document.getElementById('addForm').classList.toggle('show')">
    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="#aaa" stroke-width="2" style="margin-bottom:10px;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
    <div style="font-size:12px;color:#888;font-weight:600;text-transform:uppercase;letter-spacing:1px;">Thêm địa chỉ mới</div>
</div>

<div class="add-form" id="addForm">
    <form method="POST" action="{{ route('customer.addresses.store') }}">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Tên người nhận *</label>
                <input type="text" name="recipient_name" class="form-input" required value="{{ old('recipient_name') }}">
                @error('recipient_name')<div style="color:#c0392b;font-size:12px;margin-top:5px;">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Số điện thoại *</label>
                <input type="text" name="phone" class="form-input" required value="{{ old('phone') }}">
                @error('phone')<div style="color:#c0392b;font-size:12px;margin-top:5px;">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Tỉnh / Thành phố *</label>
                <input type="text" name="province" class="form-input" required value="{{ old('province') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Quận / Huyện *</label>
                <input type="text" name="district" class="form-input" required value="{{ old('district') }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Phường / Xã *</label>
                <input type="text" name="ward" class="form-input" required value="{{ old('ward') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Địa chỉ chi tiết *</label>
                <input type="text" name="detail_address" class="form-input" required value="{{ old('detail_address') }}" placeholder="Số nhà, tên đường...">
            </div>
        </div>
        <label style="display:flex;align-items:center;gap:10px;font-size:13px;margin-bottom:20px;cursor:pointer;color:#555;">
            <input type="checkbox" name="is_default" value="1" style="width:16px;height:16px;accent-color:#111;">
            Đặt làm địa chỉ giao hàng mặc định
        </label>
        <button type="submit" class="btn-save">Lưu địa chỉ</button>
    </form>
</div>

@if($errors->any())
<script>document.getElementById('addForm')?.classList.add('show');</script>
@endif
@endsection
