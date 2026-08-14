@extends('layouts.admin')

@section('title', 'Chỉnh sửa Tài Khoản')

@section('page-style')
<style>
    .form-container {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        max-width: 800px;
        margin: 0 auto;
    }

    .form-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 13px;
        color: var(--text-muted);
        transition: color 0.2s;
    }
    
    .btn-back:hover { color: var(--accent); }

    .form-body {
        padding: 30px 40px;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--text-main);
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        font-family: var(--font-sans);
        font-size: 14px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        background-color: #fafafa;
        transition: all 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--text-muted);
        background-color: #fff;
    }

    .form-control.is-invalid {
        border-color: #ff4d4f;
    }

    .invalid-feedback {
        color: #ff4d4f;
        font-size: 12px;
        margin-top: 5px;
        display: block;
    }

    .form-footer {
        padding: 20px 40px;
        background-color: #fafafa;
        border-top: 1px solid var(--border-color);
        border-radius: 0 0 8px 8px;
        display: flex;
        justify-content: flex-end;
        gap: 15px;
    }

    .btn {
        padding: 10px 24px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        font-family: var(--font-sans);
    }

    .btn-primary { background-color: var(--accent); color: white; }
    .btn-primary:hover { background-color: #333; }
    
    .btn-outline { background-color: transparent; border: 1px solid var(--border-color); color: var(--text-main); }
    .btn-outline:hover { background-color: #f5f5f5; }
</style>
@endsection

@section('content')

<div class="form-container">
    <div class="form-header">
        <h2 style="font-family: var(--font-serif); font-size: 18px; font-weight: 600;">Chỉnh sửa Tài Khoản</h2>
        <a href="{{ route('admin.users.index') }}" class="btn-back">
            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" fill="none" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
            Trở về
        </a>
    </div>

    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-body">
            
            <div class="form-group">
                <label class="form-label">Họ và Tên</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}">
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Địa chỉ Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}">
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Mật khẩu</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Để trống nếu không muốn đổi mật khẩu">
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Chức vụ (Phân quyền)</label>
                <select name="role" class="form-control @error('role') is-invalid @enderror">
                    @foreach($roles as $key => $label)
                        <option value="{{ $key }}" {{ old('role', $user->role) == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <p style="font-size: 12px; color: var(--text-muted); margin-top: 5px;">Chức vụ này quyết định quyền hạn của tài khoản trong hệ thống.</p>
                @error('role')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

        </div>
        
        <div class="form-footer">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Hủy</a>
            <button type="submit" class="btn btn-primary">Lưu Thay Đổi</button>
        </div>
    </form>
</div>

@endsection
