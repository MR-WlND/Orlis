@extends('layouts.admin')

@section('title', 'Cập Nhật Sản Phẩm')

@section('page-style')

@endsection

@section('content')
<h2>Cập Nhật Sản Phẩm: {{ $product->name }}</h2>

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
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Tên sản phẩm *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
        </div>
        
        <div class="grid-2">
            <div class="form-group">
                <label>Đường dẫn tĩnh (Slug)</label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug', $product->slug) }}">
            </div>
            <div class="form-group">
                <label>Mã sản phẩm (SKU)</label>
                <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}">
            </div>
        </div>

        <div class="form-group">
            <label>Danh mục *</label>
            <select name="category_id" class="form-control" required>
                <option value="">-- Chọn danh mục --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label>Giá bán ($) *</label>
                <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
            </div>
            <div class="form-group">
                <label>Giá khuyến mãi ($) - Tùy chọn</label>
                <input type="number" step="0.01" name="sale_price" class="form-control" value="{{ old('sale_price', $product->sale_price) }}">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label style="display:block; margin-bottom:5px; font-weight:600;">Mô tả chi tiết</label>
            <textarea name="description" class="form-control" rows="5">{{ old('description', $product->description) }}</textarea>
            @error('description') <span style="color:red; font-size:12px;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label style="display:block; margin-bottom:5px; font-weight:600;">Hướng dẫn Size (Bảng kích thước)</label>
            <textarea name="size_guide" class="form-control" rows="5" placeholder="Ví dụ: Form dáng chuẩn Âu, lùi 1 size so với kích thước Châu Á thông thường...">{{ old('size_guide', $product->size_guide) }}</textarea>
            @error('size_guide') <span style="color:red; font-size:12px;">{{ $message }}</span> @enderror
        </div>
        
        <div class="form-group">
            <label>Ảnh đại diện (Thumbnail mới) - Bỏ trống nếu không đổi</label>
            <input type="file" name="thumbnail" class="form-control">
            @if($product->thumbnail)
                <div style="margin-top: 10px;">
                    <img src="{{ Storage::url($product->thumbnail) }}" width="100" style="border-radius:4px;">
                </div>
            @endif
        </div>

        <div class="checkbox-group">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
            <label for="is_active">Hiển thị sản phẩm này</label>
        </div>
        
        <div class="checkbox-group" style="margin-top: 10px; margin-bottom: 20px;">
            <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
            <label for="is_featured">Đánh dấu nổi bật (Featured)</label>
        </div>
        
        <button type="submit" class="btn">Cập Nhật</button>
        <a href="{{ route('admin.products.index') }}" style="margin-left: 10px;">Quay lại</a>
    </form>
</div>
@endsection
