@extends('layouts.admin')

@section('title', 'Quản lý Banner')

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
        text-decoration: none;
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
        padding: 12px 30px;
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
        padding: 16px 30px;
    }
    .luxury-table tr:last-child td { border-bottom: none; }
    
    .banner-preview {
        width: 120px;
        height: 60px;
        background-color: #eaeaea;
        overflow: hidden;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .banner-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .banner-info {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .banner-title {
        font-family: var(--font-serif);
        font-size: 16px;
        color: var(--text-primary);
        font-weight: 500;
    }
    .banner-desc {
        font-size: 11px;
        color: #888;
        margin-top: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 250px;
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
        gap: 12px;
        align-items: center;
    }
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background: transparent;
        border: 1px solid transparent;
        color: #666;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .action-btn svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
        fill: none;
        stroke-width: 1.5;
        stroke-linecap: round;
        stroke-linejoin: round;
    }
    .action-btn:hover { background: #f5f5f5; border-color: #ddd; color: var(--text-primary); }
    .action-btn.delete:hover { background: #fff1f0; border-color: #ffccc7; color: #d93025; }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="header-text">
        <h2 class="page-title">Quản lý Banner</h2>
        <p class="page-subtitle">Thay đổi hình ảnh và thông điệp truyền thông trên toàn hệ thống.</p>
    </div>
    <a href="{{ route('admin.banners.create') }}" class="btn-add-new">
        <span>+</span> THÊM BANNER MỚI
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
                <th>HÌNH ẢNH</th>
                <th>TIÊU ĐỀ</th>
                <th>VỊ TRÍ</th>
                <th>THỨ TỰ</th>
                <th>TRẠNG THÁI</th>
                <th>HÀNH ĐỘNG</th>
            </tr>
        </thead>
        <tbody>
            @forelse($banners as $banner)
            <tr>
                <td>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <div class="banner-preview">
                            <img src="{{ Storage::url($banner->image_path) }}" alt="{{ $banner->title }}" title="Desktop Image">
                        </div>
                        @if($banner->image_mobile_path)
                        <div class="banner-preview" style="width: 40px; border-radius: 2px;">
                            <img src="{{ Storage::url($banner->image_mobile_path) }}" alt="Mobile Image" title="Mobile Image">
                        </div>
                        @endif
                    </div>
                </td>
                <td>
                    <div class="banner-info">
                        <span class="banner-title">{{ $banner->title ?? '(Không có tiêu đề)' }}</span>
                        <span class="banner-desc">{{ Str::limit($banner->description, 50) }}</span>
                        <div style="margin-top: 4px; display: flex; gap: 10px; align-items: center;">
                            @if($banner->link_url)
                                <a href="{{ $banner->link_url }}" target="_blank" style="font-size: 10px; color: #1890ff; text-decoration: underline;">Xem Link</a>
                            @endif
                            <div style="display: flex; align-items: center; gap: 4px; font-size: 10px; color: #555;">
                                Màu chữ: <span style="display: inline-block; width: 12px; height: 12px; border: 1px solid #ccc; background-color: {{ $banner->text_color }};"></span>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <span style="font-size: 12px; color: #555; background: #f0f0f0; padding: 4px 8px; border-radius: 4px; display: inline-block; margin-bottom: 5px;">{{ $banner->position }}</span>
                    <div style="font-size: 11px; margin-top: 5px;">
                        @if($banner->is_global)
                            <span style="color: #fff; background: #d93025; padding: 2px 6px; border-radius: 2px; font-weight: 600;">GLOBAL</span>
                        @elseif($banner->category_ids)
                            <span style="color: #1890ff;">{{ count($banner->category_ids) }} danh mục</span>
                        @endif
                    </div>
                </td>
                <td><span style="font-size: 14px; font-weight: 600;">{{ $banner->order }}</span></td>
                <td>
                    @if($banner->is_active)
                        <span class="status-badge">Kích hoạt</span>
                    @else
                        <span class="status-badge inactive">Đã tắt</span>
                    @endif
                </td>
                <td>
                    <div class="action-links">
                        <a href="{{ route('admin.banners.edit', $banner->id) }}" class="action-btn" title="Chỉnh sửa">
                            <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </a>
                        <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa banner này? Hành động này sẽ xóa cả file ảnh.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete" title="Xóa">
                                <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px; color: #999;">Chưa có banner nào được tạo.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
