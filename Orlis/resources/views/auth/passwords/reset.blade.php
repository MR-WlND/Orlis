@extends('layouts.client')
@section('title', 'Đặt Lại Mật Khẩu')
@section('content')
<div style="min-height: 80vh; display: flex; align-items: center; justify-content: center; background-color: #f9f9f9;">
    <div style="background: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 100%; max-width: 400px;">
        <h2 style="font-family: var(--font-serif); font-size: 24px; text-align: center; margin-bottom: 20px;">Đặt Lại Mật Khẩu</h2>
        
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 8px; font-size: 14px; color: #555;">Email:</label>
                <input type="email" name="email" value="{{ $email ?? old('email') }}" required readonly style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; background: #eee;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 8px; font-size: 14px; color: #555;">Mật khẩu mới:</label>
                <input type="password" name="password" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px;">
                @error('password')
                    <span style="color: #dc3545; font-size: 12px; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 8px; font-size: 14px; color: #555;">Xác nhận mật khẩu mới:</label>
                <input type="password" name="password_confirmation" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <button type="submit" style="width: 100%; padding: 14px; background: #333; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">Cập Nhật Mật Khẩu</button>
        </form>
    </div>
</div>
@endsection
