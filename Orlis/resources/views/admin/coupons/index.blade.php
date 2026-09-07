@extends('layouts.admin')

@section('title', 'Quản lý Mã giảm giá')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Quản lý Mã giảm giá</h2>
        <p class="page-subtitle">Quản lý danh sách các mã giảm giá và khuyến mãi của hệ thống.</p>
    </div>
    <a href="{{ route('admin.coupons.create') }}" class="btn-add-new">
        <span style="margin-right: 8px;">+</span> THÊM MÃ MỚI
    </a>
</div>

<div class="table-container">

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>MÃ CODE</th>
                <th>GIẢM GIÁ</th>
                <th>ĐÃ DÙNG</th>
                <th>GIỚI HẠN</th>
                <th>HẾT HẠN</th>
                <th>TRẠNG THÁI</th>
                <th style="text-align: right;">HÀNH ĐỘNG</th>
            </tr>
        </thead>
        <tbody>
                @forelse($coupons as $coupon)
                    <tr>
                        <td>{{ $coupon->id }}</td>
                        <td style="font-weight: 600; color: var(--text-primary);">{{ $coupon->code }}</td>
                        <td>
                            @if($coupon->discount_percent)
                                {{ $coupon->discount_percent }}%
                            @elseif($coupon->discount_amount)
                                {{ number_format($coupon->discount_amount) }}đ
                            @endif
                        </td>
                        <td>{{ $coupon->used_count }}</td>
                        <td>{{ $coupon->max_uses ?? 'Vô hạn' }}</td>
                        <td>
                            {{ $coupon->expires_at ? $coupon->expires_at->format('d/m/Y H:i') : 'Vĩnh viễn' }}
                        </td>
                        <td>
                            @if($coupon->isValid())
                                <span class="status-active">Khả dụng</span>
                            @else
                                <span class="status-pending" style="color: #d32f2f;">Hết hạn</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div class="action-links" style="display: flex; gap: 15px; justify-content: flex-end;">
                                <a href="{{ route('admin.coupons.edit', $coupon->id) }}">SỬA</a>
                                <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background:none; border:none; color: #f5222d; cursor:pointer; font:inherit; padding:0;" onclick="return confirm('Bạn có chắc chắn muốn xóa mã giảm giá này?');">XÓA</button>
                                </form>
                            </div>
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

@if($coupons->hasPages())
    {{ $coupons->links('vendor.pagination.admin') }}
@endif
@endsection
