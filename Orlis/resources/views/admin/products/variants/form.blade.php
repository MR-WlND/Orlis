@extends('layouts.admin')

@section('title', isset($variant) ? 'Sửa Biến thể' : 'Thêm Biến thể')

@section('page-style')
<style>
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
    .form-control { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
    .btn-submit { background: #000; color: #fff; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
</style>
@endsection

@section('content')
<div style="background: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); max-width: 600px;">
    <h2 style="margin-top: 0;">{{ isset($variant) ? 'Sửa Biến thể' : 'Thêm Biến thể' }}</h2>
    <p style="color: #666; margin-bottom: 20px;">Sản phẩm: <strong>{{ $product->name }}</strong></p>

    @if ($errors->any())
        <div style="background: #fde8e8; color: #c53030; padding: 10px; margin-bottom: 15px; border-radius: 4px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($variant) ? route('admin.products.variants.update', [$product->id, $variant->id]) : route('admin.products.variants.store', $product->id) }}" method="POST">
        @csrf
        @if(isset($variant)) @method('PUT') @endif

        <div class="form-group">
            <label>Mã SKU</label>
            <input type="text" name="sku" class="form-control" value="{{ old('sku', $variant->sku ?? '') }}" required placeholder="VD: SP01-RED-M">
        </div>

        <div style="display: flex; gap: 15px;">
            <div class="form-group" style="flex: 1;">
                <label>Màu sắc</label>
                <input type="text" name="color" class="form-control" value="{{ old('color', $variant->color ?? '') }}" placeholder="VD: Đỏ, Xanh...">
            </div>
            <div class="form-group" style="flex: 1;">
                <label>Kích thước (Size)</label>
                <input type="text" name="size" class="form-control" value="{{ old('size', $variant->size ?? '') }}" placeholder="VD: S, M, L, XL...">
            </div>
        </div>

        <div class="form-group">
            <label>Tồn kho</label>
            <input type="number" name="stock_qty" class="form-control" value="{{ old('stock_qty', $variant->stock_qty ?? 0) }}" min="0" required>
        </div>

        <div class="form-group">
            <label>Giá tùy chỉnh (nếu có)</label>
            <input type="number" name="price_override" class="form-control" value="{{ old('price_override', $variant->price_override ?? '') }}" min="0" placeholder="Để trống nếu lấy giá gốc của sản phẩm">
            <small style="color: #666; font-size: 12px;">Nếu nhập giá trị, biến thể này sẽ sử dụng giá này thay vì giá gốc.</small>
        </div>

        <button type="submit" class="btn-submit">Lưu</button>
        <a href="{{ route('admin.products.variants.index', $product->id) }}" style="margin-left: 10px; color: #666; text-decoration: none;">Hủy</a>
    </form>
</div>
@endsection
