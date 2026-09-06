<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập {{ $roleLabel }} - Orlis</title>
    @vite(['resources/css/client.css'])
</head>
<body>
    <div class="login-page">
        <div class="login-container">
            
            <h2 class="title">{{ __('messages.login_role_title', ['role' => $roleLabel]) }}</h2>
            <p class="subtitle">{{ __('messages.login_role_subtitle') }}</p>

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
                        <label for="password">{{ __('messages.password_ph') }}</label>
                        <input type="password" id="password" name="password" required placeholder="••••••••" style="padding-right: 32px;">
                        <button type="button" onclick="togglePassword()" style="position: absolute; right: 0; bottom: 10px; background: none; border: none; cursor: pointer; color: #52525b; padding: 0; display: flex;">
                            <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </button>
                        @error('password')
                            <div class="error-message" style="position: absolute; bottom: -20px; left: 0;">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-submit">{{ __('messages.login') }}</button>
                </div>
            </form>

            <div class="security-notice">
                <div class="security-icon"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg></div>
                <div class="security-text">
                    <h4>{{ __('messages.internal_system') }}</h4>
                    <p>{{ __('messages.internal_warning') }}</p>
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
