@extends('layouts.admin')
@section('title', 'Danh sách Sản phẩm')
@section('content')

<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <div class="header-text">
        <h2 class="page-title">Quản Lý Sản Phẩm</h2>
        <p class="page-subtitle">Danh sách toàn bộ sản phẩm trong hệ thống.</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.products.create') }}" class="btn-submit" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center; height: 42px; padding: 0 25px;">+ THÊM SẢN PHẨM</a>
    </div>
</div>

@if(session('success'))
<div style="padding: 15px 20px; background: #fff; border: 1px solid #000; color: #000; margin-bottom: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
    {{ session('success') }}
</div>
@endif

<div class="table-container">
    <table class="luxury-table">
        <thead>
            <tr>
                <th>SẢN PHẨM</th>
                <th>SKU</th>
                <th>DANH MỤC</th>
                <th style="text-align: right;">GIÁ</th>
                <th style="text-align: center;">TRẠNG THÁI</th>
                <th style="text-align: right;">THAO TÁC</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $prod)
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 15px;">
                        @if($prod->thumbnail)
                            <img src="{{ Storage::url($prod->thumbnail) }}" width="44" height="44" style="object-fit: cover; border: 1px solid #eee; flex-shrink: 0;">
                        @else
                            <div style="width: 44px; height: 44px; background: #f5f5f5; border: 1px solid #eee; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #bbb;">N/A</div>
                        @endif
                        <span style="font-weight: 600; font-size: 13px; color: #111;">{{ $prod->name }}</span>
                    </div>
                </td>
                <td><span style="font-size: 12px; color: #999; font-family: monospace;">{{ $prod->sku }}</span></td>
                <td><span style="font-size: 13px; color: #666;">{{ $prod->category ? $prod->category->name : '—' }}</span></td>
                <td style="text-align: right; font-weight: 600; font-size: 13px; font-family: var(--font-sans);">{{ number_format($prod->price, 0, ',', '.') }}₫</td>
                <td style="text-align: center;">
                    @if($prod->is_active)
                        <span class="status-badge" style="background: #e8f5e9; color: #2e7d32; border: none;">Hiển thị</span>
                    @else
                        <span class="status-badge" style="background: #fff3e0; color: #e65100; border: none;">Đã ẩn</span>
                    @endif
                </td>
                <td style="text-align: right; white-space: nowrap;">
                    <div style="display: flex; gap: 12px; justify-content: flex-end; align-items: center;">
                        <a href="{{ route('admin.products.variants.index', $prod->id) }}" style="color: #2e7d32; font-size: 12px; font-weight: 600; text-decoration: none;">BIẾN THỂ</a>
                        <a href="{{ route('admin.products.edit', $prod->id) }}" style="color: #666; font-size: 12px; font-weight: 600; text-decoration: none;">SỬa</a>
                        <form action="{{ route('admin.products.destroy', $prod->id) }}" method="POST" style="display: inline; margin: 0;" onsubmit="return confirm('Xóa sản phẩm này?');">
                            @csrf @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #d93025; font-size: 12px; font-weight: 600; cursor: pointer; padding: 0;">XÓA</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div style="margin-top: 20px;">
    {{ $products->links('vendor.pagination.admin') }}
</div>

@endsection
