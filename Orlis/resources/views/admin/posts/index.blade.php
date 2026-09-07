@extends('layouts.admin')

@section('title', 'Quản lý Tạp chí')

@section('page-style')

@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Danh sách Tạp chí</h2>
        <p class="page-subtitle">Quản lý và xuất bản các bài viết trên hệ thống Tạp chí Orlis.</p>
    </div>
    <a href="{{ route('admin.posts.create') }}" class="btn-add-new">
        <span style="margin-right: 8px;">+</span> VIẾT BÀI MỚI
    </a>
</div>

<div class="table-container">

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 20px;">{{ session('success') }}</div>
    @endif

    <div class="filter-bar" style="padding: 20px 30px; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between;">
        <form action="{{ route('admin.posts.index') }}" method="GET" style="display: flex; width: 100%; align-items: center; justify-content: space-between; margin: 0;">
            <div class="search-input" style="display: flex; align-items: center; gap: 10px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm theo tiêu đề..." onchange="this.form.submit()" style="border: none; outline: none; font-size: 13px; font-family: inherit;">
            </div>
            
            <div class="filter-selects" style="display: flex; gap: 20px;">
                <div class="filter-group" style="display: flex; align-items: center; gap: 8px;">
                    <label style="font-size: 10px; font-weight: 600; text-transform: uppercase; color: #888;">Danh mục:</label>
                    <select name="category_id" onchange="this.form.submit()" style="border: 1px solid #ddd; padding: 6px 12px; font-size: 13px; outline: none;">
                        <option value="">Tất cả</option>
                        @foreach(\App\Models\PostCategory::all() as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group" style="display: flex; align-items: center; gap: 8px;">
                    <label style="font-size: 10px; font-weight: 600; text-transform: uppercase; color: #888;">Trạng thái:</label>
                    <select name="status" onchange="this.form.submit()" style="border: 1px solid #ddd; padding: 6px 12px; font-size: 13px; outline: none;">
                        <option value="">Tất cả</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Đã xuất bản</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Lưu trữ</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <table class="luxury-table">
        <thead>
            <tr>
                <th style="width: 80px;">HÌNH ẢNH</th>
                <th>TIÊU ĐỀ</th>
                <th>DANH MỤC</th>
                <th>TÁC GIẢ</th>
                <th>NGÀY TẠO</th>
                <th>TRẠNG THÁI</th>
                <th style="text-align: right;">HÀNH ĐỘNG</th>
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
                    <td style="text-align: right;">
                        <div class="action-links" style="display: flex; gap: 15px; justify-content: flex-end;">
                            <a href="{{ route('admin.posts.edit', $post->id) }}" style="color: var(--text-primary); text-decoration: none; font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">SỬA</a>
                            <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?');">
                                @csrf @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #f5222d; cursor: pointer; padding: 0; font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">XÓA</button>
                            </form>
                        </div>
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
    
    {{ $posts->links('vendor.pagination.admin') }}

@endsection
