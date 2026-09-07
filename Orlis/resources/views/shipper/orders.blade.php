@extends('layouts.admin')
@section('title', 'Đơn hàng & Lộ trình')

@section('page-style')
<style>
/* Reset & Fonts */
body {
    background-color: #fafafa;
    color: #333;
}
.font-serif {
    font-family: "Playfair Display", Georgia, serif;
}

/* Header */
.page-header {
    margin-bottom: 30px;
    padding: 0 10px;
}
.page-title {
    font-size: 28px;
    font-family: "Playfair Display", serif;
    margin-bottom: 8px;
    color: #111;
}
.page-subtitle {
    font-size: 13px;
    color: #666;
    letter-spacing: 0.5px;
}
.header-right {
    display: flex;
    justify-content: flex-end;
    margin-top: -30px;
}
.badge-today {
    background: #e8f5e9;
    color: #1f7a54;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px solid #c8e6c9;
}
.badge-today::before {
    content: '';
    display: block;
    width: 6px;
    height: 6px;
    background: #1f7a54;
    border-radius: 50%;
}

/* Tabs */
.filter-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 30px;
    padding: 0 10px;
}
.filter-tab {
    padding: 8px 24px;
    border: 1px solid #e0e0e0;
    background: #fff;
    color: #666;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}
.filter-tab:hover {
    border-color: #111;
    color: #111;
}
.filter-tab.active {
    background: #111;
    color: #fff;
    border-color: #111;
}

/* Orders Grid */
.orders-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
    padding: 0 10px;
}
.order-card {
    background: #fff;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    border: 1px solid #eaeaea;
    display: flex;
    flex-direction: column;
}
.order-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 5px;
}
.order-code {
    font-family: "Playfair Display", serif;
    font-size: 20px;
    color: #111;
    letter-spacing: 0.5px;
}
.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.status-badge::before {
    content: '';
    display: block;
    width: 6px;
    height: 6px;
    border-radius: 50%;
}
.status-badge.shipping {
    background: #f0f7ff;
    color: #0050b3;
    border: 1px solid #d6e8ff;
}
.status-badge.shipping::before { background: #1890ff; }

.status-badge.delivered {
    background: #e8f5e9;
    color: #1f7a54;
    border: 1px solid #c8e6c9;
}
.status-badge.delivered::before { background: #1f7a54; }

.status-badge.cancelled {
    background: #fce4ec;
    color: #d32f2f;
    border: 1px solid #f8bbd0;
}
.status-badge.cancelled::before { background: #d32f2f; }

.order-meta {
    font-size: 11px;
    color: #888;
    margin-bottom: 25px;
}

.customer-info {
    display: grid;
    grid-template-columns: 70px 1fr;
    gap: 12px;
    font-size: 13px;
    margin-bottom: 25px;
}
.info-label {
    color: #888;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 0.5px;
    padding-top: 2px;
}
.info-value {
    color: #111;
    font-weight: 500;
}
.btn-action-small {
    background: #f5f5f5;
    border: 1px solid #e0e0e0;
    color: #666;
    padding: 2px 10px;
    border-radius: 4px;
    font-size: 11px;
    margin-left: 8px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    text-decoration: none;
}
.btn-action-small:hover {
    background: #e0e0e0;
}

.payment-box {
    background: #fdfdfd;
    border: 1px solid #e8f5e9;
    border-radius: 8px;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    border-left: 3px solid #1f7a54;
}
.payment-box.cod {
    border: 1px solid #fff8e1;
    border-left: 3px solid #cda873;
}
.payment-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: #666;
}
.payment-status-text {
    text-align: right;
}
.payment-status-main {
    font-weight: 600;
    font-size: 13px;
    color: #111;
}
.payment-cod-amount {
    font-size: 11px;
    color: #888;
    margin-top: 2px;
}

.card-actions {
    display: flex;
    gap: 10px;
    margin-top: auto;
}
.btn-success {
    background: #1f7a54;
    color: #fff;
    border: none;
    padding: 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    flex: 1;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: opacity 0.2s;
}
.btn-success:hover { opacity: 0.9; }

.btn-fail {
    background: #fce4ec;
    color: #d32f2f;
    border: none;
    padding: 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    flex: 0 0 100px;
    cursor: pointer;
    transition: background 0.2s;
}
.btn-fail:hover { background: #f8bbd0; }

.btn-disabled {
    background: #f9f9f9;
    color: #999;
    border: 1px solid #eaeaea;
    padding: 12px;
    border-radius: 6px;
    font-size: 13px;
    width: 100%;
    text-align: center;
    pointer-events: none;
}

.no-orders {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    background: #fff;
    border-radius: 12px;
    border: 1px dashed #ccc;
    color: #888;
}
</style>
@endsection

@section('content')
<div class="container-fluid" style="padding: 20px 40px;">
    
    <div class="page-header">
        <h1 class="page-title">Trạm Giao Hàng &bull; <span style="color: #888; font-size: 24px;">Tất cả đơn hàng</span></h1>
        <div class="page-subtitle">Quản lý phiên vận chuyển hỏa tốc & xác nhận giao hàng Privé</div>
        <div class="header-right">
            <div class="badge-today">Hôm nay: {{ $orders->count() }} đơn phụ trách</div>
        </div>
    </div>

    @php
        $countAll = $orders->count();
        $countShipping = $orders->where('order_status', 'shipping')->count();
        $countDelivered = $orders->where('order_status', 'delivered')->count();
        $countFailed = $orders->where('order_status', 'cancelled')->count();
    @endphp

    <div class="filter-tabs" id="orderTabs">
        <button class="filter-tab active" data-filter="all">Tất cả ({{ $countAll }})</button>
        <button class="filter-tab" data-filter="shipping">Đang giao ({{ $countShipping }})</button>
        <button class="filter-tab" data-filter="delivered">Đã giao ({{ $countDelivered }})</button>
        <button class="filter-tab" data-filter="cancelled">Thất bại ({{ $countFailed }})</button>
    </div>

    @if(session('success'))
        <div style="background: #e8f5e9; color: #1f7a54; padding: 12px 20px; border-radius: 6px; margin: 0 10px 20px 10px; border: 1px solid #c8e6c9;">
            {{ session('success') }}
        </div>
    @endif

    <div class="orders-grid" id="ordersGrid">
        @forelse($orders as $order)
            <div class="order-card" data-status="{{ $order->order_status }}">
                <div class="order-header">
                    <div class="order-code">{{ $order->order_code }}</div>
                    <div class="status-badge {{ $order->order_status }}">
                        @if($order->order_status == 'shipping') Đang giao
                        @elseif($order->order_status == 'delivered') Đã giao
                        @else Thất bại @endif
                    </div>
                </div>
                <div class="order-meta">
                    @if($order->order_status == 'delivered')
                        Hoàn tất lúc: {{ $order->updated_at->format('H:i') }} Hôm nay &bull; Giao tiêu chuẩn Privé
                    @else
                        Dự kiến giao: Trước 18:00 Hôm nay &bull; Couture Express
                    @endif
                </div>

                <div class="customer-info">
                    <div class="info-label">KHÁCH:</div>
                    <div class="info-value">{{ $order->recipient_name }}</div>

                    <div class="info-label">SĐT:</div>
                    <div class="info-value">
                        {{ $order->recipient_phone }}
                        @if($order->order_status == 'shipping')
                            <a href="tel:{{ $order->recipient_phone }}" class="btn-action-small">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                Gọi khách
                            </a>
                        @else
                            <span class="btn-action-small" onclick="navigator.clipboard.writeText('{{ $order->recipient_phone }}'); alert('Đã sao chép!');">Sao chép</span>
                        @endif
                    </div>

                    <div class="info-label">ĐỊA CHỈ:</div>
                    <div class="info-value" style="line-height: 1.4;">{{ $order->shipping_address_snapshot['address_line1'] ?? 'Không có' }}, {{ $order->shipping_address_snapshot['ward'] ?? '' }}, {{ $order->shipping_address_snapshot['district'] ?? '' }}, {{ $order->shipping_address_snapshot['city'] ?? '' }}</div>
                </div>

                @if($order->remaining_amount > 0)
                    <div class="payment-box cod">
                        <div class="payment-label">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            Trạng thái thanh toán
                        </div>
                        <div class="payment-status-text">
                            <div class="payment-status-main" style="color: #d48806;">Chưa thanh toán</div>
                            <div class="payment-cod-amount">Cần thu: <strong>{{ number_format($order->remaining_amount, 0, ',', '.') }}đ</strong></div>
                        </div>
                    </div>
                @else
                    <div class="payment-box">
                        <div class="payment-label">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            Trạng thái thanh toán
                        </div>
                        <div class="payment-status-text">
                            <div class="payment-status-main">Đã thanh toán</div>
                            <div class="payment-cod-amount">COD: 0đ</div>
                        </div>
                    </div>
                @endif

                @if($order->order_status == 'shipping')
                    <div class="card-actions">
                        <form action="{{ route('shipper.orders.updateStatus', $order->id) }}" method="POST" style="flex: 1;">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="delivered">
                            <button type="submit" class="btn-success" onclick="return confirm('Xác nhận ĐÃ GIAO THÀNH CÔNG đơn hàng này?');">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                Giao thành công
                            </button>
                        </form>

                        <form action="{{ route('shipper.orders.updateStatus', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="btn-fail" onclick="return confirm('Xác nhận GIAO THẤT BẠI đơn hàng này?');">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                Thất bại
                            </button>
                        </form>
                    </div>
                @else
                    <div class="btn-disabled">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: text-bottom; margin-right: 4px;"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Đơn hàng đã xử lý xong
                    </div>
                @endif
            </div>
        @empty
            <div class="no-orders">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="color: #ddd; margin-bottom: 15px;"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                <div style="font-family: 'Playfair Display', serif; font-size: 18px; color: #111;">Chưa có đơn hàng nào</div>
                <p>Bạn đã hoàn thành xuất sắc toàn bộ công việc hôm nay.</p>
            </div>
        @endforelse
    </div>

    {{ $orders->links() }}
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.filter-tab');
    const cards = document.querySelectorAll('.order-card');
    const noOrdersMsg = document.createElement('div');
    noOrdersMsg.className = 'no-orders';
    noOrdersMsg.innerHTML = '<div style="font-family: \'Playfair Display\', serif; font-size: 18px; color: #111;">Không có đơn hàng nào</div><p>Không tìm thấy đơn hàng phù hợp với trạng thái này.</p>';
    noOrdersMsg.style.display = 'none';
    document.getElementById('ordersGrid').appendChild(noOrdersMsg);

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Remove active from all tabs
            tabs.forEach(t => t.classList.remove('active'));
            // Add active to clicked
            tab.classList.add('active');

            const filter = tab.getAttribute('data-filter');
            let visibleCount = 0;

            cards.forEach(card => {
                if (filter === 'all' || card.getAttribute('data-status') === filter) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (visibleCount === 0 && cards.length > 0) {
                noOrdersMsg.style.display = 'block';
            } else {
                noOrdersMsg.style.display = 'none';
            }
        });
    });
});
</script>
@endsection
