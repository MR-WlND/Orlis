<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập {{ $roleLabel }} - Orlis</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Castoro:ital,wght@0,400;1,400&family=Inter:wght@300;400;500;600;700&display=swap');

        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Inter', sans-serif;
        }
        
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url('https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .login-page::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .login-container {
            position: relative;
            background: #ffffff;
            width: 100%;
            max-width: 340px;
            padding: 32px 28px;
            border-radius: 12px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            z-index: 10;
        }

        .title {
            text-align: center;
            font-family: 'Castoro', serif;
            font-size: 22px;
            font-weight: 400;
            margin-top: 0;
            margin-bottom: 6px;
            color: #000;
        }

        .subtitle {
            text-align: center;
            font-size: 12px;
            color: #52525b;
            margin-bottom: 24px;
            line-height: 1.5;
        }

        .form-wrapper {
            background-color: #f4f4f5;
            padding: 16px;
            border-radius: 8px;
            border: 1px solid #e4e4e7;
        }

        .input-group {
            position: relative;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e4e4e7;
        }

        .input-group label {
            display: block;
            font-size: 12px;
            color: #52525b;
            margin-bottom: 8px;
        }

        .input-group input {
            width: 100%;
            padding: 0;
            background: transparent;
            border: none;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            color: #18181b;
            outline: none;
        }

        /* Khắc phục lỗi nền xanh của trình duyệt khi tự động điền mật khẩu */
        .input-group input:-webkit-autofill,
        .input-group input:-webkit-autofill:hover, 
        .input-group input:-webkit-autofill:focus, 
        .input-group input:-webkit-autofill:active{
            -webkit-box-shadow: 0 0 0 30px #f4f4f5 inset !important;
            -webkit-text-fill-color: #18181b !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background-color: #000;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: bold;
            font-family: 'Inter', sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 8px;
        }

        .btn-submit:hover {
            background-color: #333;
        }

        .error-message {
            color: #dc2626;
            font-size: 12px;
            margin-top: 4px;
        }

        .security-notice {
            margin-top: 24px;
            padding: 12px;
            border-radius: 8px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            border: 1px solid #e4e4e7;
            background: #fff;
        }

        .security-icon {
            width: 48px;
            height: 48px;
            border-radius: 6px;
            background: #f4f4f5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .security-text h4 {
            margin: 0 0 6px 0;
            font-size: 13px;
            color: #18181b;
            font-weight: 600;
        }

        .security-text p {
            margin: 0;
            font-size: 12px;
            color: #52525b;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="login-page">
        <div class="login-container">
            
            <h2 class="title">Đăng nhập {{ $roleLabel }}</h2>
            <p class="subtitle">Nhập địa chỉ email của bạn để đăng nhập hệ thống quản trị nội bộ Orlis.</p>

            <form method="POST" action="{{ route('role.login.post', ['role' => $role]) }}">
                @csrf
                <div class="form-wrapper">
                    <div class="input-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@example.com">
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label for="password">Mật khẩu</label>
                        <input type="password" id="password" name="password" required placeholder="••••••••" style="padding-right: 32px;">
                        <button type="button" onclick="togglePassword()" style="position: absolute; right: 0; bottom: 10px; background: none; border: none; cursor: pointer; color: #52525b; padding: 0; display: flex;">
                            <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </button>
                        @error('password')
                            <div class="error-message" style="position: absolute; bottom: -20px; left: 0;">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-submit">Đăng nhập</button>
                </div>
            </form>

            <div class="security-notice">
                <div class="security-icon">🛡️</div>
                <div class="security-text">
                    <h4>Hệ thống quản trị nội bộ</h4>
                    <p>Mọi hành vi truy cập trái phép vào hệ thống này đều bị nghiêm cấm và bị ghi log địa chỉ IP.</p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function togglePassword() {
            const passInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                eyeIcon.innerHTML = '<path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path><line x1="2" y1="2" x2="22" y2="22"></line>';
            } else {
                passInput.type = 'password';
                eyeIcon.innerHTML = '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle>';
            }
        }
    </script>
</body>
</html>
