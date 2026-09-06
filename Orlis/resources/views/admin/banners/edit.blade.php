@extends('layouts.admin')

@section('title', 'Chỉnh sửa Banner')

@section('page-style')
<style>
    .form-container {
        background: #fff;
        border: 1px solid var(--border-color);
        padding: 40px;
        max-width: 800px;
    }
    .form-group { margin-bottom: 25px; }
    .form-group label {
        display: block; font-size: 11px; font-weight: 600; color: #555;
        text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px;
    }
    .form-control {
        width: 100%; padding: 12px 15px; font-family: inherit; font-size: 14px;
        border: 1px solid #ddd; border-radius: 4px; outline: none; transition: border-color 0.2s;
    }
    .form-control:focus { border-color: #333; }
    textarea.form-control { resize: vertical; min-height: 100px; }
    select.form-control { background-color: #fff; cursor: pointer; }
    .btn-submit {
        background-color: #111; color: #fff; padding: 12px 30px; font-size: 12px;
        font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px;
        border: none; cursor: pointer; transition: background-color 0.2s;
    }
    .btn-submit:hover { background-color: #333; }
    .text-danger { color: #d93025; font-size: 12px; margin-top: 5px; display: block; }
</style>
@endsection

@section('content')
<div style="margin-bottom: 30px;">
    <a href="{{ route('admin.banners.index') }}" style="color: #666; text-decoration: none; font-size: 13px;">&larr; Quay lại danh sách</a>
    <h2 style="font-family: var(--font-serif); font-size: 28px; margin-top: 10px; font-weight: 500;">Chỉnh sửa Banner</h2>
</div>

<div class="form-container">
    <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group mb-4">
                    <label class="form-label fw-bold">Thay Hình ảnh / Video Banner (Tùy chọn)</label>
                    <input type="file" name="image" class="form-control" accept="image/*,video/*">
                    <small class="text-muted">Chỉ chọn file mới nếu muốn thay đổi. Hỗ trợ JPG, PNG, WEBP, MP4... (Tối đa 20MB)</small>
                    @if($banner->image_path)
                        <div class="mt-2">
                            @php $ext = pathinfo($banner->image_path, PATHINFO_EXTENSION); @endphp
                            @if(in_array(strtolower($ext), ['mp4', 'webm', 'ogg']))
                                <video src="{{ Storage::url($banner->image_path) }}" autoplay loop muted style="height: 100px; border-radius: 4px; object-fit: cover;"></video>
                            @else
                                <img src="{{ Storage::url($banner->image_path) }}" style="height: 100px; border-radius: 4px; object-fit: cover;" alt="Current Image">
                            @endif
                        </div>
                    @endif
                    @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-4">
                    <label class="form-label fw-bold">Thay Hình ảnh / Video Mobile (Tùy chọn)</label>
                    <input type="file" name="image_mobile" class="form-control" accept="image/*,video/*">
                    @if($banner->image_mobile_path)
                        <div class="mt-2">
                            @php $ext = pathinfo($banner->image_mobile_path, PATHINFO_EXTENSION); @endphp
                            @if(in_array(strtolower($ext), ['mp4', 'webm', 'ogg']))
                                <video src="{{ Storage::url($banner->image_mobile_path) }}" autoplay loop muted style="height: 100px; border-radius: 4px; object-fit: cover;"></video>
                            @else
                                <img src="{{ Storage::url($banner->image_mobile_path) }}" style="height: 100px; border-radius: 4px; object-fit: cover;" alt="Current Mobile Image">
                            @endif
                        </div>
                    @endif
                    @error('image_mobile') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

        <div style="background: #fcfcfc; padding: 20px; border: 1px solid #eee; margin-bottom: 25px;">
            <h4 style="margin: 0 0 15px 0; font-size: 14px; text-transform: uppercase;">Luật Hiển Thị (Rule-based)</h4>
            
            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; text-transform: none; font-size: 14px;">
                    <input type="checkbox" name="is_global" value="1" {{ $banner->is_global ? 'checked' : '' }} style="width: 18px; height: 18px;" onchange="document.getElementById('cat-wrapper').style.opacity = this.checked ? '0.3' : '1';"> Áp dụng Toàn hệ thống (Global)
                </label>
                <span style="font-size: 11px; color: #888;">Ghi đè tất cả các luật gán danh mục bên dưới.</span>
            </div>

            <div class="form-group" id="cat-wrapper" style="opacity: {{ $banner->is_global ? '0.3' : '1' }};">
                <label>Áp dụng cho Cụm Danh Mục (Giữ Ctrl / Cmd để chọn nhiều)</label>
                <select name="category_ids[]" class="form-control" multiple style="height: 120px;">
                    @php $selectedCats = is_array($banner->category_ids) ? $banner->category_ids : []; @endphp
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ in_array($cat->id, $selectedCats) ? 'selected' : '' }}>{{ str_repeat('-- ', $cat->level ?? 0) }}{{ $cat->name }}</option>
                    @endforeach
                </select>
                <span style="font-size: 11px; color: #888;">Banner sẽ tự động kế thừa (inherit) xuống tất cả danh mục con của danh mục được chọn.</span>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Thời gian Bắt đầu (Tùy chọn)</label>
                    <input type="datetime-local" name="start_time" class="form-control" value="{{ $banner->start_time ? $banner->start_time->format('Y-m-d\TH:i') : '' }}">
                </div>
                <div class="form-group">
                    <label>Thời gian Kết thúc (Tùy chọn)</label>
                    <input type="datetime-local" name="end_time" class="form-control" value="{{ $banner->end_time ? $banner->end_time->format('Y-m-d\TH:i') : '' }}">
                </div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Vị trí hiển thị (Tự nhập hoặc Chọn) *</label>
                <input type="text" name="position" class="form-control" list="position_options" value="{{ old('position', $banner->position) }}" required>
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
                <input type="number" name="order" class="form-control" value="{{ old('order', $banner->order) }}" required>
                @error('order') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Tiêu đề (Tùy chọn)</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $banner->title) }}">
            @error('title') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Màu chữ</label>
            <div style="display: flex; gap: 15px; align-items: center;">
                <input type="color" name="text_color" value="{{ old('text_color', $banner->text_color) }}" style="cursor: pointer; width: 50px; height: 35px; border: none; padding: 0;">
                <span style="font-size: 13px; color: #666;">Nhấp vào ô màu để đổi màu chữ cho Tiêu đề & Lời dẫn.</span>
            </div>
            @error('text_color') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Lời dẫn / Mô tả (Tùy chọn)</label>
            <textarea name="description" class="form-control">{{ old('description', $banner->description) }}</textarea>
            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
        </div>



        <div class="form-group">
            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; text-transform: none; font-size: 14px;">
                <input type="checkbox" name="is_active" value="1" {{ $banner->is_active ? 'checked' : '' }} style="width: 18px; height: 18px;"> Kích hoạt (Hiển thị ngay)
            </label>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Đường dẫn liên kết (Link URL)</label>
                <input type="text" name="link_url" class="form-control" value="{{ old('link_url', $banner->link_url) }}" placeholder="https://...">
                @error('link_url') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Kiểu mở Link</label>
                <select name="link_target" class="form-control">
                    <option value="_self" {{ $banner->link_target == '_self' ? 'selected' : '' }}>Tab hiện tại (_self)</option>
                    <option value="_blank" {{ $banner->link_target == '_blank' ? 'selected' : '' }}>Tab mới (_blank)</option>
                </select>
                @error('link_target') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>


        <div style="margin-top: 40px;">
            <button type="submit" class="btn-submit">Cập nhật Banner</button>
        </div>
    </form>
</div>
@endsection
