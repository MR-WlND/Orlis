@extends('layouts.admin')

@section('title', 'Chỉnh sửa Banner')

@section('page-style')

@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Chỉnh sửa Banner</h1>
            <p class="page-subtitle">Cập nhật hình ảnh và thông điệp truyền thông.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.banners.index') }}" class="btn-cancel">HỦY</a>
            <button type="submit" form="bannerForm" class="btn-submit">LƯU THAY ĐỔI</button>
        </div>
    </div>

    <form id="bannerForm" action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="edit-grid">
            <!-- Cột trái: Nội dung chính -->
            <div class="form-card" style="padding: 30px 40px;">
                <h2 class="card-title" style="margin-bottom: 20px;">Hình Ảnh & Nội Dung</h2>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Thay Hình Ảnh Desktop (Tùy chọn)</label>
                        <input type="file" name="image" class="form-control" accept="image/*,video/*">
                        <span class="hint">Chỉ chọn file mới nếu muốn thay đổi. Max 20MB.</span>
                        @if($banner->image_path)
                            <div style="margin-top: 10px;">
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
                    <div class="form-group">
                        <label>Thay Hình Ảnh Mobile (Tùy chọn)</label>
                        <input type="file" name="image_mobile" class="form-control" accept="image/*,video/*">
                        @if($banner->image_mobile_path)
                            <div style="margin-top: 10px;">
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

                <div class="form-group" style="margin-top: 20px;">
                    <label>Tiêu đề (Tùy chọn)</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $banner->title) }}">
                    @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group" style="margin-top: 20px;">
                    <label>Lời dẫn / Mô tả (Tùy chọn)</label>
                    <textarea name="description" class="form-control" style="resize: vertical; min-height: 80px;">{{ old('description', $banner->description) }}</textarea>
                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-top: 20px;">
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

                <div class="form-group" style="margin-top: 20px;">
                    <label>Màu chữ (Chữ sáng / Chữ tối)</label>
                    <div style="display: flex; gap: 15px; align-items: center; margin-top: 10px;">
                        <input type="color" name="text_color" value="{{ old('text_color', $banner->text_color) }}" style="cursor: pointer; width: 50px; height: 35px; border: none; padding: 0; background: none;">
                        <span class="hint">Nhấp vào ô màu để đổi màu chữ cho Tiêu đề & Lời dẫn.</span>
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
                            <input type="checkbox" name="is_active" value="1" {{ $banner->is_active ? 'checked' : '' }} style="width: 16px; height: 16px;"> Kích hoạt ngay
                        </label>
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 10px; font-weight: 600; text-transform: uppercase; color: #888; margin-bottom: 6px;">VỊ TRÍ HIỂN THỊ *</label>
                        <input type="text" name="position" class="form-control" list="position_options" value="{{ old('position', $banner->position) }}" required>
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
                        <input type="number" name="order" class="form-control" value="{{ old('order', $banner->order) }}" required>
                        @error('order') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-card" style="padding: 24px;">
                    <h3 style="font-family: var(--font-sans); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #000; margin: 0 0 16px 0; padding-bottom: 12px; border-bottom: 1px solid #eee;">LUẬT HIỂN THỊ (RULE-BASED)</h3>
                    
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 13px;">
                            <input type="checkbox" name="is_global" value="1" {{ $banner->is_global ? 'checked' : '' }} style="width: 16px; height: 16px;" onchange="document.getElementById('cat-wrapper').style.opacity = this.checked ? '0.3' : '1';"> Áp dụng Toàn hệ thống (Global)
                        </label>
                    </div>

                    <div class="form-group" id="cat-wrapper" style="margin-bottom: 20px; opacity: {{ $banner->is_global ? '0.3' : '1' }};">
                        <label style="display: block; font-size: 10px; font-weight: 600; text-transform: uppercase; color: #888; margin-bottom: 6px;">DANH MỤC ÁP DỤNG</label>
                        <select name="category_ids[]" class="form-control" multiple style="height: 120px; padding: 10px;">
                            @php $selectedCats = is_array($banner->category_ids) ? $banner->category_ids : []; @endphp
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ in_array($cat->id, $selectedCats) ? 'selected' : '' }}>{{ str_repeat('-- ', $cat->level ?? 0) }}{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <span class="hint">Giữ Ctrl/Cmd để chọn nhiều. Áp dụng kế thừa cho danh mục con.</span>
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 10px; font-weight: 600; text-transform: uppercase; color: #888; margin-bottom: 6px;">THỜI GIAN BẮT ĐẦU</label>
                        <input type="datetime-local" name="start_time" class="form-control" value="{{ $banner->start_time ? $banner->start_time->format('Y-m-d\TH:i') : '' }}">
                    </div>

                    <div class="form-group">
                        <label style="display: block; font-size: 10px; font-weight: 600; text-transform: uppercase; color: #888; margin-bottom: 6px;">THỜI GIAN KẾT THÚC</label>
                        <input type="datetime-local" name="end_time" class="form-control" value="{{ $banner->end_time ? $banner->end_time->format('Y-m-d\TH:i') : '' }}">
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
