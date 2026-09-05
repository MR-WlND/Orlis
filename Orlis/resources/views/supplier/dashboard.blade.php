@extends('layouts.admin')
@section('title', 'Supplier Portal')

@section('page-style')
<style>
    .dashboard-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px; }
    .card { background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 20px 24px; }
    .card-title { font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; }
    .card-value { font-family: var(--font-serif); font-size: 28px; font-weight: 600; color: var(--accent); }
    .table th, .table td { vertical-align: middle; }
</style>
@endsection

@section('content')
<div class="container-fluid p-4">
    <h2 class="mb-4">Portal Quản Lý Đơn Nhập Hàng (Supplier)</h2>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="dashboard-grid">
        <div class="card">
            <div class="card-title">Tổng Yêu Cầu</div>
            <div class="card-value">{{ number_format($totalOrders) }}</div>
        </div>
        <div class="card">
            <div class="card-title">Chờ Xác Nhận</div>
            <div class="card-value">{{ number_format($pendingOrders) }}</div>
        </div>
        <div class="card">
            <div class="card-title">Hoàn Thành</div>
            <div class="card-value">{{ number_format($completedOrders) }}</div>
        </div>
        <div class="card">
            <div class="card-title">Tổng Doanh Thu</div>
            <div class="card-value">{{ number_format($totalRevenue, 0, ',', '.') }}₫</div>
        </div>
    </div>
    
    <div class="card" style="margin-top: 24px;">
        <div class="card-body">
            <h4 style="margin-bottom: 16px;">Danh Sách Phiếu Nhập Kho (Purchase Orders)</h4>
            <table class="table table-bordered">
                <thead style="background: #f9f9f9;">
                    <tr>
                        <th>Mã PO</th>
                        <th>Ngày Đặt</th>
                        <th>Chi Tiết Sản Phẩm Nhập</th>
                        <th>Tổng Tiền</th>
                        <th>Trạng Thái</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchaseOrders as $po)
                        <tr>
                            <td><strong>{{ $po->po_code }}</strong></td>
                            <td>{{ $po->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <ul style="margin:0; padding-left:20px;">
                                    @foreach($po->items as $item)
                                        <li>
                                            {{ $item->productVariant->product->name ?? 'SP' }} ({{ $item->productVariant->display_name ?? '' }}) 
                                            - SL: <strong>{{ $item->quantity }}</strong>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ number_format($po->total_amount, 0, ',', '.') }}₫<br><small class="text-muted">({{ $po->payment_status == 'paid' ? 'Đã Thanh Toán' : 'Chưa Thanh Toán' }})</small></td>
                            <td>
                                @if($po->status == 'pending')
                                    <span class="badge bg-warning">Chờ xác nhận</span>
                                @elseif($po->status == 'confirmed')
                                    <span class="badge bg-info">Đã xác nhận (Chờ gửi hàng)</span>
                                @elseif($po->status == 'shipped')
                                    <span class="badge bg-primary">Đang giao đến kho</span>
                                @elseif($po->status == 'completed')
                                    <span class="badge bg-success">Đã hoàn thành</span>
                                @elseif($po->status == 'cancelled')
                                    <span class="badge bg-danger">Đã hủy</span>
                                @endif
                            </td>
                            <td>
                                @if($po->status == 'pending')
                                <form action="{{ route('supplier.orders.updateStatus', $po->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="confirmed">
                                    <button class="btn btn-sm btn-dark">Xác nhận PO</button>
                                </form>
                                <form action="{{ route('supplier.orders.updateStatus', $po->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Từ chối nhận PO này?')">Từ chối</button>
                                </form>
                                @elseif($po->status == 'confirmed')
                                <form action="{{ route('supplier.orders.updateStatus', $po->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="shipped">
                                    <button class="btn btn-sm btn-primary">Báo đã gửi hàng (Shipped)</button>
                                </form>
                                @elseif($po->status == 'shipped')
                                    <small class="text-muted">Chờ Admin kho xác nhận nhận hàng</small>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center" style="padding: 30px;">Không có yêu cầu nhập hàng nào.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $purchaseOrders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
