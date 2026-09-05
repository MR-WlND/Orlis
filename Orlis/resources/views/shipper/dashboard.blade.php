@extends('layouts.admin')
@section('title', 'Shipper Portal')
@section('content')
<div class="container-fluid p-4">
    <h2 class="mb-4">Giao Đơn Hàng (Shipper)</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Mã Đơn</th>
                        <th>Địa Chỉ Giao</th>
                        <th>Thu Hộ (COD)</th>
                        <th>Trạng Thái</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->order_code }}</td>
                            <td>
                                {{ $order->shipping_address['recipient_name'] }}<br>
                                {{ $order->shipping_address['recipient_phone'] }}<br>
                                {{ $order->shipping_address['detail_address'] }}, {{ $order->shipping_address['ward'] }}, {{ $order->shipping_address['district'] }}, {{ $order->shipping_address['province'] }}
                            </td>
                            <td>{{ $order->payment_method == 'cod' && $order->payment_status != 'paid' ? number_format($order->grand_total, 0, ',', '.') . '₫' : 'Đã thanh toán (0₫)' }}</td>
                            <td>
                                @if($order->order_status == 'delivering')
                                    <span class="badge bg-primary">Đang giao hàng</span>
                                @elseif($order->order_status == 'completed')
                                    <span class="badge bg-success">Đã giao thành công</span>
                                @endif
                            </td>
                            <td>
                                @if($order->order_status == 'delivering')
                                <form action="{{ route('shipper.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <button class="btn btn-sm btn-success">Giao thành công</button>
                                </form>
                                <form action="{{ route('shipper.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Xác nhận giao thất bại/hoàn hàng?')">Thất bại</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">Không có đơn hàng nào cần giao.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
