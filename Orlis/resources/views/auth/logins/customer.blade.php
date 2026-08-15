<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập hoặc tạo tài khoản - Orlis</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Inter:wght@300;400;500;600&family=Castoro:ital,wght@0,400;1,400&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-image: url('https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            background-color: rgba(0,0,0,0.3); /* dark overlay */
        }

        .modal {
            background-color: #eaeaea;
            width: 100%;
            max-width: 500px;
            border-radius: 8px;
            padding: 40px;
            position: relative;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            cursor: pointer;
            color: #333;
        }
        .close-btn svg {
            width: 20px;
            height: 20px;
        }

        .modal-title {
            font-family: 'Castoro', serif;
            font-size: 24px;
            text-align: center;
            color: #333;
            margin-bottom: 15px;
        }

        .modal-subtitle {
            text-align: center;
            font-size: 13px;
            color: #555;
            margin-bottom: 30px;
            line-height: 1.5;
        }

        .form-box {
            border: 1px solid #aaa;
            border-radius: 6px;
            padding: 25px 30px;
            margin-bottom: 30px;
            background-color: transparent;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group input {
            width: 100%;
            border: none;
            border-bottom: 1px solid #666;
            background: transparent;
            padding: 10px 0;
            font-size: 14px;
            color: #333;
            outline: none;
            font-family: 'Inter', sans-serif;
        }
        .input-group input::placeholder {
            color: #666;
        }
        .input-group input:focus {
            border-bottom-color: #000;
        }

        .btn-submit {
            width: 100%;
            background-color: #333;
            color: #fff;
            border: none;
            padding: 14px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border-radius: 4px;
            margin-top: 10px;
            transition: background 0.2s;
        }
        .btn-submit:hover {
            background-color: #000;
        }

        .btn-create {
            display: block;
            text-align: center;
            margin-top: 15px;
            font-size: 13px;
            color: #333;
            text-decoration: none;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 25px 0;
            color: #666;
            font-size: 13px;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #aaa;
        }
        .divider::before {
            margin-right: 15px;
        }
        .divider::after {
            margin-left: 15px;
        }

        .social-login {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }

        .btn-social {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #fff;
            border: none;
            padding: 12px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            color: #333;
        }

        .member-banner {
            background: #fff;
            padding: 20px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .member-banner img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
        }
        .member-info h4 {
            font-size: 13px;
            margin-bottom: 5px;
            color: #333;
        }
        .member-info p {
            font-size: 11px;
            color: #666;
            line-height: 1.4;
        }

        .error {
            color: #dc2626;
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
</head>
<body>

    <div class="modal">
        <a href="/" class="close-btn" title="Đóng">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </a>

        <h2 class="modal-title">Đăng nhập hoặc tạo tài khoản</h2>
        <p class="modal-subtitle">Nhập địa chỉ email của bạn để đăng nhập hoặc tạo tài khoản và tham gia Orlis.</p>

        <form method="POST" action="{{ route('role.login.post', ['role' => $role]) }}">
            @csrf
            <div class="form-box">
                <div class="input-group">
                    <input type="email" name="email" placeholder="*E-mail" value="{{ old('email') }}" required>
                    @error('email') <div class="error">{{ $message }}</div> @enderror
                </div>
                
                <div class="input-group">
                    <input type="password" name="password" placeholder="*Mật khẩu" required>
                    @error('password') <div class="error">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn-submit">Đăng nhập</button>
                <a href="#" onclick="document.getElementById('registerModalOverlay').style.display='flex'; document.querySelector('.modal').style.display='none'; return false;" class="btn-create">Tạo tài khoản</a>
            </div>
        </form>

        <div class="divider">hoặc tiếp tục với</div>

        <div class="social-login">
            <button class="btn-social">
                <svg width="16" height="16" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                Google
            </button>
            <button class="btn-social">
                <svg width="16" height="16" viewBox="0 0 24 24"><path fill="#1877F2" d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                Facebook
            </button>
        </div>

        <div class="member-banner">
            <img src="https://images.unsplash.com/photo-1549465220-1a8b9238cd48?auto=format&fit=crop&w=200&q=80" alt="Gifts">
            <div class="member-info">
                <h4>Đặc quyền dành riêng cho Hội viên</h4>
                <p>Nâng tầm trải nghiệm với những ưu đãi thượng hạng. Đặc biệt, món quà bất ngờ từ Orlis Beauty đang chờ đón bạn ngay khi sở hữu sản phẩm thứ hai.</p>
            </div>
        </div>

    </div>

    @include('components.register-modal')

</body>
</html>
