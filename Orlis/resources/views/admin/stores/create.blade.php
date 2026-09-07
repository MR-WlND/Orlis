@extends('layouts.admin')

@section('title', 'Thêm Showroom Mới')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.stores.index') }}" style="color: #666; text-decoration: none; font-size: 13px;">&larr; Quay lại danh sách showroom</a>
</div>

<div class="page-header">
    <div class="header-text">
        <h2 class="page-title">Thêm Showroom Mới</h2>
        <p class="page-subtitle">Nhập thông tin chi tiết cho địa điểm cửa hàng mới.</p>
    </div>
</div>

<form action="{{ route('admin.stores.store') }}" method="POST">
    @csrf
    
    <div class="form-card" style="padding: 40px; max-width: 800px;">
        <h3 style="font-family: var(--font-sans); font-size: 14px; font-weight: 600; margin: 0 0 25px 0; padding-bottom: 15px; border-bottom: 1px solid #eee; text-transform: uppercase;">THÔNG TIN SHOWROOM</h3>
        
        <div class="form-group" style="margin-bottom: 25px;">
            <label style="display: block; font-size: 11px; font-weight: 600; text-transform: uppercase; color: #888; margin-bottom: 10px;">TÊN SHOWROOM *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="VD: Showroom Hoàn Kiếm, Hà Nội" required style="font-size: 14px; padding: 12px; background: transparent; border: 1px solid #ddd;">
            @error('name') <span style="color: #d93025; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 25px;">
            <label style="display: block; font-size: 11px; font-weight: 600; text-transform: uppercase; color: #888; margin-bottom: 10px;">ĐỊA CHỈ CHI TIẾT *</label>
            <input type="text" name="address" class="form-control" value="{{ old('address') }}" placeholder="VD: Tầng 1, TTTM Tràng Tiền Plaza, 24 Hai Bà Trưng, Hoàn Kiếm, Hà Nội" required style="font-size: 14px; padding: 12px; background: transparent; border: 1px solid #ddd;">
            @error('address') <span style="color: #d93025; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span> @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            <div class="form-group" style="margin-bottom: 25px;">
                <label style="display: block; font-size: 11px; font-weight: 600; text-transform: uppercase; color: #888; margin-bottom: 10px;">SỐ ĐIỆN THOẠI HOTLINE</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="VD: 0988 123 456" style="font-size: 14px; padding: 12px; background: transparent; border: 1px solid #ddd;">
                @error('phone') <span style="color: #d93025; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 25px;">
                <label style="display: block; font-size: 11px; font-weight: 600; text-transform: uppercase; color: #888; margin-bottom: 10px;">GIỜ HOẠT ĐỘNG</label>
                <input type="text" name="opening_hours" class="form-control" value="{{ old('opening_hours', '09:00 - 22:00') }}" placeholder="VD: 09:00 - 22:00" style="font-size: 14px; padding: 12px; background: transparent; border: 1px solid #ddd;">
                @error('opening_hours') <span style="color: #d93025; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span> @enderror
            </div>
        </div>

        <div style="margin-top: 20px; padding-top: 25px; border-top: 1px solid #eee;">
            <button type="submit" class="btn-submit" style="padding: 14px 40px; font-size: 13px;">TẠO SHOWROOM</button>
        </div>
    </div>
</form>

@endsection
