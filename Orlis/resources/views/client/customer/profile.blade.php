@extends('layouts.client')
@section('title', 'Hồ sơ cá nhân - Orlis')
@section('styles')
<style>
    .customer-wrap { max-width: 1060px; margin: 0 auto; padding: 100px 20px 60px; display: grid; grid-template-columns: 220px 1fr; gap: 32px; }
    .sidebar-nav { position: sticky; top: 90px; height: fit-content; }
    .sidebar-nav .user-info { padding-bottom: 20px; border-bottom: 1px solid #e8e8e8; margin-bottom: 16px; }
    .avatar-circle { width: 56px; height: 56px; border-radius: 50%; background: #f0ece6; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 20px; color: var(--primary); margin-bottom: 10px; overflow: hidden; }
    .user-name { font-weight: 600; font-size: 15px; margin-bottom: 2px; }
    .user-level { font-size: 12px; color: #999; }
    .nav-link { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 6px; text-decoration: none; color: #555; font-size: 14px; transition: all 0.15s; margin-bottom: 2px; }
    .nav-link:hover, .nav-link.active { background: #f5f0ea; color: var(--primary); font-weight: 500; }
    .nav-link svg { width: 16px; height: 16px; stroke: currentColor; fill: none; stroke-width: 2; flex-shrink: 0; }
    .card { background: white; border-radius: 10px; border: 1px solid #efefef; padding: 28px; margin-bottom: 20px; }
    .card-title { font-family: var(--font-serif); font-size: 17px; font-weight: 500; margin-bottom: 22px; }
    .form-group { margin-bottom: 16px; }
    .form-label { display: block; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #888; margin-bottom: 6px; }
    .form-input { width: 100%; padding: 11px 14px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 14px; color: #333; box-sizing: border-box; transition: border-color 0.2s; }
    .form-input:focus { border-color: var(--primary); outline: none; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .avatar-upload { display: flex; gap: 20px; align-items: center; margin-bottom: 24px; }
    .avatar-preview { width: 80px; height: 80px; border-radius: 50%; background: #f0ece6; display: flex; align-items: center; justify-content: center; font-size: 28px; font-weight: 700; color: var(--primary); overflow: hidden; flex-shrink: 0; }
    .avatar-preview img { width: 100%; height: 100%; object-fit: cover; }
    .btn-upload { padding: 9px 18px; border: 1px solid #d0d0d0; background: white; border-radius: 4px; font-size: 13px; cursor: pointer; }
    .btn-save { padding: 12px 32px; background: #333; color: white; border: none; border-radius: 4px; font-size: 14px; font-weight: 600; cursor: pointer; transition: background 0.2s; }
    .btn-save:hover { background: #111; }
    .error-msg { color: #c0392b; font-size: 12px; margin-top: 4px; }
    @media(max-width: 768px) { .customer-wrap { grid-template-columns: 1fr; } .sidebar-nav { position: static; } .form-row { grid-template-columns: 1fr; } }
</style>
@endsection
@section('content')
<div style="background: #f8f7f5; min-height: 100vh;">
<div class="customer-wrap">
    @include('client.customer._sidebar')
    <div>
        @if(session('success'))
            <div style="background:#d4edda;color:#155724;padding:12px 16px;border-radius:6px;margin-bottom:20px;font-size:13px;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div style="background:#f8d7da;color:#721c24;padding:12px 16px;border-radius:6px;margin-bottom:20px;font-size:13px;">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('customer.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            {{-- Thông tin cơ bản --}}
            <div class="card">
                <div class="card-title">Thông tin cá nhân</div>

                <div class="avatar-upload">
                    <div class="avatar-preview" id="avatar-preview">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" id="avatar-img" alt="Avatar">
                        @else
                            <span id="avatar-initials">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                        @endif
                    </div>
                    <div>
                        <label class="btn-upload" style="cursor:pointer;">
                            Thay ảnh đại diện
                            <input type="file" name="avatar" accept="image/*" style="display:none;" onchange="previewAvatar(this)">
                        </label>
                        <div style="font-size:12px;color:#aaa;margin-top:6px;">JPEG, PNG, WebP – tối đa 2MB</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Họ và tên *</label>
                        <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="error-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}" placeholder="0912 345 678">
                        @error('phone')<div class="error-msg">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-input" value="{{ $user->email }}" disabled style="opacity:0.6;">
                    <div style="font-size:12px;color:#aaa;margin-top:4px;">Email không thể thay đổi.</div>
                </div>
            </div>

            {{-- Đổi mật khẩu --}}
            <div class="card">
                <div class="card-title">Đổi mật khẩu</div>
                <div class="form-group">
                    <label class="form-label">Mật khẩu hiện tại</label>
                    <input type="password" name="current_password" class="form-input" placeholder="Nhập mật khẩu hiện tại để xác nhận">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Mật khẩu mới</label>
                        <input type="password" name="new_password" class="form-input" placeholder="Tối thiểu 8 ký tự">
                        @error('new_password')<div class="error-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Xác nhận mật khẩu mới</label>
                        <input type="password" name="new_password_confirmation" class="form-input" placeholder="Nhập lại mật khẩu mới">
                    </div>
                </div>
                <p style="font-size:12px;color:#aaa;">Bỏ trống nếu bạn không muốn thay đổi mật khẩu.</p>
            </div>

            <button type="submit" class="btn-save">Lưu thay đổi</button>
        </form>
    </div>
</div>
</div>

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
