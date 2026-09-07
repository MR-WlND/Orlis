@extends('layouts.client')
@section('title', '{{ __('messages.track_order_title_caps') }} – Maison Orlis')

@section('content')

<div class="tko-page">

    <!-- ===== Hero ===== -->
    <div class="tko-hero">
        <p class="tko-hero-sup">THEO DÕI HÀNH TRÌNH KIỆN HÀNG</p>
        <h1 class="tko-hero-title">Tra cứu đơn hàng</h1>
        <p class="tko-hero-desc">
            Nhập mã đơn hàng và số điện thoại đặt hàng để kiểm tra lộ trình vận chuyển và tình trạng thực<br>tế của kiện hàng Maison Orlis.
        </p>
    </div>

    <!-- ===== Search Box ===== -->
    <div class="tko-container">
        <div class="tko-search-card">
            <form action="{{ route('track-order.post') }}" method="POST">
                @csrf
                <div class="tko-search-row">
                    <div class="tko-field">
                        <label class="tko-label">MÃ ĐƠN HÀNG <span>*</span></label>
                        <input type="text" name="order_code"
                            class="tko-input {{ $errors->has('order_code') ? 'tko-input-error' : '' }}"
                            value="{{ old('order_code', request('order_code')) }}"
                            placeholder="ORD-8921356-VN"
                            required>
                        @error('order_code')<p class="tko-err">{{ $message }}</p>@enderror
                    </div>
                    <div class="tko-field">
                        <label class="tko-label">SỐ ĐIỆN THOẠI NHẬN HÀNG <span>*</span></label>
                        <input type="text" name="phone"
                            class="tko-input {{ $errors->has('phone') ? 'tko-input-error' : '' }}"
                            value="{{ old('phone', request('phone')) }}"
                            placeholder="0918 *** 888"
                            required>
                        @error('phone')<p class="tko-err">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="tko-search-btn">
                        TRA CỨU
                        <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </button>
                </div>
            </form>

            @if(session('error'))
            <div class="tko-alert-error">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ session('error') }}
            </div>
            @endif

            @guest
            <div class="tko-search-hint">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                Quý khách cũng có thể đăng nhập tài khoản Orlis Privé để tự động theo dõi lịch sử toàn bộ đơn hàng.
                <a href="#" onclick="toggleLoginModal(event)" class="tko-hint-link">ĐĂNG NHẬP TÀI KHOẢN</a>
            </div>
            @endguest
        </div>

        @if(isset($order))
        {{-- ===== RESULT CARD ===== --}}
        <div class="tko-result-card">

            {{-- Header --}}
            <div class="tko-result-header">
                <div class="tko-result-left">
                    <div class="tko-result-code-row">
                        <span class="tko-result-code-label">MÃ KIỆN HÀNG:</span>
                        <span class="tko-result-code">{{ $order->order_code }}</span>
                        @php
                            $statusConfig = [
                                'pending'    => ['label' => 'Chờ Xác Nhận',        'class' => 'tko-badge-pending'],
                                'confirmed'  => ['label' => 'Đã Xác Nhận',         'class' => 'tko-badge-confirmed'],
                                'processing' => ['label' => 'Đang Chuẩn Bị',       'class' => 'tko-badge-processing'],
                                'shipping'   => ['label' => 'Đang Vận Chuyển Hỏa Tốc', 'class' => 'tko-badge-shipping'],
                                'delivered'  => ['label' => 'Đã Giao Hàng',        'class' => 'tko-badge-delivered'],
                                'cancelled'  => ['label' => 'Đã Hủy',              'class' => 'tko-badge-cancelled'],
                                'refunded'   => ['label' => 'Đã Hoàn Tiền',        'class' => 'tko-badge-refunded'],
                            ];
                            $sc = $statusConfig[$order->order_status] ?? ['label' => $order->status_label, 'class' => 'tko-badge-default'];
                        @endphp
                        <span class="tko-status-badge {{ $sc['class'] }}">{{ $sc['label'] }}</span>
                    </div>
                    <p class="tko-result-meta">
                        Đặt ngày {{ $order->created_at->format('d Tháng m, Y') }}
                        @if($order->shippingMethod)
                        • {{ $order->shippingMethod->name }}
                        @endif
                        @if($order->items->first()?->variant?->product?->stores?->first())
                        • Giao từ {{ $order->items->first()->variant->product->stores->first()->name ?? '' }}
                        @endif
                    </p>
                </div>
                <div class="tko-result-right">
                    <div class="tko-eta-label">DỰ KIẾN GIAO HÀNG</div>
                    @php
                        $eta = $order->ready_for_delivery_at
                            ? 'Trước 17:00, ' . $order->ready_for_delivery_at->format('d/m/Y')
                            : ($order->order_status === 'delivered'
                                ? 'Đã giao hàng'
                                : 'Đang cập nhật...');
                    @endphp
                    <div class="tko-eta-value">{{ $eta }}</div>
                </div>
            </div>

            {{-- ===== Timeline ===== --}}
            <div class="tko-timeline-wrap">
                <div class="tko-timeline-title">TIẾN ĐỘ VẬN CHUYỂN TRỰC TIẾP</div>
                @php
                    $steps = [
                        ['key' => ['pending', 'confirmed', 'processing', 'shipping', 'delivered'], 'icon' => 'check', 'label' => 'TIẾP NHẬN & ĐÓNG HỘP THỦ CÔNG', 'sub' => ''],
                        ['key' => ['processing', 'shipping', 'delivered'],                          'icon' => 'store', 'label' => 'RỜI FLAGSHIP BOUTIQUE',           'sub' => ''],
                        ['key' => ['shipping', 'delivered'],                                         'icon' => 'truck', 'label' => 'ĐANG CHUYỂN PHÁT RIÊNG',         'sub' => ''],
                        ['key' => ['delivered'],                                                     'icon' => 'box',   'label' => 'KÝ NHẬN HOÀN TẤT',               'sub' => ''],
                    ];
                    $currentStatus = $order->order_status;

                    // Map logs by status
                    $logsByStatus = $order->statusLogs->keyBy('status');

                    // Pick log date for each step
                    $stepDates = [
                        0 => $logsByStatus->get('confirmed') ?? $logsByStatus->get('pending') ?? null,
                        1 => $logsByStatus->get('processing') ?? null,
                        2 => $logsByStatus->get('shipping') ?? null,
                        3 => $logsByStatus->get('delivered') ?? null,
                    ];
                @endphp
                <div class="tko-timeline">
                    @foreach($steps as $i => $step)
                    @php
                        $done    = in_array($currentStatus, $step['key']);
                        $current = ($i === 2 && $currentStatus === 'shipping') || ($i === 1 && $currentStatus === 'processing') || ($i === 0 && in_array($currentStatus, ['pending','confirmed']));
                        $logItem = $stepDates[$i] ?? null;
                    @endphp
                    <div class="tko-step {{ $done ? 'tko-step-done' : '' }} {{ $i < count($steps)-1 ? '' : '' }}">
                        <div class="tko-step-circle {{ $done ? 'done' : 'pending' }}">
                            @if($done)
                            <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                            @else
                            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3" fill="currentColor"/></svg>
                            @endif
                        </div>
                        @if($i < count($steps)-1)
                        <div class="tko-step-line {{ $done ? 'done' : '' }}"></div>
                        @endif
                        <div class="tko-step-info">
                            <div class="tko-step-label {{ $done ? 'done' : '' }}">{{ $step['label'] }}</div>
                            @if($logItem)
                            <div class="tko-step-date">{{ $logItem->created_at->format('d/m • H:i') }}</div>
                            @if($logItem->note)
                            <div class="tko-step-note">{{ $logItem->note }}</div>
                            @endif
                            @elseif(!$done)
                            <div class="tko-step-date" style="color: #ddd;">Dự kiến</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ===== Bottom 3-col info ===== --}}
            <div class="tko-info-grid">
                {{-- Người nhận --}}
                <div class="tko-info-col">
                    <div class="tko-info-label">NGƯỜI NHẬN</div>
                    <div class="tko-info-name">{{ $order->recipient_name }}</div>
                    <div class="tko-info-phone">{{ $order->recipient_phone }}</div>
                </div>

                {{-- Địa chỉ --}}
                @php
                    $addr = $order->shipping_address_snapshot ?? [];
                    $addrParts = array_filter([
                        $addr['detail_address'] ?? null,
                        $addr['ward'] ?? null,
                        $addr['district'] ?? null,
                        $addr['province'] ?? null,
                    ]);
                    $fullAddr = implode(', ', $addrParts);
                    if (!$fullAddr) {
                        $fullAddr = $addr['address'] ?? ($addr['street'] ?? 'Xem trong tài khoản');
                    }
                @endphp
                <div class="tko-info-col">
                    <div class="tko-info-label">ĐỊA CHỈ GIAO HÀNG</div>
                    <div class="tko-info-addr">{{ $fullAddr }}</div>
                </div>

                {{-- Sản phẩm --}}
                <div class="tko-info-col">
                    <div class="tko-info-label">SẢN PHẨM TRONG KIỆN</div>
                    @foreach($order->items as $item)
                    <div class="tko-item-row">
                        <span class="tko-item-name">{{ $item->variant->product->name ?? 'Sản phẩm' }}</span>
                        @if($item->variant->display_name ?? null)
                        <span class="tko-item-variant">({{ $item->variant->display_name }})</span>
                        @endif
                        <span class="tko-item-qty">×{{ $item->quantity }}</span>
                    </div>
                    @endforeach
                    <div class="tko-total-row">
                        Tổng kiện:
                        <strong>{{ number_format($order->grand_total, 0, ',', '.') }}₫</strong>
                    </div>
                </div>
            </div>

        </div>{{-- /tko-result-card --}}
        @endif

    </div>{{-- /tko-container --}}
