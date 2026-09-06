@extends('layouts.admin')

@section('title', 'Quản lý Banner')

@section('page-style')

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
