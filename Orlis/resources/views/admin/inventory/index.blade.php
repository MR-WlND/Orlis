@extends('layouts.admin')
@section('title', 'Quản lý Kho Hàng')
@section('page-style')
<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
    .stat-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 16px 20px; }
    .stat-label { font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }
    .stat-value { font-family: var(--font-serif); font-size: 24px; font-weight: 600; color: var(--accent); margin-top: 4px; }
    .filter-bar { display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; align-items: center; }
    .filter-bar input, .filter-bar select { padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 4px; font-size: 13px; background: var(--bg-card); color: var(--text-primary); }
    .btn { padding: 8px 16px; border-radius: 4px; display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 500; cursor: pointer; border: none; text-decoration: none; }
    .btn-primary { background: var(--accent); color: white; }
    .btn-sm { padding: 5px 10px; font-size: 12px; }
    .btn-outline { background: transparent; border: 1px solid var(--border-color); color: var(--text-primary); }
    .btn-warning { background: #faad14; color: white; }
    .table { width: 100%; border-collapse: collapse; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; }
    .table th, .table td { padding: 11px 16px; text-align: left; border-bottom: 1px solid var(--border-color); font-size: 13px; }
    .table th { font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); }
    .table tbody tr:last-child td { border-bottom: none; }
    .table tbody tr:hover { background: rgba(0,0,0,0.02); }
    .stock-bar-wrap { width: 100px; background: var(--border-color); border-radius: 4px; height: 6px; overflow: hidden; }
    .stock-bar { height: 6px; border-radius: 4px; }
    .qty-tag { display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; border-radius: 10px; font-size: 12px; font-weight: 600; }
    .qty-ok { color: #52c41a; background: #f6ffed; }
    .qty-low { color: #faad14; background: #fffbe6; }
    .qty-out { color: #f5222d; background: #fff1f0; }
    .alert { padding: 10px 14px; border-radius: 6px; margin-bottom: 16px; font-size: 13px; }
    .alert-success { background: #d4edda; color: #155724; }
    .alert-error { background: #f8d7da; color: #721c24; }
    /* Modal */
    .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; display: none; align-items: center; justify-content: center; }
    .modal-overlay.open { display: flex; }
    .modal { background: var(--bg-card); border-radius: 10px; padding: 28px; width: 420px; max-width: 90vw; }
    .modal-title { font-family: var(--font-serif); font-size: 18px; font-weight: 500; margin-bottom: 20px; }
    .modal label { display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; }
    .modal select, .modal input { width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 4px; font-size: 13px; background: var(--bg-card); color: var(--text-primary); margin-bottom: 14px; box-sizing: border-box; }
    .modal-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .btn-close { float: right; background: none; border: none; font-size: 18px; cursor: pointer; color: var(--text-muted); }
</style>
@endsection
@section('content')

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-error">{{ session('error') }}</div>@endif

<div class="page-header">
    <h2 style="font-family: var(--font-serif); font-size: 22px;">Quản lý Kho Hàng</h2>
    <div style="display:flex;gap:10px;">
        <button onclick="document.getElementById('upsertModal').classList.add('open')" class="btn btn-primary">+ Nhập/Cập nhật kho</button>
        <button onclick="document.getElementById('transferModal').classList.add('open')" class="btn btn-warning">↔ Điều chuyển hàng</button>
    </div>
</div>

{{-- Stats --}}
<div class="stats-row">
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
        <div class="stat-value" style="color:#faad14;">{{ number_format($stats['low_stock_count']) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Hết hàng</div>
        <div class="stat-value" style="color:#f5222d;">{{ number_format($stats['out_of_stock']) }}</div>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.inventory.index') }}" class="filter-bar">
    <input type="text" name="search" placeholder="Tên sản phẩm, SKU..." value="{{ request('search') }}" style="min-width:200px;">
    <select name="store_id">
        <option value="">-- Tất cả cửa hàng --</option>
        @foreach($stores as $store)
            <option value="{{ $store->id }}" @selected(request('store_id') == $store->id)>{{ $store->name }}</option>
        @endforeach
    </select>
    <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer;color:var(--text-primary);">
        <input type="checkbox" name="low_stock" value="1" @checked(request('low_stock')) style="width:14px;height:14px;">
        Chỉ hàng sắp hết
    </label>
    <button type="submit" class="btn btn-primary">Lọc</button>
    @if(request()->hasAny(['search','store_id','low_stock']))
        <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline">Xóa lọc</a>
    @endif
</form>

{{-- Table --}}
<table class="table">
    <thead>
        <tr>
            <th>Sản phẩm / SKU</th>
            <th>Cửa hàng</th>
            <th>Tổng kho</th>
            <th>Đã đặt giữ</th>
            <th>Khả dụng</th>
            <th>Mức tồn</th>
        </tr>
    </thead>
    <tbody>
        @forelse($inventory as $row)
        @php
            $availableQty = max(0, $row->available_qty);
            $stockClass = $availableQty <= 0 ? 'qty-out' : ($availableQty <= 5 ? 'qty-low' : 'qty-ok');
            $barPct = $row->stock_qty > 0 ? min(100, ($availableQty / $row->stock_qty) * 100) : 0;
            $barColor = $availableQty <= 0 ? '#f5222d' : ($availableQty <= 5 ? '#faad14' : '#52c41a');
        @endphp
        <tr>
            <td>
                <div style="font-weight:500;">{{ $row->product_name }}</div>
                <div style="font-size:11px;color:var(--text-muted);">SKU: {{ $row->sku }}</div>
            </td>
            <td>{{ $row->store_name }}</td>
            <td style="font-weight:600;">{{ number_format($row->stock_qty) }}</td>
            <td style="color:var(--text-muted);">{{ number_format($row->reserved_qty) }}</td>
            <td>
                <span class="qty-tag {{ $stockClass }}">{{ number_format($availableQty) }}</span>
            </td>
            <td>
                <div class="stock-bar-wrap">
                    <div class="stock-bar" style="width:{{ $barPct }}%;background:{{ $barColor }};"></div>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--text-muted);font-style:italic;">Không có dữ liệu tồn kho.</td></tr>
        @endforelse
    </tbody>
</table>

<div style="display:flex;justify-content:space-between;align-items:center;margin-top:20px;">
    <div style="font-size:12px;color:var(--text-muted);">{{ $inventory->firstItem() ?? 0 }}–{{ $inventory->lastItem() ?? 0 }} / {{ $inventory->total() }}</div>
    <div style="display:flex;gap:8px;">
        @if(!$inventory->onFirstPage())<a href="{{ $inventory->previousPageUrl() }}" class="btn btn-outline" style="font-size:11px;padding:6px 12px;">← Trước</a>@endif
        @if($inventory->hasMorePages())<a href="{{ $inventory->nextPageUrl() }}" class="btn btn-outline" style="font-size:11px;padding:6px 12px;">Sau →</a>@endif
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
            <button type="submit" class="btn btn-primary" style="width:100%;">Xác nhận</button>
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
            <button type="submit" class="btn btn-warning" style="width:100%;margin-top:4px;">Xác nhận điều chuyển</button>
        </form>
    </div>
</div>

@endsection
