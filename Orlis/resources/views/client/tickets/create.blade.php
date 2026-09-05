@extends('layouts.client')
@section('title', 'Tạo Yêu Cầu Hỗ Trợ')
@section('content')
<div style="background: #f9f9f9; padding: 40px 0; min-height: 80vh;">
    <div class="container" style="max-width: 700px; margin: 0 auto; padding: 0 20px;">
        <h2 style="font-family: var(--font-serif); font-size: 24px; margin-bottom: 20px;">Gửi Yêu Cầu Hỗ Trợ Mới</h2>
        
        <div style="background: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); padding: 30px;">
            <form action="{{ route('tickets.store') }}" method="POST">
                @csrf
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; color: #333; margin-bottom: 8px; font-weight: 500;">Chủ đề</label>
                    <input type="text" name="subject" required placeholder="Ví dụ: Lỗi thanh toán, Đổi trả sản phẩm..." style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-family: inherit;">
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; color: #333; margin-bottom: 8px; font-weight: 500;">Mức độ ưu tiên</label>
                    <select name="priority" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-family: inherit;">
                        <option value="normal">Bình thường</option>
                        <option value="high">Gấp (Cần xử lý ngay)</option>
                        <option value="low">Thấp</option>
                    </select>
                </div>
                
                <div style="margin-bottom: 25px;">
                    <label style="display: block; font-size: 14px; color: #333; margin-bottom: 8px; font-weight: 500;">Nội dung chi tiết</label>
                    <textarea name="message" rows="6" required placeholder="Vui lòng mô tả chi tiết vấn đề của bạn..." style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-family: inherit; resize: vertical;"></textarea>
                </div>
                
                <div style="display: flex; gap: 15px; justify-content: flex-end;">
                    <a href="{{ route('tickets.index') }}" style="padding: 12px 20px; background: #f1f1f1; color: #333; text-decoration: none; border-radius: 4px; font-size: 14px;">Hủy bỏ</a>
                    <button type="submit" style="padding: 12px 25px; background: #111; color: white; border: none; border-radius: 4px; font-size: 14px; cursor: pointer;">Gửi yêu cầu</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
