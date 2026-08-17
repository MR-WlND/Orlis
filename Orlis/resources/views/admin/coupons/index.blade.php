@extends('layouts.admin')

@section('title', 'Quản lý Mã giảm giá')

@section('content')
<div style="background: #fff; border: 1px solid var(--border-color); padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0; font-size: 20px; font-weight: 600;">Danh sách Mã giảm giá</h2>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary" style="background:#000; color:#fff; border:none; padding:8px 16px; border-radius:4px; text-decoration:none;">+ Thêm Mã mới</a>
    </div>

    @if(session('success'))
        <div style="background: #e6f7ff; color: #1890ff; padding: 10px 15px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #91d5ff;">
            {{ session('success') }}
        </div>
    @endif

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f8f9fa; border-bottom: 2px solid #eee;">
                    <th style="padding: 12px;">ID</th>
                    <th style="padding: 12px;">Mã Code</th>
                    <th style="padding: 12px;">Giảm giá</th>
                    <th style="padding: 12px;">Đã dùng</th>
                    <th style="padding: 12px;">Giới hạn</th>
                    <th style="padding: 12px;">Hết hạn</th>
                    <th style="padding: 12px;">Trạng thái</th>
                    <th style="padding: 12px; text-align: right;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px;">{{ $coupon->id }}</td>
                        <td style="padding: 12px; font-weight: 600;">{{ $coupon->code }}</td>
                        <td style="padding: 12px;">
                            @if($coupon->discount_percent)
                                {{ $coupon->discount_percent }}%
                            @elseif($coupon->discount_amount)
                                {{ number_format($coupon->discount_amount) }}đ
                            @endif
                        </td>
                        <td style="padding: 12px;">{{ $coupon->used_count }}</td>
                        <td style="padding: 12px;">{{ $coupon->max_uses ?? 'Vô hạn' }}</td>
                        <td style="padding: 12px;">
                            {{ $coupon->expires_at ? $coupon->expires_at->format('d/m/Y H:i') : 'Vĩnh viễn' }}
                        </td>
                        <td style="padding: 12px;">
                            @if($coupon->isValid())
                                <span style="background: #e6f7ff; color: #1890ff; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Khả dụng</span>
                            @else
                                <span style="background: #fff1f0; color: #f5222d; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Hết hạn/Hết lượt</span>
                            @endif
                        </td>
                        <td style="padding: 12px; text-align: right;">
                            <a href="{{ route('admin.coupons.edit', $coupon->id) }}" style="color: #1890ff; text-decoration: none; margin-right: 10px;">Sửa</a>
                            <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa mã giảm giá này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color: #f5222d; border: none; background: transparent; cursor: pointer; padding: 0;">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="padding: 20px; text-align: center; color: #888;">Chưa có mã giảm giá nào</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $coupons->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
