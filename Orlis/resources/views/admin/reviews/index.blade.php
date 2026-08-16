@extends('layouts.admin')

@section('title', 'Quản lý Đánh giá')

@section('content')
<div style="background: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0; font-size: 20px; font-weight: 600;">Đánh giá từ người dùng</h2>
        
        <div>
            <form action="{{ route('admin.reviews.index') }}" method="GET" style="display: flex; gap: 10px;">
                <select name="status" style="padding: 6px 12px; border: 1px solid #ddd; border-radius: 4px;" onchange="this.form.submit()">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                    <option value="hidden" {{ request('status') == 'hidden' ? 'selected' : '' }}>Đã ẩn</option>
                </select>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #e6f7ff; color: #1890ff; padding: 10px 15px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #91d5ff;">
            {{ session('success') }}
        </div>
    @endif

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f8f9fa; border-bottom: 2px solid #eee;">
                    <th style="padding: 12px; width: 250px;">Sản phẩm</th>
                    <th style="padding: 12px;">Khách hàng</th>
                    <th style="padding: 12px;">Đánh giá</th>
                    <th style="padding: 12px;">Nội dung</th>
                    <th style="padding: 12px;">Hình ảnh</th>
                    <th style="padding: 12px;">Trạng thái</th>
                    <th style="padding: 12px; text-align: right;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px;">
                            <div style="font-weight: 500;">{{ optional($review->product)->name ?? 'N/A' }}</div>
                        </td>
                        <td style="padding: 12px;">
                            <div style="font-weight: 500;">{{ optional($review->user)->name ?? 'Khách ẩn danh' }}</div>
                            <div style="font-size: 12px; color: #888;">{{ $review->created_at->format('d/m/Y H:i') }}</div>
                        </td>
                        <td style="padding: 12px;">
                            <div style="color: #fa8c16; font-size: 16px;">
                                {!! str_repeat('★', $review->rating) !!}{!! str_repeat('☆', 5 - $review->rating) !!}
                            </div>
                        </td>
                        <td style="padding: 12px; max-width: 300px;">
                            <div style="white-space: pre-wrap; font-size: 13px;">{{ $review->comment }}</div>
                        </td>
                        <td style="padding: 12px;">
                            @if($review->images && is_array($review->images))
                                <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                    @foreach($review->images as $img)
                                        <img src="{{ Storage::url($img) }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td style="padding: 12px;">
                            @if($review->status == 'pending')
                                <span style="background: #fffbe6; color: #faad14; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Chờ duyệt</span>
                            @elseif($review->status == 'approved')
                                <span style="background: #f6ffed; color: #52c41a; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Đã duyệt</span>
                            @else
                                <span style="background: #fff1f0; color: #f5222d; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Đã ẩn</span>
                            @endif
                        </td>
                        <td style="padding: 12px; text-align: right;">
                            <form action="{{ route('admin.reviews.updateStatus', $review->id) }}" method="POST" style="display: inline-block; margin-bottom: 5px;">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" style="padding: 4px; border: 1px solid #ddd; border-radius: 4px; font-size: 12px;">
                                    <option value="pending" {{ $review->status == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                                    <option value="approved" {{ $review->status == 'approved' ? 'selected' : '' }}>Duyệt hiển thị</option>
                                    <option value="hidden" {{ $review->status == 'hidden' ? 'selected' : '' }}>Ẩn</option>
                                </select>
                            </form>
                            <br>
                            <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn đánh giá này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color: #f5222d; border: none; background: transparent; cursor: pointer; padding: 0; font-size: 12px; margin-top: 5px;">Xóa bỏ</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 20px; text-align: center; color: #888;">Không có đánh giá nào</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $reviews->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
