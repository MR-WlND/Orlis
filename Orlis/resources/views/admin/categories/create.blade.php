@extends('layouts.admin')

@section('title', 'Thêm Danh Mục')

@section('page-style')

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
                            <option value="{{ $parent->id }}" style="font-weight: 600;">{{ $parent->name }}</option>
                            @foreach($parent->children as $child)
                                <option value="{{ $child->id }}">&nbsp;&nbsp;&nbsp;-- {{ $child->name }}</option>
                            @endforeach
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
