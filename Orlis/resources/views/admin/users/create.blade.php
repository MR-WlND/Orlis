@extends('layouts.admin')

@section('title', 'Thêm mới Khách hàng')

@section('page-style')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
    }
    .page-title {
        font-family: var(--font-serif);
        font-size: 32px;
        color: var(--text-primary);
        font-weight: 500;
        margin: 0;
    }
    .header-actions {
        display: flex;
        gap: 15px;
    }

    .btn-cancel {
        height: 42px;
        padding: 0 40px;
        min-width: 100px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        background: transparent;
        color: var(--text-primary);
        border: 1px solid var(--text-primary);
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-sizing: border-box;
        font-family: inherit;
    }
    .btn-cancel:hover { background: #f5f5f5; }

    .btn-submit {
        height: 42px;
        padding: 0 40px;
        min-width: 200px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        background: var(--text-primary);
        color: #fff;
        border: 1px solid var(--text-primary);
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-sizing: border-box;
        font-family: inherit;
    }
    .btn-submit:hover { background: #333; }

    .edit-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
        align-items: start;
    }

    .form-card {
        background: #fff;
        padding: 30px 40px;
        border: 1px solid var(--border-color);
    }
    .card-title {
        font-family: var(--font-serif);
        font-size: 20px;
        color: var(--text-primary);
        margin-bottom: 30px;
        font-weight: 500;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border-color);
    }

    .form-group { margin-bottom: 30px; }
    .form-group label {
        display: block;
        font-size: 9px;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 12px;
    }

    .form-control {
        width: 100%;
        padding: 10px 0;
        border: none;
        border-bottom: 1px solid var(--border-color);
        font-family: var(--font-sans);
        font-size: 14px;
        color: var(--text-primary);
        background: transparent;
        transition: border-color 0.2s;
        outline: none;
    }
    .form-control:focus { border-bottom-color: var(--text-primary); }

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23000%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E");
        background-repeat: no-repeat;
        background-position: right 0px top 50%;
        background-size: 10px auto;
        cursor: pointer;
    }

    .right-col {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .radio-group {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    .radio-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        color: var(--text-primary);
        cursor: pointer;
    }

    .radio-item input {
        accent-color: var(--text-primary);
    }
</style>
@endsection

@section('content')
<form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="page-header">
        <h1 class="page-title">Thêm Khách hàng</h1>
        <div class="header-actions">
            <a href="{{ route('admin.users.index') }}" class="btn-cancel">Hủy</a>
            <button type="submit" class="btn-submit">Lưu khách hàng</button>
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
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            
            <div class="form-group">
                <label>Số điện thoại</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
            </div>
            
            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu (ít nhất 8 ký tự)" required>
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
                            <option value="{{ $key }}" {{ old('role') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Hạng thành viên</label>
                    <select name="membership_level" class="form-control">
                        <option value="">Chọn hạng...</option>
                        @foreach(App\Models\User::MEMBERSHIPS as $key => $label)
                            <option value="{{ $key }}" {{ old('membership_level') == $key ? 'selected' : '' }}>
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
                            <input type="radio" name="status" value="1" {{ old('status', '1') == '1' ? 'checked' : '' }}> Hoạt động
                        </label>
                        <label class="radio-item">
                            <input type="radio" name="status" value="0" {{ old('status') == '0' ? 'checked' : '' }}> Đã khóa
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
