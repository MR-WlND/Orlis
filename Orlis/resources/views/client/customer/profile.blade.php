@extends('layouts.customer')
@section('customer_title', __('messages.profile') . ' - Orlis')
@section('customer_styles')
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
