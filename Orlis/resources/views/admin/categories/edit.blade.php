@extends('layouts.admin')

@section('title', 'Chỉnh sửa Danh mục')

@section('page-style')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
    }
    .page-title {
        font-family: var(--font-serif);
        font-size: 32px;
        color: var(--text-primary);
        font-weight: 500;
        margin: 0;
    }
    .header-actions {
        display: flex;
        gap: 15px;
    }

    .btn-cancel {
        height: 42px;
        padding: 0 40px;
        min-width: 100px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        background: transparent;
        color: var(--text-primary);
        border: 1px solid var(--text-primary);
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-sizing: border-box;
        font-family: inherit;
    }
    .btn-cancel:hover { background: #f5f5f5; }

    .btn-submit {
        height: 42px;
        padding: 0 40px;
        min-width: 200px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        background: var(--text-primary);
        color: #fff;
        border: 1px solid var(--text-primary);
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-sizing: border-box;
        font-family: inherit;
    }
    .btn-submit:hover { background: #333; }

    .edit-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
        align-items: start;
    }

    .form-card {
        background: #fff;
        padding: 30px 40px;
        border: 1px solid var(--border-color);
    }
    .card-title {
        font-family: var(--font-serif);
        font-size: 20px;
        color: var(--text-primary);
        margin-bottom: 30px;
        font-weight: 500;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border-color);
    }

    .form-group { margin-bottom: 30px; }
    .form-group label {
        display: block;
        font-size: 9px;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 12px;
    }

    .form-control {
        width: 100%;
        padding: 10px 0;
        border: none;
        border-bottom: 1px solid var(--border-color);
        font-family: var(--font-sans);
        font-size: 14px;
        color: var(--text-primary);
        background: transparent;
        transition: border-color 0.2s;
        outline: none;
    }
    .form-control:focus { border-bottom-color: var(--text-primary); }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23000%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E");
        background-repeat: no-repeat;
        background-position: right 0px top 50%;
        background-size: 10px auto;
        cursor: pointer;
    }

    /* Right column specific */
    .right-col {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .radio-group {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    .radio-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        color: var(--text-primary);
        cursor: pointer;
    }

    .radio-item input {
        width: 14px;
        height: 14px;
        cursor: pointer;
        accent-color: #000;
        margin: 0;
    }

    .img-preview {
        width: 100%;
        background: #fdfdfd;
        border: 1px solid var(--border-color);
        padding: 10px;
        margin-bottom: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .img-preview img {
        width: 100%;
        height: auto;
        object-fit: cover;
    }

    .btn-change-img {
        width: 100%;
        padding: 10px;
        background: #fff;
        border: 1px solid var(--border-color);
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-primary);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
        margin-bottom: 15px;
        position: relative;
    }
    .btn-change-img:hover { border-color: var(--text-primary); }
    .btn-change-img input[type="file"] {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        opacity: 0;
        cursor: pointer;
    }
    
    .btn-remove-img {
        display: block;
        text-align: center;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #d93025;
        cursor: pointer;
        background: none;
        border: none;
        width: 100%;
    }
    
    .img-hint {
        text-align: center;
        font-size: 10px;
        color: var(--text-placeholder);
        margin-top: 20px;
    }
</style>
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
