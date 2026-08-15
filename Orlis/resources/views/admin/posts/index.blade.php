@extends('layouts.admin')

@section('title', 'Quản lý Tạp chí')

@section('content')
<div style="background: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0; font-size: 18px; color: #333;">Danh sách Tạp chí</h2>
        <a href="{{ route('admin.posts.create') }}" style="background: #000; color: #fff; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-size: 14px;">+ Thêm Tạp chí</a>
    </div>

    @if(session('success'))
        <div style="padding: 10px; background: #e6f4ea; color: #1e8e3e; margin-bottom: 15px; border-radius: 4px;">{{ session('success') }}</div>
    @endif

    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
        <thead>
            <tr style="border-bottom: 2px solid #eee; text-align: left;">
                <th style="padding: 12px 8px;">ID</th>
                <th style="padding: 12px 8px;">Tiêu đề</th>
                <th style="padding: 12px 8px;">Trạng thái</th>
                <th style="padding: 12px 8px;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($posts as $post)
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 12px 8px;">{{ $post->id }}</td>
                <td style="padding: 12px 8px;">{{ $post->title }}</td>
                <td style="padding: 12px 8px;">{{ ucfirst($post->status) }}</td>
                <td style="padding: 12px 8px;">
                    <a href="{{ route('admin.posts.edit', $post->id) }}" style="color: #1a73e8; margin-right: 10px; text-decoration: none;">Sửa</a>
                    <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                        @csrf @method('DELETE')
                        <button type="submit" style="background: none; border: none; color: #ea4335; cursor: pointer; font-size: 14px;">Xóa</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center; padding: 20px;">Chưa có bài viết nào.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="margin-top: 20px;">
        {{ $posts->links() }}
    </div>
</div>
@endsection
