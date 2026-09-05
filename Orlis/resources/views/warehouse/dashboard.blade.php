@extends('layouts.admin')
@section('title', 'Warehouse Portal')
@section('content')
<div class="container-fluid p-4">
    <h2 class="mb-4">Quản Lý Xuất Kho (Warehouse)</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Mã Đơn</th>
                        <th>Sản Phẩm Cần Xuất</th>
                        <th>Trạng Thái</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->order_code }}</td>
                            <td>
                                <ul style="margin:0; padding-left:20px;">
                                    @foreach($order->items as $item)
                                        <li>{{ $item->variant->product->name ?? 'Sản phẩm' }} ({{ $item->variant->display_name ?? '' }}) - <strong>SL: {{ $item->quantity }}</strong></li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                @if($order->order_status == 'shipped')
                                    <span class="badge bg-warning">Chờ xuất kho</span>
                                @elseif($order->order_status == 'delivering')
                                    <span class="badge bg-info">Đã xuất kho/Đang giao</span>
                                @endif
                            </td>
                            <td>
                                @if($order->order_status == 'shipped')
                                <form action="{{ route('warehouse.orders.delivering', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-dark">
                                        Xuất kho & Giao Shipper
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">Không có đơn hàng nào chờ xuất kho.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
