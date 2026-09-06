@extends('layouts.admin')

@section('title', 'Chỉnh sửa Danh mục')

@section('page-style')

@endsection

@section('content')
<form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="page-header">
        <h2 class="page-title">Chỉnh sửa Danh mục</h2>
        <div class="header-actions">
            <a href="{{ route('admin.categories.index') }}" class="btn-cancel">HỦY</a>
            <button type="submit" class="btn-submit">CẬP NHẬT THAY ĐỔI</button>
        </div>
    </div>

    @if($errors->any())
        <div style="padding: 15px 20px; background: #fff5f5; border: 1px solid #ffb8b8; color: #d93025; margin-bottom: 20px; font-size: 12px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="edit-grid">
        <!-- Cột trái: Thông tin cơ bản -->
        <div class="form-card">
            <h3 class="card-title">Thông tin cơ bản</h3>
            
            <div class="form-group">
                <label>TÊN DANH MỤC *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
            </div>
            
            <div class="form-group">
                <label>ĐƯỜNG DẪN (SLUG)</label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug', $category->slug) }}">
            </div>
            
            <div class="form-group">
                <label>DANH MỤC CHA</label>
                <select name="parent_id" class="form-control">
                    <option value="">Không có (Danh mục gốc)</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}" style="font-weight: 600;" {{ (old('parent_id', $category->parent_id) == $parent->id) ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                        @foreach($parent->children as $child)
                            <option value="{{ $child->id }}" {{ (old('parent_id', $category->parent_id) == $child->id) ? 'selected' : '' }}>
                                &nbsp;&nbsp;&nbsp;-- {{ $child->name }}
                            </option>
                        @endforeach
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label>MÔ TẢ</label>
                <textarea name="description" class="form-control">{{ old('description', $category->description) }}</textarea>
            </div>
        </div>

        <!-- Cột phải: Trạng thái & Hình ảnh -->
        <div class="right-col">
            
            <!-- Card Trạng thái -->
            <div class="form-card">
                <h3 class="card-title">Trạng thái</h3>
                <div class="radio-group">
                    <label class="radio-item">
                        <input type="radio" name="status" value="1" checked> Hiển thị
                    </label>
                    <label class="radio-item">
                        <input type="radio" name="status" value="0"> Ẩn
                    </label>
                </div>
            </div>

            <!-- Card Hình ảnh -->
            <div class="form-card">
                <h3 class="card-title">Hình ảnh đại diện</h3>
                
                <div class="img-preview">
                    @if($category->image)
                        <img src="{{ filter_var($category->image, FILTER_VALIDATE_URL) ? $category->image : Storage::url($category->image) }}" alt="Preview">
                    @else
                        <div style="height: 150px; display: flex; align-items: center; justify-content: center; color: #ccc;">Chưa có ảnh</div>
                    @endif
                </div>

                <div class="btn-change-img">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                    THAY ĐỔI HÌNH ẢNH
                    <input type="file" name="image" accept="image/png, image/jpeg, image/webp">
                </div>
                
                @if($category->image)
                    <button type="button" class="btn-remove-img">XÓA ẢNH</button>
                @endif
                
                <div class="img-hint">Hỗ trợ JPG, PNG. Tối đa 5MB.</div>
            </div>

        </div>
    </div>
</form>
@endsection
