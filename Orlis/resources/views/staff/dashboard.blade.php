@extends('layouts.admin')
@section('title', 'Staff Portal')
@section('content')
<div class="container-fluid p-4">
    <h2 class="mb-4">Xử Lý Đơn Hàng (Staff)</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Mã Đơn</th>
                        <th>Khách Hàng</th>
                        <th>Tổng Tiền</th>
                        <th>Trạng Thái</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->order_code }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ number_format($order->grand_total, 0, ',', '.') }}₫</td>
                            <td>
                                @if($order->order_status == 'pending')
                                    <span class="badge bg-warning">Chờ xử lý</span>
                                @elseif($order->order_status == 'processing')
                                    <span class="badge bg-primary">Đang xử lý</span>
                                @elseif($order->order_status == 'shipping')
                                    <span class="badge bg-info">Đang giao hàng</span>
                                @endif
                            </td>
                            <td style="display: flex; gap: 8px;">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-dark">Chi tiết</a>
                                @if(in_array($order->order_status, ['pending', 'processing']))
                                <form action="{{ route('staff.orders.process', $order->id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-dark">
                                        {{ $order->order_status == 'pending' ? 'Xác nhận' : 'Giao kho' }}
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">Không có đơn hàng nào cần xử lý.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
