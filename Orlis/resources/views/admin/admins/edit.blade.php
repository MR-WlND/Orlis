@extends('layouts.admin')

@section('title', 'Chỉnh sửa Nhân sự')

@section('page-style')

@endsection

@section('content')
<form action="{{ route('admin.admins.update', $admin->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="page-header">
        <h1 class="page-title">Sửa tài khoản</h1>
        <div class="header-actions">
            <a href="{{ route('admin.admins.index') }}" class="btn-cancel">Hủy</a>
            <button type="submit" class="btn-submit">Cập nhật thay đổi</button>
        </div>
    </div>

    @if($errors->any())
        <div style="background: #fff0f0; color: #d00; padding: 15px; margin-bottom: 30px; border: 1px solid #ffcccc; font-size: 13px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="edit-grid">
        <!-- Cột trái: Thông tin chính -->
        <div class="form-card">
            <h2 class="card-title">Thông tin tài khoản</h2>
            
            <div class="form-group">
                <label>Họ và Tên</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $admin->name) }}" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $admin->email) }}" required>
            </div>
            
            <div class="form-group">
                <label>Số điện thoại</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $admin->phone) }}">
            </div>
            
            <div class="form-group">
                <label>Mật khẩu (Để trống nếu không đổi)</label>
                <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu mới (ít nhất 8 ký tự)">
            </div>

        </div>

        <!-- Cột phải: Thuộc tính & Ảnh -->
        <div class="right-col">
            <div class="form-card">
                <h2 class="card-title">Phân quyền</h2>
                <div class="form-group">
                    <label>Vai trò</label>
                    <select name="role" class="form-control" required>
                        <option value="">Chọn vai trò...</option>
                        @foreach($roles as $key => $label)
                            <option value="{{ $key }}" {{ old('role', $admin->role) == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-card">
                <h2 class="card-title">Hiển thị</h2>
                <div class="form-group">
                    <label>Trạng thái</label>
                    <div class="radio-group">
                        <label class="radio-item">
                            <input type="radio" name="status" value="1" {{ old('status', $admin->status) == '1' ? 'checked' : '' }}> Hoạt động
                        </label>
                        <label class="radio-item">
                            <input type="radio" name="status" value="2" {{ old('status', $admin->status) == '2' ? 'checked' : '' }}> Chờ xác nhận
                        </label>
                        <label class="radio-item">
                            <input type="radio" name="status" value="0" {{ old('status', $admin->status) == '0' ? 'checked' : '' }}> Đã khóa
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
