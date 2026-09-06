@extends('layouts.admin')

@section('title', 'Đơn hàng ' . $order->order_code)

@section('page-style')

@endsection

@section('content')

<a href="{{ route('admin.orders.index') }}" class="back-link">
    ← Quay lại danh sách
</a>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

<div class="order-header">
    <div>
        <div class="order-code">{{ $order->order_code }}</div>
        <div class="order-date">Đặt lúc {{ $order->created_at->format('H:i, d/m/Y') }}</div>
    </div>
    <span class="status-badge" style="color: {{ $order->status_color }}; background: {{ $order->status_color }}22; font-size: 14px; padding: 7px 16px;">
        <span class="status-dot"></span>
        {{ $order->status_label }}
    </span>
</div>

<div class="order-grid">
    {{-- LEFT --}}
    <div>
        {{-- Sản phẩm --}}
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-title">Sản phẩm đặt mua</div>
            @foreach($order->items as $item)
            <div class="order-item">
                @php $product = $item->variant?->product; @endphp
                @if($product?->thumbnail)
                    <img class="order-item-img" src="{{ Storage::url($product->thumbnail) }}" alt="{{ $item->product_name }}">
                @else
                    <div class="order-item-img" style="display:flex;align-items:center;justify-content:center;color:#ccc;font-size:20px;">📦</div>
                @endif
                <div class="order-item-info">
                    <div class="order-item-name">{{ $item->product_name }}</div>
                    @if($item->variant_info)
                        <div class="order-item-variant">{{ $item->variant_info }}</div>
                    @endif
                    <div class="order-item-price">{{ number_format($item->price, 0, ',', '.') }}₫ × {{ $item->quantity }}</div>
                </div>
                <div class="order-item-total">{{ number_format($item->subtotal, 0, ',', '.') }}₫</div>
            </div>
            @endforeach

            {{-- Summary --}}
            <div style="margin-top: 16px;">
                <div class="summary-row">
                    <span>Tạm tính</span>
                    <span>{{ number_format($order->subtotal, 0, ',', '.') }}₫</span>
                </div>
                @if($order->discount_amount > 0)
                <div class="summary-row" style="color: #52c41a;">
                    <span>Giảm giá ({{ $order->coupon?->code }})</span>
                    <span>-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</span>
                </div>
                @endif
                <div class="summary-row total">
                    <span>Tổng thanh toán</span>
                    <span style="color: var(--accent);">{{ number_format($order->grand_total, 0, ',', '.') }}₫</span>
                </div>
            </div>
        </div>

        {{-- Lịch sử trạng thái --}}
        @if($order->statusLogs->isNotEmpty())
        <div class="card">
            <div class="card-title">Lịch sử cập nhật trạng thái</div>
            <ul class="timeline">
                @foreach($order->statusLogs->sortByDesc('created_at') as $log)
                <li class="timeline-item">
                    <div class="timeline-dot">
                        <svg width="10" height="10" viewBox="0 0 10 10" fill="var(--accent)"><circle cx="5" cy="5" r="4"/></svg>
                    </div>
                    <div class="timeline-content">
                        <div>
                            <strong>{{ \App\Models\Order::STATUSES[$log->old_status] ?? $log->old_status }}</strong>
                            → <strong style="color: var(--accent);">{{ \App\Models\Order::STATUSES[$log->new_status] ?? $log->new_status }}</strong>
                        </div>
                        @if($log->note)
                            <div style="color: var(--text-muted); margin-top: 2px;">{{ $log->note }}</div>
                        @endif
                        @if($log->changedByAdmin)
                            <div class="timeline-time">bởi {{ $log->changedByAdmin->name }} &bull; {{ $log->created_at->format('H:i d/m/Y') }}</div>
                        @else
                            <div class="timeline-time">{{ $log->created_at->format('H:i d/m/Y') }}</div>
                        @endif
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>

    {{-- RIGHT --}}
    <div>
        {{-- Cập nhật trạng thái --}}
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-title">Cập nhật trạng thái</div>
            <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}" class="status-form">
                @csrf
                @method('PATCH')
                <select name="order_status" required>
                    @foreach(\App\Models\Order::STATUSES as $key => $label)
                        <option value="{{ $key }}" @selected($order->order_status === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <textarea name="note" rows="2" placeholder="Ghi chú (tùy chọn)..."></textarea>
                <button type="submit" class="btn btn-primary">Cập nhật trạng thái</button>
            </form>
        </div>

        {{-- Thông tin khách hàng --}}
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-title">Thông tin giao hàng</div>
            <div class="info-row">
                <span class="info-label">Người nhận</span>
                <span style="font-weight: 500;">{{ $order->recipient_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Số điện thoại</span>
                <span>{{ $order->recipient_phone }}</span>
            </div>
            @if($order->shipping_address_snapshot)
            <div class="info-row">
                <span class="info-label">Địa chỉ</span>
                <span>
                    {{ $order->shipping_address_snapshot['detail_address'] ?? '' }},
                    {{ $order->shipping_address_snapshot['ward'] ?? '' }},
                    {{ $order->shipping_address_snapshot['district'] ?? '' }},
                    {{ $order->shipping_address_snapshot['province'] ?? '' }}
                </span>
            </div>
            @endif
            @if($order->gift_note)
            <div class="info-row">
                <span class="info-label">Ghi chú quà tặng</span>
                <span style="font-style: italic;">{{ $order->gift_note }}</span>
            </div>
            @endif
        </div>

        {{-- Thông tin tài khoản --}}
        @if($order->user)
        <div class="card">
            <div class="card-title">Tài khoản khách hàng</div>
            <div class="info-row">
                <span class="info-label">Tên</span>
                <a href="{{ route('admin.users.edit', $order->user) }}" style="color: var(--accent); text-decoration: none; font-weight: 500;">{{ $order->user->name }}</a>
            </div>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span>{{ $order->user->email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Hạng thành viên</span>
                <span>{{ $order->user->membership_level ? \App\Models\User::MEMBERSHIPS[$order->user->membership_level] : 'Classic' }}</span>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection
