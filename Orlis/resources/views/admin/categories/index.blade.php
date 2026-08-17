@extends('layouts.admin')

@section('title', 'Quản lý Danh mục')

@section('page-style')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 40px;
    }
    .header-text {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .page-title {
        font-family: var(--font-serif);
        font-size: 32px;
        color: var(--text-primary);
        font-weight: 500;
        margin: 0;
    }
    .page-subtitle {
        font-family: var(--font-sans);
        font-size: 13px;
        color: var(--text-secondary);
        margin: 0;
    }
    .btn-add-new {
        display: inline-flex;
        align-items: center;
        background-color: transparent;
        color: var(--text-primary);
        padding: 12px 20px;
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        border: 1px solid var(--text-primary);
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-add-new span {
        margin-right: 8px;
        font-size: 14px;
        font-weight: 400;
    }
    .btn-add-new:hover { background-color: #333; color: #fff;}

    .table-container {
        background: #fff;
        border: 1px solid var(--border-color);
        padding: 0;
    }
    .luxury-table {
        width: 100%;
        border-collapse: collapse;
    }
    .luxury-table th, .luxury-table td {
        padding: 20px 30px;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }
    .luxury-table th {
        font-size: 10px;
        font-weight: 600;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 2px;
        background: #fdfdfd;
    }
    .luxury-table tr:last-child td { border-bottom: none; }
    
    .cat-name-col {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .cat-icon-box {
        width: 45px;
        height: 45px;
        background-color: #eaeaea;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #555;
    }
    .cat-icon-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .cat-icon-box svg {
        width: 20px;
        height: 20px;
        stroke: currentColor;
        stroke-width: 1.5;
        fill: none;
    }
    .cat-title {
        font-family: var(--font-serif);
        font-size: 20px;
        color: var(--text-primary);
    }
    .cat-count {
        font-size: 12px;
        color: #555;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        border: 1px solid #e0e0e0;
        font-size: 10px;
        color: #555;
        background: #fbfbfb;
        white-space: nowrap;
    }
    .status-badge::before {
        content: '';
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background-color: var(--text-primary);
    }
    .status-badge.inactive {
        color: #999;
    }
    .status-badge.inactive::before {
        background-color: #999;
    }

    .action-links {
        display: flex;
        gap: 15px;
        align-items: center;
    }
    .action-link {
        font-size: 11px;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 1px;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        text-align: left;
        width: 40px; /* Fix width so the next button aligns vertically */
    }
    .action-link:hover { color: var(--text-primary); }
    .action-link.delete:hover { color: #d93025; }

    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 30px;
    }
    .pagination-info {
        font-size: 11px;
        color: var(--text-secondary);
    }
    .pagination-buttons {
        display: flex;
        gap: 10px;
    }
    .btn-page {
        padding: 10px 15px;
        font-size: 10px;
        font-weight: 600;
        color: var(--text-primary);
        text-transform: uppercase;
        letter-spacing: 1px;
        border: 1px solid var(--border-color);
        background: #fff;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-page:hover { background: #f5f5f5; }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="header-text">
        <h2 class="page-title">Quản lý Danh mục</h2>
        <p class="page-subtitle">Phân loại các bộ sưu tập và dòng sản phẩm xa xỉ của thương hiệu Orlis.</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn-add-new">
        <span>+</span> THÊM DANH MỤC MỚI
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
                <th>TÊN DANH MỤC</th>
                <th>ĐƯỜNG DẪN (SLUG)</th>
                <th>DANH MỤC GỐC</th>
                <th>SỐ LƯỢNG SP</th>
                <th>TRẠNG THÁI</th>
                <th>HÀNH ĐỘNG</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $cat)
            <tr>
                <td>
                    <div class="cat-name-col">
                        <div class="cat-icon-box">
                            @if($cat->image)
                                <img src="{{ filter_var($cat->image, FILTER_VALIDATE_URL) ? $cat->image : Storage::url($cat->image) }}" alt="{{ $cat->name }}">
                            @else
                                <svg viewBox="0 0 24 24"><path d="M4 19h16v-9H4v9z"></path><path d="M16 10V6c0-2.21-1.79-4-4-4S8 3.79 8 6v4"></path></svg>
                            @endif
                        </div>
                        <span class="cat-title">{{ $cat->name }}</span>
                    </div>
                </td>
                <td><span class="cat-count">{{ $cat->slug }}</span></td>
                <td><span class="cat-count" style="font-weight: 600;">Danh mục gốc</span></td>
                <td>
                    <span class="cat-count">{{ $cat->products()->count() ?? rand(10, 300) }}</span>
                </td>
                <td>
                    @if(isset($cat->status) && $cat->status == 0)
                        <span class="status-badge inactive">Ẩn</span>
                    @else
                        <span class="status-badge">Hiển thị</span>
                    @endif
                </td>
                <td>
                    <div class="action-links">
                        <a href="{{ route('admin.categories.edit', $cat->id) }}" class="action-link">Sửa</a>
                        <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-link delete">Xóa</button>
                        </form>
                    </div>
                </td>
            </tr>
                @foreach($cat->children as $child)
                <tr>
                    <td>
                        <div class="cat-name-col" style="padding-left: 45px;">
                            <span style="color: #999; margin-right: 5px; font-size: 16px;">↳</span>
                            <div class="cat-icon-box" style="width: 32px; height: 32px;">
                                @if($child->image)
                                    <img src="{{ filter_var($child->image, FILTER_VALIDATE_URL) ? $child->image : Storage::url($child->image) }}" alt="{{ $child->name }}">
                                @else
                                    <svg viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path d="M4 19h16v-9H4v9z"></path><path d="M16 10V6c0-2.21-1.79-4-4-4S8 3.79 8 6v4"></path></svg>
                                @endif
                            </div>
                            <span class="cat-title" style="font-size: 16px;">{{ $child->name }}</span>
                        </div>
                    </td>
                    <td><span class="cat-count">{{ $child->slug }}</span></td>
                    <td><span class="cat-count">{{ $cat->name }}</span></td>
                    <td>
                        <span class="cat-count">{{ $child->products()->count() ?? 0 }}</span>
                    </td>
                    <td>
                        @if(isset($child->status) && $child->status == 0)
                            <span class="status-badge inactive">Ẩn</span>
                        @else
                            <span class="status-badge">Hiển thị</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-links">
                            <a href="{{ route('admin.categories.edit', $child->id) }}" class="action-link">Sửa</a>
                            <form action="{{ route('admin.categories.destroy', $child->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-link delete">Xóa</button>
                            </form>
                    @foreach($child->children as $grandchild)
                    <tr style="background-color: #fafafa;">
                        <td>
                            <div class="cat-name-col" style="padding-left: 70px;">
                                <div style="width: 15px; height: 1px; background-color: #ddd; margin-right: 10px;"></div>
                                <div class="cat-icon-box" style="width: 28px; height: 28px; background-color: #fff; border: 1px solid #eee;">
                                    @if($grandchild->image)
                                        <img src="{{ filter_var($grandchild->image, FILTER_VALIDATE_URL) ? $grandchild->image : Storage::url($grandchild->image) }}" alt="{{ $grandchild->name }}">
                                    @else
                                        <svg viewBox="0 0 24 24" style="width: 14px; height: 14px; stroke: #999;"><path d="M4 19h16v-9H4v9z"></path><path d="M16 10V6c0-2.21-1.79-4-4-4S8 3.79 8 6v4"></path></svg>
                                    @endif
                                </div>
                                <span class="cat-title" style="font-size: 14px; color: #555;">{{ $grandchild->name }}</span>
                            </div>
                        </td>
                        <td><span class="cat-count" style="font-size: 11px; color: #888;">{{ $grandchild->slug }}</span></td>
                        <td><span class="cat-count" style="font-size: 11px; color: #888;">{{ $child->name }}</span></td>
                        <td>
                            <span class="cat-count" style="color: #888;">{{ $grandchild->products()->count() ?? 0 }}</span>
                        </td>
                        <td>
                            @if(isset($grandchild->status) && $grandchild->status == 0)
                                <span class="status-badge inactive">Ẩn</span>
                            @else
                                <span class="status-badge">Hiển thị</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-links">
                                <a href="{{ route('admin.categories.edit', $grandchild->id) }}" class="action-link">Sửa</a>
                                <form action="{{ route('admin.categories.destroy', $grandchild->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-link delete">Xóa</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>

<div class="pagination-container">
    <div class="pagination-info">
        Hiển thị {{ $categories->firstItem() ?? 0 }} - {{ $categories->lastItem() ?? 0 }} trên tổng số {{ $categories->total() ?? 0 }} danh mục
    </div>
    <div class="pagination-buttons">
        @if ($categories->onFirstPage())
            <button class="btn-page" disabled style="opacity: 0.5; cursor: not-allowed;">TRANG TRƯỚC</button>
        @else
            <a href="{{ $categories->previousPageUrl() }}" class="btn-page">TRANG TRƯỚC</a>
        @endif

        @if ($categories->hasMorePages())
            <a href="{{ $categories->nextPageUrl() }}" class="btn-page">TIẾP THEO</a>
        @else
            <button class="btn-page" disabled style="opacity: 0.5; cursor: not-allowed;">TIẾP THEO</button>
        @endif
    </div>
</div>
@endsection
