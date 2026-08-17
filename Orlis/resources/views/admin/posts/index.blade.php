@extends('layouts.admin')

@section('title', 'Quản lý Tạp chí')

@section('page-style')
<style>
    .admin-posts-container {
        font-family: var(--font-sans, 'Inter', sans-serif);
    }
    .header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .filter-bar {
        display: flex;
        align-items: center;
        gap: 20px;
        background: #fff;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }
    .search-input {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 10px;
        border-right: 1px solid #eee;
        padding-right: 20px;
    }
    .search-input input {
        border: none;
        outline: none;
        width: 100%;
        font-size: 14px;
        color: #333;
    }
    .filter-selects {
        display: flex;
        gap: 20px;
        align-items: center;
    }
    .filter-group {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        color: #555;
    }
    .filter-group select {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 6px 10px;
        font-size: 13px;
        outline: none;
        color: #333;
    }
    .table-container {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }
    th {
        padding: 16px 20px;
        text-align: left;
        color: #666;
        font-weight: 500;
        font-size: 13px;
        border-bottom: 1px solid #eee;
        white-space: nowrap;
    }
    td {
        padding: 20px;
        vertical-align: middle;
        border-bottom: 1px solid #eee;
        color: #333;
    }
    .post-img {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 4px;
    }
    .post-title {
        font-weight: 500;
        font-size: 15px;
        font-family: var(--font-serif, 'Playfair Display', serif);
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        max-width: 220px;
        line-height: 1.4;
    }
    .status-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        background: #f0f0f0;
        color: #555;
        white-space: nowrap;
    }
    .action-links a, .action-links button {
        color: #666;
        text-decoration: none;
        margin-right: 10px;
        font-size: 13px;
    }
    .action-links a:hover, .action-links button:hover {
        color: #000;
    }
    /* Pagination Fix */
    nav[role="navigation"] {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
        color: #666;
    }
    nav[role="navigation"] p { margin: 0; }
    nav[role="navigation"] > div { display: flex; align-items: center; gap: 20px; width: 100%; justify-content: space-between; }
    nav[role="navigation"] svg { width: 16px; height: 16px; }
    .relative.z-0.inline-flex { display: inline-flex; border: 1px solid #ddd; border-radius: 4px; overflow: hidden; }
    .relative.z-0.inline-flex > * { padding: 8px 12px; background: #fff; border-left: 1px solid #ddd; color: #333; text-decoration: none; display: flex; align-items: center; }
    .relative.z-0.inline-flex > *:first-child { border-left: none; }
    .relative.z-0.inline-flex > [aria-current="page"] { background: #000; color: #fff; }
    .relative.z-0.inline-flex > *:hover:not([aria-current="page"]):not([aria-disabled="true"]) { background: #f5f5f5; }
</style>
@endsection

@section('content')
<div class="admin-posts-container">
    <div class="header-actions">
        <h2 style="margin: 0; font-size: 24px; font-family: var(--font-serif, serif); font-weight: 400;">Danh sách Tạp chí</h2>
        <a href="{{ route('admin.posts.create') }}" style="background: #000; color: #fff; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-size: 13px; font-weight: 500; text-transform: uppercase; letter-spacing: 1px;">+ Viết bài mới</a>
    </div>

    @if(session('success'))
        <div style="padding: 12px 20px; background: #e6f4ea; color: #1e8e3e; margin-bottom: 20px; border-radius: 4px;">{{ session('success') }}</div>
    @endif

    <div class="filter-bar">
        <form action="{{ route('admin.posts.index') }}" method="GET" style="display: flex; width: 100%; align-items: center; justify-content: space-between; margin: 0;">
            <div class="search-input">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm theo tiêu đề..." onchange="this.form.submit()">
            </div>
            
            <div class="filter-selects">
                <div class="filter-group">
                    <label>Danh mục:</label>
                    <select name="category_id" onchange="this.form.submit()">
                        <option value="">Tất cả</option>
                        @foreach(\App\Models\PostCategory::all() as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label>Trạng thái:</label>
                    <select name="status" onchange="this.form.submit()">
                        <option value="">Tất cả</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Đã xuất bản</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Lưu trữ</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 80px;">Hình ảnh</th>
                    <th>Tiêu đề</th>
                    <th>Danh mục</th>
                    <th>Tác giả</th>
                    <th>Ngày tạo</th>
                    <th>Trạng thái</th>
                    <th style="text-align: right;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                <tr>
                    <td>
                        @if($post->thumbnail)
                            <img src="{{ str_starts_with($post->thumbnail, 'http') ? $post->thumbnail : Storage::url($post->thumbnail) }}" class="post-img">
                        @else
                            <div class="post-img" style="background: #f5f5f5; display: flex; align-items: center; justify-content: center; color: #ccc; font-size: 11px;">No IMG</div>
                        @endif
                    </td>
                    <td><div class="post-title">{{ $post->title }}</div></td>
                    <td style="color: #666; font-size: 13px;">
                        {{ $post->category ? $post->category->name : 'N/A' }}
                        @if($post->department)
                            <br><small style="color: #999;">({{ $post->department == 'fashion' ? 'Thời trang' : 'Nước hoa' }})</small>
                        @endif
                    </td>
                    <td style="color: #666; font-size: 13px;">{{ $post->author ? $post->author->name : 'Admin' }}</td>
                    <td style="color: #666; font-size: 13px; line-height: 1.5;">
                        {{ $post->created_at->format('d') }}<br>
                        Thg {{ $post->created_at->format('m, Y') }}
                    </td>
                    <td>
                        <span class="status-badge">
                            @if($post->status == 'published') Đã xuất bản
                            @elseif($post->status == 'draft') Bản nháp
                            @else Lưu trữ @endif
                        </span>
                    </td>
                    <td class="action-links" style="text-align: right;">
                        <a href="{{ route('admin.posts.edit', $post->id) }}">Sửa</a>
                        <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?');">
                            @csrf @method('DELETE')
                            <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0;">Xóa</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: #888;">Chưa có bài viết nào phù hợp.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 20px;">
        {{ $posts->links() }}
    </div>
</div>
@endsection
