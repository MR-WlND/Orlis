@extends('layouts.admin')

@section('title', 'Cập nhật Showroom')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.stores.index') }}" style="color: #666; text-decoration: none; font-size: 13px;">&larr; Quay lại danh sách showroom</a>
</div>

<div class="page-header">
    <div class="header-text">
        <h2 class="page-title">Cập nhật Showroom</h2>
        <p class="page-subtitle">Chỉnh sửa thông tin cho {{ $store->name }}.</p>
    </div>
</div>

<form action="{{ route('admin.stores.update', $store) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="form-card" style="padding: 40px; max-width: 800px;">
        <h3 style="font-family: var(--font-sans); font-size: 14px; font-weight: 600; margin: 0 0 25px 0; padding-bottom: 15px; border-bottom: 1px solid #eee; text-transform: uppercase;">THÔNG TIN SHOWROOM</h3>
        
        <div class="form-group" style="margin-bottom: 25px;">
            <label style="display: block; font-size: 11px; font-weight: 600; text-transform: uppercase; color: #888; margin-bottom: 10px;">TÊN SHOWROOM *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $store->name) }}" required style="font-size: 14px; padding: 12px; background: transparent; border: 1px solid #ddd;">
            @error('name') <span style="color: #d93025; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 25px;">
            <label style="display: block; font-size: 11px; font-weight: 600; text-transform: uppercase; color: #888; margin-bottom: 10px;">ĐỊA CHỈ CHI TIẾT *</label>
            <input type="text" name="address" class="form-control" value="{{ old('address', $store->address) }}" required style="font-size: 14px; padding: 12px; background: transparent; border: 1px solid #ddd;">
            @error('address') <span style="color: #d93025; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span> @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            <div class="form-group" style="margin-bottom: 25px;">
                <label style="display: block; font-size: 11px; font-weight: 600; text-transform: uppercase; color: #888; margin-bottom: 10px;">SỐ ĐIỆN THOẠI HOTLINE</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $store->phone) }}" style="font-size: 14px; padding: 12px; background: transparent; border: 1px solid #ddd;">
                @error('phone') <span style="color: #d93025; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 25px;">
                <label style="display: block; font-size: 11px; font-weight: 600; text-transform: uppercase; color: #888; margin-bottom: 10px;">GIỜ HOẠT ĐỘNG</label>
                <input type="text" name="opening_hours" class="form-control" value="{{ old('opening_hours', $store->opening_hours) }}" style="font-size: 14px; padding: 12px; background: transparent; border: 1px solid #ddd;">
                @error('opening_hours') <span style="color: #d93025; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span> @enderror
            </div>
        </div>

        <div style="margin-top: 20px; padding-top: 25px; border-top: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
            <button type="submit" class="btn-submit" style="padding: 14px 40px; font-size: 13px;">LƯU THAY ĐỔI</button>
        </div>
    </div>
</form>

<form action="{{ route('admin.stores.destroy', $store) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa showroom này?');" style="margin-top: 20px; max-width: 800px; text-align: right;">
    @csrf
    @method('DELETE')
    <button type="submit" style="background: none; border: none; color: #d93025; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: underline;">Xóa showroom này</button>
</form>

@endsection
