@extends('layouts.admin')

@section('title', 'Thêm Danh Mục')

@section('page-style')
<style>
    .page-header {
        margin-bottom: 40px;
    }
    .page-title {
        font-family: var(--font-serif);
        font-size: 32px;
        color: var(--text-primary);
        font-weight: 500;
        margin: 0 0 8px 0;
    }
    .page-subtitle {
        font-family: var(--font-sans);
        font-size: 13px;
        color: var(--text-secondary);
        margin: 0;
    }

    .form-card {
        background: #fff;
        border: 1px solid var(--border-color);
        padding: 0;
    }

    .form-body {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        padding: 40px;
    }

    .form-col-left {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .form-col-right {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        display: block;
        font-size: 10px;
        font-weight: 600;
        color: var(--text-primary);
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 12px;
    }

    .form-control {
        width: 100%;
        padding: 12px 0;
        border: none;
        border-bottom: 1px solid var(--border-color);
        font-family: var(--font-sans);
        font-size: 13px;
        color: var(--text-primary);
        background: transparent;
        transition: border-color 0.2s;
        outline: none;
    }
    .form-control::placeholder {
        color: var(--text-placeholder);
    }
    .form-control:focus {
        border-bottom-color: var(--text-primary);
    }
    
    textarea.form-control {
        border: 1px solid var(--border-color);
        padding: 15px;
        resize: vertical;
        min-height: 120px;
    }
    textarea.form-control:focus {
        border-color: var(--text-primary);
    }

    select.form-control {
        padding-right: 20px;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23000%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E");
        background-repeat: no-repeat;
        background-position: right 0px top 50%;
        background-size: 10px auto;
    }

    /* Upload Area */
    .upload-area {
        border: 1px dashed #ccc;
        background-color: #fcfcfc;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 50px 20px;
        text-align: center;
        position: relative;
        cursor: pointer;
        transition: all 0.2s;
        flex: 1;
    }
    .upload-area:hover {
        border-color: var(--text-primary);
        background-color: #f9f9f9;
    }
    .upload-icon {
        width: 32px;
        height: 32px;
        margin-bottom: 20px;
        stroke: #b0b0b0;
        fill: none;
        stroke-width: 1.5;
    }
    .upload-text {
        font-size: 12px;
        color: var(--text-primary);
        margin-bottom: 8px;
    }
    .upload-text-bold {
        font-weight: 600;
        text-decoration: underline;
        text-underline-offset: 3px;
    }
    .upload-hint {
        font-size: 10px;
        color: var(--text-placeholder);
        line-height: 1.6;
    }
    .upload-input {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .form-footer {
        padding: 25px 40px;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        background: #fff;
    }

    .btn-cancel {
        padding: 12px 30px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        background: #fff;
        color: var(--text-primary);
        border: 1px solid var(--text-primary);
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-cancel:hover { background: #f5f5f5; }

    .btn-submit {
        padding: 12px 30px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        background: var(--text-primary);
        color: #fff;
        border: 1px solid var(--text-primary);
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-submit:hover { background: #333; }
</style>
@endsection

@section('content')
<div class="page-header">
    <h2 class="page-title">Thêm Danh Mục Mới</h2>
    <p class="page-subtitle">Tạo danh mục phân loại sản phẩm mới cho Atelier.</p>
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

<form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-card">
        <div class="form-body">
            <!-- Cột trái -->
            <div class="form-col-left">
                <div class="form-group">
                    <label>TÊN DANH MỤC *</label>
                    <input type="text" name="name" class="form-control" placeholder="VD: Haute Couture" value="{{ old('name') }}" required>
                </div>
                
                <div class="form-group">
                    <label>ĐƯỜNG DẪN (SLUG)</label>
                    <input type="text" name="slug" class="form-control" placeholder="Bỏ trống để tạo tự động" value="{{ old('slug') }}">
                </div>
                
                <div class="form-group">
                    <label>DANH MỤC CHA</label>
                    <select name="parent_id" class="form-control">
                        <option value="">Không có (Danh mục gốc)</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>MÔ TẢ</label>
                    <textarea name="description" class="form-control" placeholder="Nhập mô tả chi tiết cho danh mục này...">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label>TRẠNG THÁI</label>
                    <div style="display: flex; gap: 20px; align-items: center; margin-top: 5px;">
                        <label style="display: flex; align-items: center; gap: 8px; text-transform: none; font-size: 13px; font-weight: 400; color: var(--text-primary); cursor: pointer;">
                            <input type="radio" name="status" value="1" checked style="width: 14px; height: 14px; margin: 0; cursor: pointer; accent-color: #000;"> Active (Hiển thị)
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; text-transform: none; font-size: 13px; font-weight: 400; color: var(--text-primary); cursor: pointer;">
                            <input type="radio" name="status" value="0" style="width: 14px; height: 14px; margin: 0; cursor: pointer; accent-color: #000;"> Hidden (Ẩn)
                        </label>
                    </div>
                </div>
            </div>

            <!-- Cột phải -->
            <div class="form-col-right">
                <div class="form-group" style="height: 100%; display: flex; flex-direction: column;">
                    <label>TẢI LÊN HÌNH ẢNH</label>
                    <div class="upload-area">
                        <input type="file" name="image" class="upload-input" accept="image/png, image/jpeg, image/webp">
                        <svg class="upload-icon" viewBox="0 0 24 24"><path d="M21.2 15c.7-1.2 1-2.5.7-3.9-.6-2-2.4-3.5-4.4-3.5h-1.2c-.3-1.2-.9-2.3-1.8-3.1C13.2 3.3 11.2 3 9.6 3.6c-2 .7-3.5 2.5-3.6 4.6 0 .2 0 .4.1.6-1.5.3-2.7 1.5-3 3-.3 1.6.4 3.1 1.7 4C5.6 16.5 6.8 17 8 17h12c1.7 0 3-1.4 3-3 0-.8-.3-1.5-.8-2z"></path><path d="M12 12v9"></path><path d="M8 15l4-4 4 4"></path></svg>
                        <div class="upload-text">Kéo thả hình ảnh vào đây hoặc</div>
                        <div class="upload-text upload-text-bold">DUYỆT TẬP TIN</div>
                        <div class="upload-hint" style="margin-top: 15px;">JPG, PNG (Tối đa 5MB)<br>Tỉ lệ 1:1 khuyến nghị</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-footer">
            <a href="{{ route('admin.categories.index') }}" class="btn-cancel">HỦY</a>
            <button type="submit" class="btn-submit">LƯU DANH MỤC</button>
        </div>
    </div>
</form>
@endsection
