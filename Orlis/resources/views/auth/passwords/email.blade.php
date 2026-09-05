@extends('layouts.client')
@section('title', 'Quên Mật Khẩu')
@section('content')
<div style="min-height: 80vh; display: flex; align-items: center; justify-content: center; background-color: #f9f9f9;">
    <div style="background: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 100%; max-width: 400px;">
        <h2 style="font-family: var(--font-serif); font-size: 24px; text-align: center; margin-bottom: 20px;">Quên Mật Khẩu</h2>
        
        @if (session('status'))
            <div style="color: #155724; background-color: #d4edda; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-size: 14px; color: #555;">Email của bạn:</label>
                <input type="email" name="email" value="{{ old('email') }}" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px;" placeholder="Ví dụ: email@domain.com">
                @error('email')
                    <span style="color: #dc3545; font-size: 12px; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>
            
            <button type="submit" style="width: 100%; padding: 14px; background: #333; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">Gửi Link Khôi Phục</button>
        </form>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="{{ route('role.login', 'customer') }}" style="color: #666; font-size: 13px; text-decoration: underline;">Quay lại Đăng nhập</a>
        </div>
    </div>
</div>
@endsection
