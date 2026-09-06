@extends('layouts.admin')

@section('title', 'Thêm Sản Phẩm')

@section('page-style')
<style>
    .form-group { margin-bottom: 15px; }
    .form-control {
        width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;
    }
    .btn { padding: 10px 20px; background-color: var(--accent); color: white; border: none; border-radius: 4px; cursor: pointer; }
    .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); max-width: 800px; }
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
    .checkbox-group { display: flex; align-items: center; gap: 10px; margin-top: 20px; }
    .checkbox-group input { width: 18px; height: 18px; }
</style>
@endsection

@section('content')
<h2>Thêm Sản Phẩm Mới</h2>

@if($errors->any())
    <div style="padding: 10px; background: #f8d7da; color: #721c24; margin-bottom: 15px; border-radius: 4px;">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Tên sản phẩm *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        
        <div class="grid-2">
            <div class="form-group">
                <label>Đường dẫn tĩnh (Slug) - Bỏ trống để tự tạo</label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug') }}">
            </div>
            <div class="form-group">
                <label>Mã sản phẩm (SKU) - Bỏ trống để tự tạo</label>
                <input type="text" name="sku" class="form-control" value="{{ old('sku') }}">
            </div>
        </div>

        <div class="form-group">
            <label>Danh mục *</label>
            <select name="category_id" class="form-control" required>
                <option value="">-- Chọn danh mục --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label>Giá bán ($) *</label>
                <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}" required>
            </div>
            <div class="form-group">
                <label>Giá khuyến mãi ($) - Tùy chọn</label>
                <input type="number" step="0.01" name="sale_price" class="form-control" value="{{ old('sale_price') }}">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label style="display:block; margin-bottom:5px; font-weight:600;">Mô tả chi tiết</label>
            <textarea name="description" class="form-control" rows="5">{{ old('description') }}</textarea>
            @error('description') <span style="color:red; font-size:12px;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label style="display:block; margin-bottom:5px; font-weight:600;">Hướng dẫn Size (Bảng kích thước)</label>
            <textarea name="size_guide" class="form-control" rows="5" placeholder="Ví dụ: Form dáng chuẩn Âu, lùi 1 size so với kích thước Châu Á thông thường...">{{ old('size_guide') }}</textarea>
            @error('size_guide') <span style="color:red; font-size:12px;">{{ $message }}</span> @enderror
        </div>
        
        <div class="form-group">
            <label>Ảnh đại diện (Thumbnail)</label>
            <input type="file" name="thumbnail" class="form-control">
        </div>

        <div class="checkbox-group">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
            <label for="is_active">Hiển thị sản phẩm này</label>
        </div>
        
        <div class="checkbox-group" style="margin-top: 10px; margin-bottom: 20px;">
            <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
            <label for="is_featured">Đánh dấu nổi bật (Featured)</label>
        </div>
        
        <button type="submit" class="btn">Lưu Sản Phẩm</button>
        <a href="{{ route('admin.products.index') }}" style="margin-left: 10px;">Quay lại</a>
    </form>
</div>
@endsection
