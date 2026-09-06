@extends('layouts.admin')

@section('title', 'Quản lý Sản phẩm')

@section('page-style')

@endsection

@section('content')
<div class="page-header">
    <h2>Danh sách Sản phẩm</h2>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Thêm Sản phẩm</a>
</div>

@if(session('success'))
    <div style="padding: 10px; background: #d4edda; color: #155724; margin-bottom: 15px; border-radius: 4px;">
        {{ session('success') }}
    </div>
@endif

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Hình ảnh</th>
            <th>Tên sản phẩm</th>
            <th>Mã (SKU)</th>
            <th>Danh mục</th>
            <th>Giá</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $prod)
        <tr>
            <td>{{ $prod->id }}</td>
            <td>
                @if($prod->thumbnail)
                    <img src="{{ Storage::url($prod->thumbnail) }}" width="50" height="50" style="object-fit:cover; border-radius:4px;">
                @else
                    <span style="color:#999; font-style:italic;">Không có</span>
                @endif
            </td>
            <td>{{ $prod->name }}</td>
            <td>{{ $prod->sku }}</td>
            <td>{{ $prod->category ? $prod->category->name : 'N/A' }}</td>
            <td>${{ number_format($prod->price, 2) }}</td>
            <td>
                @if($prod->is_active)
                    <span class="badge badge-success">Hiện</span>
                @else
                    <span class="badge badge-warning">Ẩn</span>
                @endif
            </td>
            <td>
                <a href="{{ route('admin.products.variants.index', $prod->id) }}" class="btn btn-sm" style="background:#52c41a; color:white;">Biến thể</a>
                <a href="{{ route('admin.products.edit', $prod->id) }}" class="btn btn-info btn-sm">Sửa</a>
                <form action="{{ route('admin.products.destroy', $prod->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="pagination-container">
    <div class="pagination-info">
        Hiển thị {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} trên tổng số {{ $products->total() ?? 0 }} sản phẩm
    </div>
    <div class="pagination-buttons">
        @if ($products->onFirstPage())
            <button class="btn-page" disabled style="opacity: 0.5; cursor: not-allowed;">TRANG TRƯỚC</button>
        @else
            <a href="{{ $products->previousPageUrl() }}" class="btn-page">TRANG TRƯỚC</a>
        @endif

        @if ($products->hasMorePages())
            <a href="{{ $products->nextPageUrl() }}" class="btn-page">TIẾP THEO</a>
        @else
            <button class="btn-page" disabled style="opacity: 0.5; cursor: not-allowed;">TIẾP THEO</button>
        @endif
    </div>
</div>
@endsection