</div>{{-- /tko-page --}}

<style>
/* ===== PAGE ===== */
.tko-page {
    font-family: var(--font-sans, 'Inter', sans-serif);
    background: #f6f6f4;
    min-height: 80vh;
}

/* ===== HERO ===== */
.tko-hero {
    text-align: center;
    padding: 100px 24px 40px;
    background: #f6f6f4;
}
.tko-hero-sup {
    font-size: 9px;
    letter-spacing: 2.5px;
    font-weight: 700;
    color: #cda873;
    margin-bottom: 12px;
}
.tko-hero-title {
    font-family: var(--font-serif, Georgia, serif);
    font-size: 42px;
    font-weight: 400;
    color: #111;
    margin: 0 0 16px;
    line-height: 1.2;
}
.tko-hero-desc {
    font-size: 13px;
    color: #888;
    line-height: 1.8;
    margin: 0;
}

/* ===== CONTAINER ===== */
.tko-container {
    max-width: 820px;
    margin: 0 auto;
    padding: 0 24px 80px;
}

/* ===== SEARCH CARD ===== */
.tko-search-card {
    background: white;
    border: 1px solid #ebebeb;
    padding: 32px;
    margin-bottom: 28px;
}
.tko-search-row {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 16px;
    align-items: flex-end;
}
.tko-field { display: flex; flex-direction: column; gap: 7px; }
.tko-label {
    font-size: 9.5px;
    font-weight: 700;
    letter-spacing: 1px;
    color: #888;
    text-transform: uppercase;
}
.tko-label span { color: #cda873; }
.tko-input {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #e5e5e5;
    background: white;
    font-family: inherit;
    font-size: 13px;
    color: #111;
    outline: none;
    transition: border-color 0.2s;
    border-radius: 2px;
    box-sizing: border-box;
}
.tko-input:focus { border-color: #cda873; }
.tko-input::placeholder { color: #ccc; }
.tko-input-error { border-color: #e74c3c; }
.tko-err { font-size: 11px; color: #e74c3c; margin: 0; }

.tko-search-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: #111;
    color: white;
    border: none;
    font-family: inherit;
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: 1px;
    cursor: pointer;
    transition: background 0.2s;
    border-radius: 2px;
    white-space: nowrap;
    height: 46px;
}
.tko-search-btn svg {
    width: 13px; height: 13px;
    stroke: white; fill: none; stroke-width: 2;
}
.tko-search-btn:hover { background: #333; }

/* Alert */
.tko-alert-error {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    background: #fff3f3;
    border: 1px solid #f5c6cb;
    color: #c0392b;
    font-size: 12.5px;
    margin-top: 18px;
    border-radius: 2px;
}
.tko-alert-error svg {
    width: 15px; height: 15px;
    stroke: #c0392b; fill: none; stroke-width: 2;
    flex-shrink: 0;
}

/* Hint */
.tko-search-hint {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 18px;
    font-size: 11.5px;
    color: #aaa;
    flex-wrap: wrap;
}
.tko-search-hint svg {
    width: 14px; height: 14px;
    stroke: #cda873; fill: none; stroke-width: 2;
    flex-shrink: 0;
}
.tko-hint-link {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.8px;
    color: #cda873;
    text-decoration: underline;
    cursor: pointer;
    margin-left: 4px;
}

/* ===== RESULT CARD ===== */
.tko-result-card {
    background: white;
    border: 1px solid #ebebeb;
    overflow: hidden;
}

/* Header */
.tko-result-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 24px 28px;
    border-bottom: 1px solid #f0f0f0;
    gap: 20px;
}
.tko-result-code-row {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 6px;
}
.tko-result-code-label {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 1.5px;
    color: #bbb;
}
.tko-result-code {
    font-size: 15px;
    font-weight: 700;
    color: #cda873;
    font-family: var(--font-serif, Georgia, serif);
}
.tko-result-meta {
    font-size: 11.5px;
    color: #aaa;
    margin: 0;
    line-height: 1.5;
}

/* Status badges */
.tko-status-badge {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 0.8px;
    padding: 4px 10px;
    border-radius: 2px;
    white-space: nowrap;
}
.tko-badge-pending    { background: #fffbf0; color: #d4940a; border: 1px solid #f5e4a0; }
.tko-badge-confirmed  { background: #e8f4ff; color: #1565c0; border: 1px solid #b3d4f5; }
.tko-badge-processing { background: #f3e5ff; color: #7b1fa2; border: 1px solid #dab6f5; }
.tko-badge-shipping   { background: #e0f7fa; color: #00796b; border: 1px solid #a5d6d9; }
.tko-badge-delivered  { background: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; }
.tko-badge-cancelled  { background: #fff3f3; color: #c0392b; border: 1px solid #f5c6c6; }
.tko-badge-refunded   { background: #fce4ec; color: #ad1457; border: 1px solid #f48fb1; }
.tko-badge-default    { background: #f5f5f5; color: #777; border: 1px solid #e0e0e0; }

/* ETA */
.tko-result-right { text-align: right; flex-shrink: 0; }
.tko-eta-label {
    font-size: 8.5px;
    font-weight: 700;
    letter-spacing: 1.5px;
    color: #bbb;
    margin-bottom: 6px;
}
.tko-eta-value {
    font-family: var(--font-serif, Georgia, serif);
    font-size: 15px;
    color: #111;
    font-weight: 400;
}

/* ===== TIMELINE ===== */
.tko-timeline-wrap {
    padding: 28px 28px 24px;
    border-bottom: 1px solid #f0f0f0;
}
.tko-timeline-title {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 1.5px;
    color: #bbb;
    margin-bottom: 24px;
}
.tko-timeline {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    position: relative;
}
.tko-step {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    position: relative;
}
.tko-step-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 10px;
    z-index: 1;
    flex-shrink: 0;
}
.tko-step-circle.done {
    background: #111;
    border: 2px solid #111;
}
.tko-step-circle.pending {
    background: white;
    border: 2px solid #e0e0e0;
}
.tko-step-circle svg {
    width: 15px; height: 15px;
    stroke: white; fill: none; stroke-width: 2.5;
}
.tko-step-circle.pending svg { stroke: #ddd; }
.tko-step-line {
    position: absolute;
    top: 18px;
    left: 36px;
    right: -36px;
    height: 2px;
    background: #e8e8e8;
    z-index: 0;
    width: calc(100% - 0px);
}
.tko-step-line.done { background: #111; }
.tko-step-info {}
.tko-step-label {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.5px;
    color: #bbb;
    line-height: 1.4;
    margin-bottom: 4px;
}
.tko-step-label.done { color: #111; }
.tko-step-date { font-size: 10.5px; color: #aaa; margin-bottom: 2px; }
.tko-step-note { font-size: 10.5px; color: #888; line-height: 1.4; }

/* ===== INFO GRID ===== */
.tko-info-grid {
    display: grid;
    grid-template-columns: 1fr 1.5fr 1.5fr;
    gap: 0;
    border-top: 1px solid #f0f0f0;
}
.tko-info-col {
    padding: 22px 24px;
    border-right: 1px solid #f5f5f5;
}
.tko-info-col:last-child { border-right: none; }
.tko-info-label {
    font-size: 8.5px;
    font-weight: 700;
    letter-spacing: 1.5px;
    color: #bbb;
    margin-bottom: 10px;
}
.tko-info-name {
    font-size: 13.5px;
    font-weight: 600;
    color: #111;
    margin-bottom: 3px;
}
.tko-info-phone { font-size: 12px; color: #888; }
.tko-info-addr {
    font-size: 12px;
    color: #555;
    line-height: 1.6;
}
.tko-item-row {
    display: flex;
    align-items: baseline;
    gap: 5px;
    margin-bottom: 4px;
    font-size: 12px;
    flex-wrap: wrap;
}
.tko-item-name { font-weight: 500; color: #333; }
.tko-item-variant { color: #999; font-size: 11px; }
.tko-item-qty { color: #aaa; font-size: 11px; }
.tko-total-row {
    font-size: 11.5px;
    color: #888;
    margin-top: 8px;
    padding-top: 8px;
    border-top: 1px dashed #eee;
}
.tko-total-row strong { color: #111; }

/* ===== RESPONSIVE ===== */
@media (max-width: 700px) {
    .tko-search-row { grid-template-columns: 1fr; }
    .tko-search-btn { width: 100%; justify-content: center; }
    .tko-result-header { flex-direction: column; }
    .tko-result-right { text-align: left; }
    .tko-timeline { grid-template-columns: 1fr 1fr; gap: 20px; }
    .tko-step-line { display: none; }
    .tko-info-grid { grid-template-columns: 1fr; }
    .tko-info-col { border-right: none; border-bottom: 1px solid #f5f5f5; }
    .tko-hero-title { font-size: 30px; }
}
</style>

@endsection
