@extends('layouts.admin')

@section('title', 'Hồ Sơ Cá Nhân')

@section('content')
    <div class="page-header">
        <div class="header-text">
            <h2 class="page-title">Hồ Sơ Cá Nhân</h2>
            <p class="page-subtitle">Cập nhật thông tin tài khoản quản trị của bạn.</p>
        </div>
    </div>

    @if(session('success'))
        <div style="padding: 15px 20px; background: #fff; border: 1px solid #000; color: #000; margin-bottom: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="edit-grid">
            <!-- Cột trái: Nội dung chính -->
            <div class="form-card" style="padding: 30px 40px;">
                <h3 style="font-family: var(--font-sans); font-size: 14px; font-weight: 600; margin: 0 0 20px 0; padding-bottom: 15px; border-bottom: 1px solid #eee;">THÔNG TIN CƠ BẢN</h3>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label>Họ và Tên *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $admin->name) }}" required>
                    @error('name') <span class="text-danger" style="color: #d93025; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label>Email đăng nhập *</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $admin->email) }}" required>
                    @error('email') <span class="text-danger" style="color: #d93025; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span> @enderror
                </div>
                
                <h3 style="font-family: var(--font-sans); font-size: 14px; font-weight: 600; margin: 30px 0 20px 0; padding-bottom: 15px; border-bottom: 1px solid #eee;">ĐỔI MẬT KHẨU (BỎ TRỐNG NẾU GIỮ NGUYÊN)</h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Mật khẩu mới</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" autocomplete="new-password">
                        @error('password') <span class="text-danger" style="color: #d93025; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Xác nhận mật khẩu</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" autocomplete="new-password">
                    </div>
                </div>
                
                <div style="margin-top: 30px;">
                    <button type="submit" class="btn-submit">CẬP NHẬT HỒ SƠ</button>
                </div>
            </div>

            <!-- Cột phải: Avatar -->
            <div class="right-col">
                <div class="form-card" style="padding: 24px;">
                    <h3 style="font-family: var(--font-sans); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #000; margin: 0 0 16px 0; padding-bottom: 12px; border-bottom: 1px solid #eee;">ẢNH ĐẠI DIỆN</h3>
                    
                    <div style="text-align: center; margin-bottom: 20px;">
                        <img src="{{ $admin->avatar ? Storage::url($admin->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($admin->name).'&background=random' }}" alt="Avatar" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 1px solid #eee;">
                    </div>
                    
                    <div class="form-group">
                        <label style="display: block; font-size: 10px; font-weight: 600; text-transform: uppercase; color: #888; margin-bottom: 6px;">TẢI ẢNH LÊN (TỐI ĐA 2MB)</label>
                        <input type="file" name="avatar" class="form-control" accept="image/*">
                        @error('avatar') <span class="text-danger" style="color: #d93025; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="form-card" style="padding: 24px;">
                    <h3 style="font-family: var(--font-sans); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #000; margin: 0 0 16px 0; padding-bottom: 12px; border-bottom: 1px solid #eee;">THÔNG TIN THÊM</h3>
                    
                    <div style="display: flex; flex-direction: column; gap: 15px; font-size: 13px;">
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #888;">Chức vụ:</span>
                            <span style="color: #111; font-weight: 600; text-transform: uppercase;">{{ $admin->role ?? 'Quản trị viên' }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #888;">Ngày tạo tài khoản:</span>
                            <span style="color: #111;">{{ $admin->created_at ? $admin->created_at->format('d/m/Y') : 'Không rõ' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
