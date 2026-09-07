@extends('layouts.admin')

@section('title', 'Thêm Banner Mới')

@section('page-style')

@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Thêm Banner Mới</h1>
            <p class="page-subtitle">Thiết lập hình ảnh và thông điệp truyền thông.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.banners.index') }}" class="btn-cancel">HỦY</a>
            <button type="submit" form="bannerForm" class="btn-submit">LƯU BANNER</button>
        </div>
    </div>

    <form id="bannerForm" action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="edit-grid">
            <!-- Cột trái: Nội dung chính -->
            <div class="form-card" style="padding: 30px 40px;">
                <h2 class="card-title" style="margin-bottom: 20px;">Hình Ảnh & Nội Dung</h2>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Hình Ảnh Desktop * (Ngang 16:9)</label>
                        <input type="file" name="image" class="form-control" accept="image/*,video/*" required>
                        <span class="hint">Định dạng hỗ trợ: JPG, PNG, WEBP, MP4... Max 20MB.</span>
                        @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Hình Ảnh Mobile (Dọc 9:16)</label>
                        <input type="file" name="image_mobile" class="form-control" accept="image/*,video/*">
                        <span class="hint">Nếu để trống sẽ dùng Ảnh Desktop làm mặc định.</span>
                        @error('image_mobile') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group" style="margin-top: 20px;">
                    <label>Tiêu đề (Tùy chọn)</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                    @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group" style="margin-top: 20px;">
                    <label>Lời dẫn / Mô tả (Tùy chọn)</label>
                    <textarea name="description" class="form-control" style="resize: vertical; min-height: 80px;">{{ old('description') }}</textarea>
                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-top: 20px;">
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

                <div class="form-group" style="margin-top: 20px;">
                    <label>Màu chữ (Chữ sáng / Chữ tối)</label>
                    <div style="display: flex; gap: 20px; margin-top: 10px;">
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; cursor: pointer;"><input type="radio" name="text_color" value="#FFFFFF" checked> Sáng (Trắng)</label>
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; cursor: pointer;"><input type="radio" name="text_color" value="#111111"> Tối (Đen)</label>
                        <input type="color" name="text_color_custom" style="cursor: pointer; height: 24px; border: none; background: none;" onchange="document.querySelector('input[name=text_color][value=\'#FFFFFF\']').value = this.value; document.querySelector('input[name=text_color][value=\'#FFFFFF\']').checked = true;" title="Chọn màu tùy chỉnh">
                    </div>
                    @error('text_color') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Cột phải: Cài đặt hiển thị -->
            <div class="right-col">
                <div class="form-card" style="padding: 24px;">
                    <h3 style="font-family: var(--font-sans); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #000; margin: 0 0 16px 0; padding-bottom: 12px; border-bottom: 1px solid #eee;">CÀI ĐẶT HIỂN THỊ</h3>
                    
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 10px; font-weight: 600; text-transform: uppercase; color: #888; margin-bottom: 6px;">TRẠNG THÁI</label>
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 14px;">
                            <input type="checkbox" name="is_active" value="1" checked style="width: 16px; height: 16px;"> Kích hoạt ngay
                        </label>
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 10px; font-weight: 600; text-transform: uppercase; color: #888; margin-bottom: 6px;">VỊ TRÍ HIỂN THỊ *</label>
                        <input type="text" name="position" class="form-control" list="position_options" required placeholder="VD: home_hero, cart_top...">
                        <datalist id="position_options">
                            <option value="home_hero">Home Hero</option>
                            <option value="home_double">Home Double</option>
                            <option value="beauty_hero">Beauty Hero</option>
                            <option value="category_header">Category Header</option>
                        </datalist>
                        @error('position') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 10px; font-weight: 600; text-transform: uppercase; color: #888; margin-bottom: 6px;">THỨ TỰ (ORDER)</label>
                        <input type="number" name="order" class="form-control" value="{{ old('order', 0) }}" required>
                        @error('order') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-card" style="padding: 24px;">
                    <h3 style="font-family: var(--font-sans); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #000; margin: 0 0 16px 0; padding-bottom: 12px; border-bottom: 1px solid #eee;">LUẬT HIỂN THỊ (RULE-BASED)</h3>
                    
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 13px;">
                            <input type="checkbox" name="is_global" value="1" style="width: 16px; height: 16px;" onchange="document.getElementById('cat-wrapper').style.opacity = this.checked ? '0.3' : '1';"> Áp dụng Toàn hệ thống (Global)
                        </label>
                    </div>

                    <div class="form-group" id="cat-wrapper" style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 10px; font-weight: 600; text-transform: uppercase; color: #888; margin-bottom: 6px;">DANH MỤC ÁP DỤNG</label>
                        <select name="category_ids[]" class="form-control" multiple style="height: 120px; padding: 10px;">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ str_repeat('-- ', $cat->level ?? 0) }}{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <span class="hint">Giữ Ctrl/Cmd để chọn nhiều. Áp dụng kế thừa cho danh mục con.</span>
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 10px; font-weight: 600; text-transform: uppercase; color: #888; margin-bottom: 6px;">THỜI GIAN BẮT ĐẦU</label>
                        <input type="datetime-local" name="start_time" class="form-control" value="{{ old('start_time') }}">
                    </div>

                    <div class="form-group">
                        <label style="display: block; font-size: 10px; font-weight: 600; text-transform: uppercase; color: #888; margin-bottom: 6px;">THỜI GIAN KẾT THÚC</label>
                        <input type="datetime-local" name="end_time" class="form-control" value="{{ old('end_time') }}">
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
