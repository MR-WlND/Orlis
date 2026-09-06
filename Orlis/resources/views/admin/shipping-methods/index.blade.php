@extends('layouts.admin')

@section('title', 'Quản lý Phương thức Giao hàng')

@section('content')
<div class="page-header">
    <div class="header-text">
        <h2 class="page-title">Phương thức Giao hàng</h2>
        <p class="page-subtitle">Quản lý các gói giao hàng hiển thị cho khách hàng tại trang Thanh toán.</p>
    </div>
    <a href="{{ route('admin.shipping-methods.create') }}" class="btn-add-new">
        <span>+</span> THÊM GÓI MỚI
    </a>
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
                <th>TÊN GÓI GIAO HÀNG</th>
                <th>MÔ TẢ</th>
                <th>PHÍ MẶC ĐỊNH</th>
                <th>MIỄN PHÍ TỪ</th>
                <th>TRẠNG THÁI</th>
                <th>HÀNH ĐỘNG</th>
            </tr>
        </thead>
        <tbody>
            @forelse($methods as $method)
            <tr>
                <td>
                    <span style="font-weight: 600; font-size: 13px;">{{ $method->name }}</span>
                </td>
                <td>
                    <span style="font-size: 12px; color: #666;">{{ $method->description ?? '—' }}</span>
                </td>
                <td>
                    @if($method->cost == 0)
                        <span style="color: #b8860b; font-weight: 700; font-size: 13px;">MIỄN PHÍ</span>
                    @else
                        <span style="font-weight: 600; font-size: 13px;">{{ number_format($method->cost, 0, ',', '.') }}₫</span>
                    @endif
                </td>
                <td>
                    @if($method->min_order_amount_for_free_shipping)
                        <span style="font-size: 12px; color: #555;">{{ number_format($method->min_order_amount_for_free_shipping, 0, ',', '.') }}₫</span>
                    @else
                        <span style="color: #ccc;">—</span>
                    @endif
                </td>
                <td>
                    @if($method->is_active)
                        <span class="status-badge">Kích hoạt</span>
                    @else
                        <span class="status-badge inactive">Đã tắt</span>
                    @endif
                </td>
                <td>
                    <div class="action-links">
                        <a href="{{ route('admin.shipping-methods.edit', $method->id) }}" class="action-btn" title="Chỉnh sửa">
                            <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </a>
                        <form action="{{ route('admin.shipping-methods.destroy', $method->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Xóa phương thức giao hàng này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete" title="Xóa">
                                <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px; color: #999;">Chưa có phương thức giao hàng nào. Hãy thêm mới.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
