@extends('layouts.customer')
@section('customer_title', __('messages.profile') . ' - Orlis')
@section('customer_styles')
<style>
    .card { background: white; border: 1px solid #eee; padding: 35px 40px; margin-bottom: 25px; transition: 0.3s; }
    .card:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.02); border-color: #e5e5e5; }
    .card-title { font-family: var(--font-serif); font-size: 18px; font-weight: 400; color: #111; margin-bottom: 25px; border-bottom: 1px solid #f9f9f9; padding-bottom: 15px; }
    
    .form-group { margin-bottom: 20px; }
    .form-label { display: block; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: #888; margin-bottom: 8px; }
    .form-input { width: 100%; padding: 12px 15px; border: 1px solid #e0e0e0; background: #fbfbfb; font-size: 14px; color: #333; box-sizing: border-box; transition: 0.3s; }
    .form-input:focus { border-color: #111; background: white; outline: none; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    
    .avatar-upload { display: flex; gap: 25px; align-items: center; margin-bottom: 30px; }
    .avatar-preview { width: 80px; height: 80px; border-radius: 50%; background: #111; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 600; color: white; overflow: hidden; flex-shrink: 0; border: 2px solid #eee; }
    .avatar-preview img { width: 100%; height: 100%; object-fit: cover; }
    .btn-upload { display: inline-block; padding: 10px 20px; border: 1px solid #ddd; background: transparent; color: #111; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; cursor: pointer; transition: 0.3s; }
    .btn-upload:hover { border-color: #111; background: #111; color: white; }
    
    .btn-save { padding: 14px 35px; background: #111; color: white; border: none; font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 600; cursor: pointer; transition: 0.3s; display: block; width: fit-content; margin-top: 30px; }
    .btn-save:hover { background: #333; }
    
    .error-msg { color: #c0392b; font-size: 11px; margin-top: 6px; letter-spacing: 0.5px; }
    
    @media(max-width: 768px) { .form-row { grid-template-columns: 1fr; } .avatar-upload { flex-direction: column; text-align: center; } }
</style>
@endsection
@section('customer_content')
<div class="section-header">
    <div>
        <div class="subtitle">THÔNG TIN CÁ NHÂN & THẺ</div>
        <h2 class="section-title">Hồ sơ của tôi</h2>
    </div>
</div>

<form method="POST" action="{{ route('customer.profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    {{-- Thông tin cơ bản --}}
    <div class="card">
        <div class="card-title">{{ __('messages.personal_info') }}</div>

        <div class="avatar-upload">
            <div class="avatar-preview" id="avatar-preview">
                @if($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}" id="avatar-img" alt="Avatar">
                @else
                    <span id="avatar-initials">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                @endif
            </div>
            <div>
                <label class="btn-upload">
                    {{ __('messages.change_avatar') }}
                    <input type="file" name="avatar" accept="image/*" style="display:none;" onchange="previewAvatar(this)">
                </label>
                <div style="font-size:11px;color:#aaa;margin-top:8px;">{{ __('messages.avatar_notice') }}</div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">{{ __('messages.fullname') }} *</label>
                <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                @error('name')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('messages.phone_number') }}</label>
                <input type="text" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}" placeholder="0912 345 678">
                @error('phone')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" class="form-input" value="{{ $user->email }}" disabled style="opacity:0.6; cursor:not-allowed;">
            <div style="font-size:11px;color:#aaa;margin-top:6px;">{{ __('messages.email_cannot_change') }}</div>
        </div>
    </div>

    {{-- Đổi mật khẩu --}}
    <div class="card">
        <div class="card-title">{{ __('messages.change_password') }}</div>
        <div class="form-group">
            <label class="form-label">{{ __('messages.current_password') }}</label>
            <input type="password" name="current_password" class="form-input" placeholder="{{ __('messages.current_password_ph') }}">
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">{{ __('messages.new_password') }}</label>
                <input type="password" name="new_password" class="form-input" placeholder="{{ __('messages.new_password_ph') }}">
                @error('new_password')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('messages.confirm_new_password') }}</label>
                <input type="password" name="new_password_confirmation" class="form-input" placeholder="{{ __('messages.confirm_new_password_ph') }}">
            </div>
        </div>
        <p style="font-size:11px;color:#aaa;margin-top:10px;">{{ __('messages.leave_blank_password') }}</p>
    </div>

    <button type="submit" class="btn-save">{{ __('messages.save_changes') }}</button>
</form>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('avatar-preview');
            preview.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
