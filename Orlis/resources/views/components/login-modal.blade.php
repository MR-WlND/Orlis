<style>
    .login-overlay {
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
    .login-overlay.active {
        display: flex;
        opacity: 1;
    }

    .login-modal {
        background-color: #eaeaea;
        width: 100%;
        max-width: 400px;
        border-radius: 8px;
        padding: 25px 30px;
        position: relative;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        font-family: var(--font-sans);
        color: #333;
        transform: scale(0.95);
        transition: transform 0.3s;
        max-height: 90vh;
        overflow-y: auto;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .login-modal::-webkit-scrollbar {
        display: none;
    }
    .login-overlay.active .login-modal {
        transform: scale(1);
    }

    .login-modal .close-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        cursor: pointer;
        color: #333;
    }
    .login-modal .close-btn svg {
        width: 20px;
        height: 20px;
    }

    .login-modal-title {
        font-family: var(--font-serif);
        font-size: 20px;
        text-align: center;
        color: #333;
        margin-bottom: 10px;
    }

    .login-modal-subtitle {
        text-align: center;
        font-size: 12px;
        color: #555;
        margin-bottom: 20px;
        line-height: 1.4;
    }

    .login-form-box {
        border: 1px solid #aaa;
        border-radius: 6px;
        padding: 15px 20px;
        margin-bottom: 20px;
        background-color: transparent;
    }

    .login-input-group {
        margin-bottom: 15px;
    }

    .login-input-group input {
        width: 100%;
        border: none;
        border-bottom: 1px solid #666;
        background: transparent;
        padding: 10px 0;
        font-size: 14px;
        color: #333;
        outline: none;
        font-family: var(--font-sans);
    }
    .login-input-group input::placeholder {
        color: #666;
    }
    .login-input-group input:focus {
        border-bottom-color: #000;
    }
    
    /* Override browser autofill background */
    .login-input-group input:-webkit-autofill,
    .login-input-group input:-webkit-autofill:hover, 
    .login-input-group input:-webkit-autofill:focus, 
    .login-input-group input:-webkit-autofill:active{
        -webkit-box-shadow: 0 0 0 30px #eaeaea inset !important;
        -webkit-text-fill-color: #333 !important;
        transition: background-color 5000s ease-in-out 0s;
    }

    .login-btn-submit {
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
    .login-btn-submit:hover {
        background-color: #000;
    }

    .login-btn-create {
        display: block;
        text-align: center;
        margin-top: 10px;
        font-size: 12px;
        color: #333;
        text-decoration: none;
    }

    .login-divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 15px 0;
        color: #666;
        font-size: 12px;
    }
    .login-divider::before, .login-divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #aaa;
    }
    .login-divider::before {
        margin-right: 15px;
    }
    .login-divider::after {
        margin-left: 15px;
    }

    .login-social {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
    }

    .login-btn-social {
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

    .login-member-banner {
        background: #fff;
        padding: 15px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .login-member-banner img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
    }
    .login-member-info h4 {
        font-size: 12px;
        margin-bottom: 3px;
        color: #333;
    }
    .login-member-info p {
        font-size: 10px;
        color: #666;
        line-height: 1.3;
    }

    .login-error {
        color: #dc2626;
        font-size: 12px;
        margin-top: 5px;
    }
</style>

<div class="login-overlay" id="loginModalOverlay">
    <div class="login-modal" onclick="event.stopPropagation()">
        <div class="close-btn" title="Đóng" onclick="toggleLoginModal()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>

        <h2 class="login-modal-title">{{ __('messages.login_or_create') }}</h2>
        <p class="login-modal-subtitle">{{ __('messages.login_subtitle') }}</p>

        <form method="POST" action="{{ route('role.login.post', ['role' => 'customer']) }}">
            @csrf
            <div class="login-form-box">
                <div class="login-input-group">
                    <input type="email" name="email" placeholder="{{ __('messages.email_ph') }}" value="{{ old('email') }}" required>
                    @error('email') <div class="login-error">{{ $message }}</div> @enderror
                </div>
                
                <div class="login-input-group" style="position: relative;">
                    <input type="password" id="loginPassword" name="password" placeholder="{{ __('messages.password_ph') }}" required style="padding-right: 35px;">
                    <span onclick="togglePasswordVisibility('loginPassword', this)" style="position: absolute; right: 0; top: 12px; cursor: pointer; color: #666;" title="Hiện/Ẩn mật khẩu">
                        <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="1.5" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </span>
                    @error('password') <div class="login-error">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="login-btn-submit">{{ __('messages.login') }}</button>
                <a href="#" onclick="switchToRegisterModal(event)" class="login-btn-create">{{ __('messages.create_account') }}</a>
            </div>
        </form>

        <div class="login-divider">{{ __('messages.or_continue_with') }}</div>

        <div class="login-social">
            <a href="{{ route('social.redirect', ['provider' => 'google']) }}" class="login-btn-social" style="text-decoration: none;">
                <svg width="16" height="16" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                Google
            </a>
            <a href="{{ route('social.redirect', ['provider' => 'facebook']) }}" class="login-btn-social" style="text-decoration: none;">
                <svg width="16" height="16" viewBox="0 0 24 24"><path fill="#1877F2" d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                Facebook
            </a>
        </div>

        <div class="login-member-banner">
            <img src="https://images.unsplash.com/photo-1549465220-1a8b9238cd48?auto=format&fit=crop&w=200&q=80" alt="Gifts">
            <div class="login-member-info">
                <h4>{{ __('messages.member_privileges') }}</h4>
                <p>{{ __('messages.member_privileges_desc') }}</p>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleLoginModal(e) {
        if (e) e.preventDefault();
        var overlay = document.getElementById('loginModalOverlay');
        if (overlay.style.display === 'flex') {
            overlay.classList.remove('active');
            document.body.style.overflow = '';
            setTimeout(() => { overlay.style.display = 'none'; }, 300);
        } else {
            overlay.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            // slight delay to allow display:flex to apply before adding class for transition
            setTimeout(() => { overlay.classList.add('active'); }, 10);
        }
    }
    
    function switchToRegisterModal(e) {
        if (e) e.preventDefault();
        var loginOverlay = document.getElementById('loginModalOverlay');
        loginOverlay.classList.remove('active');
        setTimeout(() => { 
            loginOverlay.style.display = 'none'; 
            if(typeof toggleRegisterModal === 'function') {
                toggleRegisterModal();
            }
        }, 300);
    }
    
    function togglePasswordVisibility(inputId, iconSpan) {
        var input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
            iconSpan.innerHTML = '<svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="1.5" fill="none"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>';
        } else {
            input.type = "password";
            iconSpan.innerHTML = '<svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="1.5" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
        }
    }
    
    @if(($errors->has('email') || $errors->has('password')) && !$errors->has('name') && !$errors->has('password_confirmation'))
    // Auto-open modal if there are login validation errors after submit
    document.addEventListener('DOMContentLoaded', function() {
        var overlay = document.getElementById('loginModalOverlay');
        overlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        setTimeout(() => { overlay.classList.add('active'); }, 10);
    });
    @endif
</script>
