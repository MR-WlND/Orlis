@extends('layouts.admin')

@section('title', 'Biến thể: ' . $product->name)

@section('content')
<div style="background: #fff; border: 1px solid var(--border-color); padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0; font-size: 18px; color: #333;">Biến thể: {{ $product->name }}</h2>
        <div>
            <a href="{{ route('admin.products.index') }}" style="color: #666; text-decoration: none; margin-right: 15px;">&larr; Về danh sách SP</a>
            <a href="{{ route('admin.products.variants.create', $product->id) }}" style="background: #000; color: #fff; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-size: 14px;">+ Thêm Biến thể</a>
        </div>
    </div>

    @if(session('success'))
        <div style="padding: 10px; background: #e6f4ea; color: #1e8e3e; margin-bottom: 15px; border-radius: 4px;">{{ session('success') }}</div>
    @endif

    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
        <thead>
            <tr style="border-bottom: 2px solid #eee; text-align: left;">
                <th style="padding: 12px 8px;">SKU</th>
                <th style="padding: 12px 8px;">Màu sắc</th>
                <th style="padding: 12px 8px;">Size</th>
                <th style="padding: 12px 8px;">Tồn kho</th>
                <th style="padding: 12px 8px;">Giá tùy chỉnh</th>
                <th style="padding: 12px 8px;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($variants as $variant)
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 12px 8px;">{{ $variant->sku }}</td>
                <td style="padding: 12px 8px;">{{ $variant->color ?? '-' }}</td>
                <td style="padding: 12px 8px;">{{ $variant->size ?? '-' }}</td>
                <td style="padding: 12px 8px;">{{ $variant->stock_qty }}</td>
                <td style="padding: 12px 8px;">{{ $variant->price_override ? number_format($variant->price_override) . 'đ' : '-' }}</td>
                <td style="padding: 12px 8px;">
                    <a href="{{ route('admin.products.variants.edit', [$product->id, $variant->id]) }}" style="color: #1a73e8; margin-right: 10px; text-decoration: none;">Sửa</a>
                    <form action="{{ route('admin.products.variants.destroy', [$product->id, $variant->id]) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa biến thể này?');">
                        @csrf @method('DELETE')
                        <button type="submit" style="background: none; border: none; color: #ea4335; cursor: pointer; font-size: 14px;">Xóa</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px;">Sản phẩm này chưa có biến thể nào.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="margin-top: 20px;">
        {{ $variants->links() }}
    </div>
</div>
@endsection
