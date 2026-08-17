@extends('layouts.admin')

@section('title', 'Thêm Banner Mới')

@section('page-style')
<style>
    .form-container {
        background: #fff;
        border: 1px solid var(--border-color);
        padding: 40px;
        max-width: 800px;
    }
    .form-group {
        margin-bottom: 25px;
    }
    .form-group label {
        display: block;
        font-size: 11px;
        font-weight: 600;
        color: #555;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 10px;
    }
    .form-control {
        width: 100%;
        padding: 12px 15px;
        font-family: inherit;
        font-size: 14px;
        border: 1px solid #ddd;
        border-radius: 4px;
        outline: none;
        transition: border-color 0.2s;
    }
    .form-control:focus {
        border-color: #333;
    }
    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }
    select.form-control {
        background-color: #fff;
        cursor: pointer;
    }
    .btn-submit {
        background-color: #111;
        color: #fff;
        padding: 12px 30px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        border: none;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .btn-submit:hover {
        background-color: #333;
    }
    .text-danger {
        color: #d93025;
        font-size: 12px;
        margin-top: 5px;
        display: block;
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 30px;">
    <a href="{{ route('admin.banners.index') }}" style="color: #666; text-decoration: none; font-size: 13px;">&larr; Quay lại danh sách</a>
    <h2 style="font-family: var(--font-serif); font-size: 28px; margin-top: 10px; font-weight: 500;">Thêm Banner Mới</h2>
</div>

<div class="form-container">
    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Hình Ảnh Desktop * (Ngang 16:9)</label>
                <input type="file" name="image" class="form-control" accept="image/*" required>
                @error('image') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Hình Ảnh Mobile (Tùy chọn - Dọc 9:16)</label>
                <input type="file" name="image_mobile" class="form-control" accept="image/*">
                <span style="font-size: 11px; color: #888; display: block; margin-top: 5px;">Nếu để trống sẽ dùng Ảnh Desktop làm mặc định.</span>
                @error('image_mobile') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>

        <div style="background: #fcfcfc; padding: 20px; border: 1px solid #eee; margin-bottom: 25px;">
            <h4 style="margin: 0 0 15px 0; font-size: 14px; text-transform: uppercase;">Luật Hiển Thị (Rule-based)</h4>
            
            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; text-transform: none; font-size: 14px;">
                    <input type="checkbox" name="is_global" value="1" style="width: 18px; height: 18px;" onchange="document.getElementById('cat-wrapper').style.opacity = this.checked ? '0.3' : '1';"> Áp dụng Toàn hệ thống (Global)
                </label>
                <span style="font-size: 11px; color: #888;">Ghi đè tất cả các luật gán danh mục bên dưới.</span>
            </div>

            <div class="form-group" id="cat-wrapper">
                <label>Áp dụng cho Cụm Danh Mục (Giữ Ctrl / Cmd để chọn nhiều)</label>
                <select name="category_ids[]" class="form-control" multiple style="height: 120px;">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ str_repeat('-- ', $cat->level ?? 0) }}{{ $cat->name }}</option>
                    @endforeach
                </select>
                <span style="font-size: 11px; color: #888;">Banner sẽ tự động kế thừa (inherit) xuống tất cả danh mục con của danh mục được chọn.</span>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Thời gian Bắt đầu (Tùy chọn)</label>
                    <input type="datetime-local" name="start_time" class="form-control" value="{{ old('start_time') }}">
                </div>
                <div class="form-group">
                    <label>Thời gian Kết thúc (Tùy chọn)</label>
                    <input type="datetime-local" name="end_time" class="form-control" value="{{ old('end_time') }}">
                </div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Vị trí hiển thị (Tự nhập hoặc Chọn) *</label>
                <input type="text" name="position" class="form-control" list="position_options" required placeholder="VD: home_hero, cart_top...">
                <datalist id="position_options">
                    <option value="home_hero">Home Hero (Banner chính Thời trang)</option>
                    <option value="home_double">Home Double (Banner ghép 2 Thời trang)</option>
                    <option value="home_wide">Home Wide (Banner giữa Thời trang)</option>
                    <option value="beauty_hero">Beauty Hero (Banner chính Nước hoa)</option>
                    <option value="beauty_double">Beauty Double (Banner ghép 2 Nước hoa)</option>
                    <option value="beauty_wide">Beauty Wide (Banner giữa Nước hoa)</option>
                    <option value="category_header">Category Header (Banner đầu danh mục)</option>
                    <option value="cart_top">Cart Top (Banner đầu Giỏ hàng)</option>
                </datalist>
                @error('position') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label>Thứ tự hiển thị (Order)</label>
                <input type="number" name="order" class="form-control" value="{{ old('order', 0) }}" required>
                @error('order') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Tiêu đề (Tùy chọn)</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}">
            @error('title') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Màu chữ (Chữ sáng / Chữ tối)</label>
            <div style="display: flex; gap: 15px;">
                <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 400; text-transform: none;"><input type="radio" name="text_color" value="#FFFFFF" checked> Sáng (Trắng)</label>
                <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 400; text-transform: none;"><input type="radio" name="text_color" value="#111111"> Tối (Đen)</label>
                <input type="color" name="text_color_custom" style="margin-left: 10px; cursor: pointer;" onchange="document.querySelector('input[name=text_color][value=\'#FFFFFF\']').value = this.value; document.querySelector('input[name=text_color][value=\'#FFFFFF\']').checked = true;" title="Chọn màu tùy chỉnh">
            </div>
            @error('text_color') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Lời dẫn / Mô tả (Tùy chọn)</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Đường dẫn liên kết (Link URL)</label>
                <input type="text" name="link_url" class="form-control" value="{{ old('link_url') }}" placeholder="https://...">
                @error('link_url') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Kiểu mở Link</label>
                <select name="link_target" class="form-control">
                    <option value="_self">Tab hiện tại (_self)</option>
                    <option value="_blank">Tab mới (_blank)</option>
                </select>
                @error('link_target') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-group">
            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; text-transform: none; font-size: 14px;">
                <input type="checkbox" name="is_active" value="1" checked style="width: 18px; height: 18px;"> Kích hoạt (Hiển thị ngay)
            </label>
        </div>

        <div style="margin-top: 40px;">
            <button type="submit" class="btn-submit">Lưu Banner</button>
        </div>
    </form>
</div>
@endsection
