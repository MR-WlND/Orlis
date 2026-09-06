@extends('layouts.customer')
@section('customer_title', 'Sổ địa chỉ - Orlis')
@section('customer_styles')
<style>
    .address-card { background: white; border-radius: 0; border: 1px solid #eee; padding: 25px; margin-bottom: 20px; position: relative; transition: 0.3s; }
    .address-card:hover { border-color: #ddd; box-shadow: 0 5px 15px rgba(0,0,0,0.03); }
    .address-card.default { border: 1px solid #d4af37; }
    .address-card-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
    .address-name { font-weight: 600; font-size: 15px; color: #111; }
    .address-phone { font-size: 13px; color: #888; margin-bottom: 8px; }
    .address-text { font-size: 14px; color: #555; line-height: 1.5; }
    .default-badge { font-size: 10px; font-weight: 600; color: #d4af37; text-transform: uppercase; letter-spacing: 1px; padding: 4px 10px; background: rgba(212, 175, 55, 0.1); }
    .address-actions { display: flex; gap: 10px; margin-top: 20px; padding-top: 20px; border-top: 1px solid #f9f9f9; }
    .btn-sm { padding: 8px 16px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; cursor: pointer; text-decoration: none; transition: 0.3s; }
    .btn-outline-action { background: transparent; border: 1px solid #ddd; color: #555; }
    .btn-outline-action:hover { border-color: #111; background: #111; color: white; }
    .btn-danger { background: transparent; border: 1px solid #f5c6c6; color: #c0392b; }
    .btn-danger:hover { background: #c0392b; color: white; border-color: #c0392b; }
    .add-address-card { border: 1px dashed #d0d0d0; padding: 30px; text-align: center; cursor: pointer; transition: 0.3s; margin-bottom: 20px; background: #fbfbfb; }
    .add-address-card:hover { border-color: #111; background: white; }
    .add-form { background: white; border: 1px solid #eee; padding: 30px; margin-top: 20px; display: none; }
    .add-form.show { display: block; }
    .form-group { margin-bottom: 20px; }
    .form-label { display: block; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: #888; margin-bottom: 8px; }
    .form-input { width: 100%; padding: 12px 15px; border: 1px solid #e0e0e0; font-size: 14px; color: #333; box-sizing: border-box; transition: 0.3s; background: #fbfbfb; }
    .form-input:focus { border-color: #111; background: white; outline: none; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .btn-save { padding: 12px 24px; background: #111; color: white; border: none; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; cursor: pointer; transition: 0.3s; }
    .btn-save:hover { background: #333; }
    @media(max-width: 768px) { .form-row { grid-template-columns: 1fr; } }
</style>
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
