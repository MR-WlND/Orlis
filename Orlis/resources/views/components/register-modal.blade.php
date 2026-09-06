<div class="register-overlay" id="registerModalOverlay">
    <div class="register-modal" onclick="event.stopPropagation()">
        <div class="close-btn" title="Đóng" onclick="toggleRegisterModal()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>

        <h2 class="register-modal-title">{{ __('messages.create_new_account') }}</h2>
        <p class="register-modal-subtitle">{{ __('messages.register_subtitle') }}</p>

        <form method="POST" action="{{ route('register.post') }}">
            @csrf
            <div class="register-form-box">
                <div class="register-input-group">
                    <input type="text" name="name" placeholder="{{ __('messages.fullname_ph') }}" value="{{ old('name') }}" required>
                    @error('name') <div class="register-error">{{ $message }}</div> @enderror
                </div>
                
                <div class="register-input-group">
                    <input type="email" name="email" placeholder="{{ __('messages.email_addr_ph') }}" value="{{ old('email') }}" required>
                    @error('email') <div class="register-error">{{ $message }}</div> @enderror
                </div>
                
                <div class="register-input-group" style="position: relative;">
                    <input type="password" id="regPassword" name="password" placeholder=".........." required style="padding-right: 35px;">
                    <span onclick="togglePasswordVisibility('regPassword', this)" style="position: absolute; right: 0; top: 12px; cursor: pointer; color: #666;" title="Hiện/Ẩn mật khẩu">
                        <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="1.5" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </span>
                    @error('password') <div class="register-error">{{ $message }}</div> @enderror
                </div>
                
                <div class="register-input-group" style="position: relative;">
                    <input type="password" id="regPasswordConfirm" name="password_confirmation" placeholder="{{ __('messages.confirm_password_ph') }}" required style="padding-right: 35px;">
                    <span onclick="togglePasswordVisibility('regPasswordConfirm', this)" style="position: absolute; right: 0; top: 12px; cursor: pointer; color: #666;" title="Hiện/Ẩn mật khẩu">
                        <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="1.5" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </span>
                </div>

                <div class="register-checkbox-group">
                    <input type="checkbox" name="terms" id="agreeTerms" {{ old('terms') ? 'checked' : '' }} required>
                    <label for="agreeTerms">{{ __('messages.agree_terms') }}</label>
                </div>
                @error('terms') <div class="register-error" style="margin-top: -10px; margin-bottom: 15px;">{{ $message }}</div> @enderror

                <button type="submit" class="register-btn-submit">{{ __('messages.create_account') }}</button>
                <div class="register-btn-login">
                    {{ __('messages.already_have_account') }} <a href="#" onclick="switchToLoginModal(event)">{{ __('messages.login') }}</a>
                </div>
            </div>
        </form>

        <div class="register-divider">{{ __('messages.or_continue_with') }}</div>

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
                <h4>{{ __('messages.member_privileges') }}</h4>
                <p>{{ __('messages.member_privileges_desc') }}</p>
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
    
    if (typeof togglePasswordVisibility !== 'function') {
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
