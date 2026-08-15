<style>
    .register-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: rgba(0,0,0,0.4);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        z-index: 2000;
        display: none;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s;
    }
    .register-overlay.active {
        display: flex;
        opacity: 1;
    }

    .register-modal {
        background-color: #eaeaea;
        width: 100%;
        max-width: 440px;
        border-radius: 8px;
        padding: 25px 30px;
        position: relative;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        font-family: 'Alata', sans-serif;
        color: #333;
        transform: scale(0.95);
        transition: transform 0.3s;
        max-height: 90vh;
        overflow-y: auto;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .register-modal::-webkit-scrollbar {
        display: none;
    }
    .register-overlay.active .register-modal {
        transform: scale(1);
    }

    .register-modal .close-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        cursor: pointer;
        color: #333;
    }
    .register-modal .close-btn svg {
        width: 20px;
        height: 20px;
    }

    .register-modal-title {
        font-family: 'Castoro', serif;
        font-size: 20px;
        text-align: center;
        color: #333;
        margin-bottom: 10px;
    }

    .register-modal-subtitle {
        text-align: center;
        font-size: 12px;
        color: #555;
        margin-bottom: 20px;
        line-height: 1.4;
    }

    .register-form-box {
        border: 1px solid #aaa;
        border-radius: 6px;
        padding: 15px 20px;
        margin-bottom: 20px;
        background-color: transparent;
    }

    .register-input-group {
        margin-bottom: 15px;
    }

    .register-input-group input {
        width: 100%;
        border: none;
        border-bottom: 1px solid #666;
        background: transparent;
        padding: 10px 0;
        font-size: 14px;
        color: #333;
        outline: none;
        font-family: 'Alata', sans-serif;
    }
    .register-input-group input::placeholder {
        color: #666;
    }
    .register-input-group input:focus {
        border-bottom-color: #000;
    }
    
    .register-checkbox-group {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 15px;
        font-size: 12px;
        color: #555;
    }
    .register-checkbox-group input {
        margin: 0;
        cursor: pointer;
    }
    .register-checkbox-group label {
        cursor: pointer;
        user-select: none;
    }
    
    .register-input-group input:-webkit-autofill,
    .register-input-group input:-webkit-autofill:hover, 
    .register-input-group input:-webkit-autofill:focus, 
    .register-input-group input:-webkit-autofill:active{
        -webkit-box-shadow: 0 0 0 30px #eaeaea inset !important;
        -webkit-text-fill-color: #333 !important;
        transition: background-color 5000s ease-in-out 0s;
    }

    .register-btn-submit {
        width: 100%;
        background-color: #333;
        color: #fff;
        border: none;
        padding: 10px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        border-radius: 4px;
        margin-top: 5px;
        transition: background 0.2s;
    }
    .register-btn-submit:hover {
        background-color: #000;
    }

    .register-btn-login {
        display: block;
        text-align: center;
        margin-top: 10px;
        font-size: 12px;
        color: #666;
    }
    
    .register-btn-login a {
        color: #333;
        text-decoration: none;
    }
    .register-btn-login a:hover {
        text-decoration: underline;
    }

    .register-divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 15px 0;
        color: #666;
        font-size: 12px;
    }
    .register-divider::before, .register-divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #aaa;
    }
    .register-divider::before {
        margin-right: 15px;
    }
    .register-divider::after {
        margin-left: 15px;
    }

    .register-social {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
    }

    .register-btn-social {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: #fff;
        border: none;
        padding: 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        color: #333;
    }

    .register-member-banner {
        background: #fff;
        padding: 15px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .register-member-banner img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
    }
    .register-member-info h4 {
        font-size: 12px;
        margin-bottom: 3px;
        color: #333;
    }
    .register-member-info p {
        font-size: 10px;
        color: #666;
        line-height: 1.3;
    }

    .register-error {
        color: #dc2626;
        font-size: 12px;
        margin-top: 5px;
    }
</style>

