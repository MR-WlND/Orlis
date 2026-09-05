@extends('layouts.client')
@section('title', 'Địa chỉ của tôi - Orlis')
@section('styles')
<style>
    .customer-wrap { max-width: 1060px; margin: 0 auto; padding: 100px 20px 60px; display: grid; grid-template-columns: 220px 1fr; gap: 32px; }
    .sidebar-nav { position: sticky; top: 90px; height: fit-content; }
    .sidebar-nav .user-info { padding-bottom: 20px; border-bottom: 1px solid #e8e8e8; margin-bottom: 16px; }
    .avatar-circle { width: 56px; height: 56px; border-radius: 50%; background: #f0ece6; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 20px; color: var(--primary); margin-bottom: 10px; overflow: hidden; }
    .user-name { font-weight: 600; font-size: 15px; margin-bottom: 2px; }
    .user-level { font-size: 12px; color: #999; }
    .nav-link { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 6px; text-decoration: none; color: #555; font-size: 14px; transition: all 0.15s; margin-bottom: 2px; }
    .nav-link:hover, .nav-link.active { background: #f5f0ea; color: var(--primary); font-weight: 500; }
    .nav-link svg { width: 16px; height: 16px; stroke: currentColor; fill: none; stroke-width: 2; flex-shrink: 0; }
    .section-title { font-family: var(--font-serif); font-size: 18px; font-weight: 500; margin-bottom: 18px; }
    .address-card { background: white; border-radius: 10px; border: 2px solid #efefef; padding: 18px 20px; margin-bottom: 12px; position: relative; }
    .address-card.default { border-color: var(--primary); }
    .address-card-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; }
    .address-name { font-weight: 600; font-size: 15px; }
    .address-phone { font-size: 13px; color: #888; margin-bottom: 4px; }
    .address-text { font-size: 14px; color: #555; }
    .default-badge { font-size: 11px; font-weight: 600; color: var(--primary); background: rgba(139,111,71,0.1); padding: 3px 8px; border-radius: 10px; }
    .address-actions { display: flex; gap: 10px; margin-top: 12px; }
    .btn-sm { padding: 6px 14px; border-radius: 4px; font-size: 12px; font-weight: 500; cursor: pointer; text-decoration: none; transition: all 0.15s; }
    .btn-outline { background: transparent; border: 1px solid #d0d0d0; color: #555; }
    .btn-outline:hover { border-color: var(--primary); color: var(--primary); }
    .btn-danger { background: transparent; border: 1px solid #f5c6c6; color: #c0392b; }
    .btn-danger:hover { background: #c0392b; color: white; border-color: #c0392b; }
    .add-address-card { border: 2px dashed #d0d0d0; border-radius: 10px; padding: 24px; text-align: center; cursor: pointer; transition: all 0.2s; margin-bottom: 12px; }
    .add-address-card:hover { border-color: var(--primary); }
    .add-form { background: white; border-radius: 10px; border: 1px solid #efefef; padding: 24px; margin-top: 16px; display: none; }
    .add-form.show { display: block; }
    .form-group { margin-bottom: 14px; }
    .form-label { display: block; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #888; margin-bottom: 6px; }
    .form-input { width: 100%; padding: 10px 14px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 14px; color: #333; box-sizing: border-box; }
    .form-input:focus { border-color: var(--primary); outline: none; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .btn-save { padding: 11px 28px; background: #333; color: white; border: none; border-radius: 4px; font-size: 13px; font-weight: 600; cursor: pointer; }
    @media(max-width: 768px) { .customer-wrap { grid-template-columns: 1fr; } .sidebar-nav { position: static; } .form-row { grid-template-columns: 1fr; } }
</style>
@endsection
@section('content')
<div style="background: #f8f7f5; min-height: 100vh;">
<div class="customer-wrap">
    @include('client.customer._sidebar')
    <div>
        @if(session('success'))
            <div style="background:#d4edda;color:#155724;padding:12px 16px;border-radius:6px;margin-bottom:20px;font-size:13px;">{{ session('success') }}</div>
        @endif

        <h2 class="section-title">Địa chỉ giao hàng</h2>

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
                    <button type="submit" class="btn-sm btn-outline">Đặt làm mặc định</button>
                </form>
                @endif
                <form method="POST" action="{{ route('customer.addresses.destroy', $addr) }}" onsubmit="return confirm('Xóa địa chỉ này?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-sm btn-danger">Xóa</button>
                </form>
            </div>
        </div>
        @endforeach

        <div class="add-address-card" onclick="document.getElementById('addForm').classList.toggle('show')">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="#aaa" stroke-width="2" style="margin-bottom:6px;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <div style="font-size:14px;color:#aaa;font-weight:500;">Thêm địa chỉ mới</div>
        </div>

        <div class="add-form" id="addForm">
            <form method="POST" action="{{ route('customer.addresses.store') }}">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tên người nhận *</label>
                        <input type="text" name="recipient_name" class="form-input" required value="{{ old('recipient_name') }}">
                        @error('recipient_name')<div style="color:#c0392b;font-size:12px;margin-top:3px;">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Số điện thoại *</label>
                        <input type="text" name="phone" class="form-input" required value="{{ old('phone') }}">
                        @error('phone')<div style="color:#c0392b;font-size:12px;margin-top:3px;">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tỉnh / Thành phố *</label>
                        <input type="text" name="province" class="form-input" required value="{{ old('province') }}" placeholder="TP. Hồ Chí Minh">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Quận / Huyện *</label>
                        <input type="text" name="district" class="form-input" required value="{{ old('district') }}" placeholder="Quận 1">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Phường / Xã *</label>
                        <input type="text" name="ward" class="form-input" required value="{{ old('ward') }}" placeholder="Phường Bến Nghé">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Địa chỉ chi tiết *</label>
                        <input type="text" name="detail_address" class="form-input" required value="{{ old('detail_address') }}" placeholder="Số nhà, tên đường...">
                    </div>
                </div>
                <label style="display:flex;align-items:center;gap:8px;font-size:13px;margin-bottom:14px;cursor:pointer;">
                    <input type="checkbox" name="is_default" value="1" style="width:15px;height:15px;accent-color:var(--primary);">
                    Đặt làm địa chỉ mặc định
                </label>
                <button type="submit" class="btn-save">Lưu địa chỉ</button>
            </form>
        </div>
    </div>
</div>
</div>

@if($errors->any())
<script>document.getElementById('addForm')?.classList.add('show');</script>
@endif
@endsection
