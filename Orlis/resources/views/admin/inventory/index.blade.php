@extends('layouts.admin')
@section('title', 'Quản lý Kho Hàng')
@section('content')

@if(session('success'))
<div style="padding: 15px 20px; background: #fff; border: 1px solid #000; color: #000; margin-bottom: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">{{ session('success') }}</div>
@endif
@if(session('error'))
<div style="padding: 15px 20px; background: #fff; border: 1px solid #d93025; color: #d93025; margin-bottom: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">{{ session('error') }}</div>
@endif

<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <div class="header-text">
        <h2 class="page-title">Quản Lý Kho Hàng</h2>
        <p class="page-subtitle">Theo dõi và điều phối tồn kho theo từng cửa hàng.</p>
    </div>
    <div class="header-actions" style="display: flex; gap: 10px;">
        <button onclick="document.getElementById('upsertModal').classList.add('open')" class="btn-submit" style="cursor: pointer; display: inline-flex; align-items: center; height: 42px; padding: 0 20px;">+ NHẬP/CẬP NHẬT KHO</button>
        <button onclick="document.getElementById('transferModal').classList.add('open')" class="btn-cancel" style="cursor: pointer; display: inline-flex; align-items: center; height: 42px; padding: 0 20px;">↔ ĐIỀU CHUYỂN HÀNG</button>
    </div>
</div>