<div class="register-overlay" id="registerModalOverlay">
    <div class="register-modal" onclick="event.stopPropagation()">
        <div class="close-btn" title="Đóng" onclick="toggleRegisterModal()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>

        <h2 class="register-modal-title">Tạo tài khoản mới</h2>
        <p class="register-modal-subtitle">Nhập thông tin của bạn để tạo tài khoản và tham gia Orlis.</p>

        <form method="POST" action="{{ route('register.post') }}">
            @csrf
            <div class="register-form-box">
                <div class="register-input-group">
                    <input type="text" name="name" placeholder="Họ và tên" value="{{ old('name') }}" required>
                    @error('name') <div class="register-error">{{ $message }}</div> @enderror
                </div>
                
                <div class="register-input-group">
                    <input type="email" name="email" placeholder="Địa chỉ email" value="{{ old('email') }}" required>
                    @error('email') <div class="register-error">{{ $message }}</div> @enderror
                </div>
                
                <div class="register-input-group">
                    <input type="password" name="password" placeholder=".........." required>
                    @error('password') <div class="register-error">{{ $message }}</div> @enderror
                </div>
                
                <div class="register-input-group">
                    <input type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu" required>
                </div>

                <div class="register-checkbox-group">
                    <input type="checkbox" name="terms" id="agreeTerms" {{ old('terms') ? 'checked' : '' }} required>
                    <label for="agreeTerms">Đồng ý với Điều khoản & Chính sách</label>
                </div>
                @error('terms') <div class="register-error" style="margin-top: -10px; margin-bottom: 15px;">{{ $message }}</div> @enderror

                <button type="submit" class="register-btn-submit">Tạo tài khoản</button>
                <div class="register-btn-login">
                    Đã có tài khoản? <a href="#" onclick="switchToLoginModal(event)">Đăng nhập</a>
                </div>
            </div>
        </form>

        <div class="register-divider">hoặc tiếp tục với</div>

        <div class="register-social">
            <a href="{{ route('social.redirect', ['provider' => 'google']) }}" class="register-btn-social" style="text-decoration: none;">
                <svg width="16" height="16" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                Google
            </a>
            <a href="{{ route('social.redirect', ['provider' => 'facebook']) }}" class="register-btn-social" style="text-decoration: none;">
                <svg width="16" height="16" viewBox="0 0 24 24"><path fill="#1877F2" d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                Facebook
            </a>
        </div>

        <div class="register-member-banner">
            <img src="https://images.unsplash.com/photo-1549465220-1a8b9238cd48?auto=format&fit=crop&w=200&q=80" alt="Gifts">
            <div class="register-member-info">
                <h4>Đặc quyền dành riêng cho Hội viên</h4>
                <p>Nâng tầm trải nghiệm với những ưu đãi thượng hạng. Đặc biệt, món quà bất ngờ từ Orlis đang chờ đón bạn ngay khi sở hữu sản phẩm thứ hai.</p>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleRegisterModal(e) {
        if (e) e.preventDefault();
        var overlay = document.getElementById('registerModalOverlay');
        if (overlay.style.display === 'flex') {
            overlay.classList.remove('active');
            document.body.style.overflow = '';
            setTimeout(() => { 
                overlay.style.display = 'none'; 
                var mainModal = document.querySelector('.modal');
                if (mainModal) mainModal.style.display = 'block';
            }, 300);
        } else {
            overlay.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            setTimeout(() => { overlay.classList.add('active'); }, 10);
        }
    }
    
    function switchToLoginModal(e) {
        if (e) e.preventDefault();
        var registerOverlay = document.getElementById('registerModalOverlay');
        registerOverlay.classList.remove('active');
        setTimeout(() => { 
            registerOverlay.style.display = 'none'; 
            if(typeof toggleLoginModal === 'function') {
                toggleLoginModal();
            } else {
                var mainModal = document.querySelector('.modal');
                if (mainModal) mainModal.style.display = 'block';
            }
        }, 300);
    }
    
    @if($errors->has('name') || ($errors->has('password') && !$errors->has('email')))
    // Auto-open modal if there are validation errors on registration after submit
    // Note: login doesn't have 'name'. 
    document.addEventListener('DOMContentLoaded', function() {
        var overlay = document.getElementById('registerModalOverlay');
        overlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        setTimeout(() => { overlay.classList.add('active'); }, 10);
    });
    @endif
</script>