{{-- Stats --}}
<div class="stats-row" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 30px;">
    <div class="stat-card">
        <div class="stat-label">Tổng biến thể theo dõi</div>
        <div class="stat-value">{{ number_format($stats['total_variants']) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Cửa hàng</div>
        <div class="stat-value">{{ $stats['total_stores'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Sắp hết hàng (≤5)</div>
        <div class="stat-value" style="color: #faad14;">{{ number_format($stats['low_stock_count']) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Hết hàng</div>
        <div class="stat-value" style="color: #f5222d;">{{ number_format($stats['out_of_stock']) }}</div>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.inventory.index') }}" style="display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; align-items: center;">
    <input type="text" name="search" class="form-control" placeholder="Tên sản phẩm, SKU..." value="{{ request('search') }}" style="width: 240px; background: transparent;">
    <select name="store_id" class="form-control" style="width: 200px; background: transparent;">
        <option value="">-- Tất cả cửa hàng --</option>
        @foreach($stores as $store)
            <option value="{{ $store->id }}" @selected(request('store_id') == $store->id)>{{ $store->name }}</option>
        @endforeach
    </select>
    <label style="display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; cursor: pointer; color: var(--text-primary);">
        <input type="checkbox" name="low_stock" value="1" @checked(request('low_stock')) style="width: 14px; height: 14px;">
        Chỉ hàng sắp hết
    </label>
    <button type="submit" class="btn-submit" style="width: auto; padding: 0 20px;">LỌC DỮ LIỆU</button>
    @if(request()->hasAny(['search', 'store_id', 'low_stock']))
        <a href="{{ route('admin.inventory.index') }}" class="btn-cancel" style="padding: 0 20px; display: flex; align-items: center;">XÓA LỌC</a>
    @endif
</form>

{{-- Table --}}
<div class="table-container">
    <table class="luxury-table">
        <thead>
            <tr>
                <th>SẢN PHẨM / SKU</th>
                <th>CỬA HÀNG</th>
                <th style="text-align: center;">TỔNG KHO</th>
                <th style="text-align: center;">ĐÃ ĐẶT GIỮ</th>
                <th style="text-align: center;">KHẢ DỤNG</th>
                <th style="text-align: right; width: 140px;">MỨC TỒN</th>
            </tr>
        </thead>
        <tbody>
            @forelse($inventory as $row)
            @php
                $availableQty = max(0, $row->available_qty);
                $barPct = $row->stock_qty > 0 ? min(100, ($availableQty / $row->stock_qty) * 100) : 0;
                $barColor = $availableQty <= 0 ? '#f5222d' : ($availableQty <= 5 ? '#faad14' : '#52c41a');
                $badgeBg = $availableQty <= 0 ? '#fce4e4' : ($availableQty <= 5 ? '#fff8e1' : '#e8f5e9');
                $badgeColor = $availableQty <= 0 ? '#c62828' : ($availableQty <= 5 ? '#f57f17' : '#2e7d32');
            @endphp
            <tr>
                <td>
                    <div style="font-weight: 600; font-size: 13px; color: #111;">{{ $row->product_name }}</div>
                    <div style="font-size: 11px; color: #999; font-family: monospace; margin-top: 2px;">SKU: {{ $row->sku }}</div>
                </td>
                <td><span style="font-size: 13px; color: #666;">{{ $row->store_name }}</span></td>
                <td style="text-align: center; font-weight: 700; font-size: 13px;">{{ number_format($row->stock_qty) }}</td>
                <td style="text-align: center; font-size: 13px; color: #999;">{{ number_format($row->reserved_qty) }}</td>
                <td style="text-align: center;">
                    <span class="status-badge" style="background: {{ $badgeBg }}; color: {{ $badgeColor }}; border: none; font-weight: 700;">{{ number_format($availableQty) }}</span>
                </td>
                <td style="text-align: right;">
                    <div style="height: 6px; background: #f0f0f0; border-radius: 3px; overflow: hidden; min-width: 80px; display: inline-block; width: 100%;">
                        <div style="height: 100%; width: {{ $barPct }}%; background: {{ $barColor }}; border-radius: 3px; transition: width 0.3s;"></div>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align: center; padding: 40px; color: #999;">Không có dữ liệu tồn kho.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
    <div style="font-size: 12px; color: #999;">{{ $inventory->firstItem() ?? 0 }}–{{ $inventory->lastItem() ?? 0 }} / {{ $inventory->total() }} mục</div>
    <div style="display: flex; gap: 8px;">
        @if(!$inventory->onFirstPage())<a href="{{ $inventory->previousPageUrl() }}" class="btn-cancel" style="padding: 0 16px; display: flex; align-items: center; font-size: 12px; height: 36px;">← Trước</a>@endif
        @if($inventory->hasMorePages())<a href="{{ $inventory->nextPageUrl() }}" class="btn-submit" style="padding: 0 16px; display: flex; align-items: center; font-size: 12px; height: 36px;">Sau →</a>@endif
    </div>
</div>

{{-- Upsert Modal --}}
<div id="upsertModal" class="modal-overlay" onclick="if(event.target===this)this.classList.remove('open')">
    <div class="modal">
        <button class="btn-close" onclick="document.getElementById('upsertModal').classList.remove('open')">✕</button>
        <div class="modal-title">Nhập / Cập nhật Kho</div>
        <form method="POST" action="{{ route('admin.inventory.upsert') }}">
            @csrf
            @method('PUT')
            <label>Cửa hàng *</label>
            <select name="store_id" required>
                <option value="">-- Chọn cửa hàng --</option>
                @foreach($stores as $store)
                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                @endforeach
            </select>
            <label>Variant ID (SKU) *</label>
            <input type="number" name="variant_id" placeholder="Nhập ID biến thể sản phẩm..." required>
            <div class="modal-row">
                <div>
                    <label>Thao tác *</label>
                    <select name="action" required>
                        <option value="set">Đặt về (Set)</option>
                        <option value="add">Cộng thêm</option>
                        <option value="subtract">Trừ bớt</option>
                    </select>
                </div>
                <div>
                    <label>Số lượng *</label>
                    <input type="number" name="stock_qty" min="0" required placeholder="0">
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Xác nhận</button>
        </form>
    </div>
</div>

{{-- Transfer Modal --}}
<div id="transferModal" class="modal-overlay" onclick="if(event.target===this)this.classList.remove('open')">
    <div class="modal">
        <button class="btn-close" onclick="document.getElementById('transferModal').classList.remove('open')">✕</button>
        <div class="modal-title">Điều Chuyển Hàng Giữa Kho</div>
        <form method="POST" action="{{ route('admin.inventory.transfer') }}">
            @csrf
            <label>Variant ID *</label>
            <input type="number" name="variant_id" placeholder="ID biến thể sản phẩm..." required>
            <div class="modal-row">
                <div>
                    <label>Kho nguồn *</label>
                    <select name="from_store_id" required>
                        <option value="">-- Kho gốc --</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Kho đích *</label>
                    <select name="to_store_id" required>
                        <option value="">-- Kho nhận --</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <label>Số lượng điều chuyển *</label>
            <input type="number" name="qty" min="1" required placeholder="1">
            <button type="submit" class="btn btn-warning" style="width: 100%; margin-top: 4px;">Xác nhận điều chuyển</button>
        </form>
    </div>
</div>

@endsection
