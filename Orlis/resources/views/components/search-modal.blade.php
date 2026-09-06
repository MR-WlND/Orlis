<div class="search-modal-overlay" id="searchModalOverlay">
    <div class="search-modal-content">
        <!-- Nút đóng -->
        <div class="search-modal-header" style="display: flex; justify-content: space-between; align-items: center; padding: 30px 30px 20px 30px; border-bottom: 1px solid transparent; background: #fff;">
            <button class="search-modal-close" onclick="closeSearchModal()" style="display: flex; align-items: center; gap: 8px; margin: 0; border: none; background: transparent; cursor: pointer; color: #333; font-size: 14px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 20px; height: 20px;"><path d="M18 6L6 18M6 6l12 12"/></svg>
                {{ __('messages.close') }}
            </button>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 20px; height: 20px; color: #333;"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35"/></svg>
        </div>

        <div class="search-modal-body" style="padding: 0 30px 30px 30px;">
            <!-- Thanh nhập liệu -->
            <form action="{{ route('catalog') }}" method="GET" class="search-modal-input-wrap">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35"/></svg>
                <input type="text" name="search" autocomplete="off" placeholder="{{ __('messages.what_are_you_looking_for') }}" id="searchModalInput">
                <button type="submit" class="search-submit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 20px; height: 20px;"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </button>
            </form>

            <!-- Gợi ý từ khoá -->
            <div class="search-suggestions">
                <h4>{{ __('messages.suggestions') }}</h4>
                <ul>
                    <li><a href="{{ route('catalog', ['search' => 'Wallet']) }}">{{ __('messages.wallet') }}</a></li>
                    <li><a href="{{ route('catalog', ['search' => 'Lady bag']) }}">{{ __('messages.lady_bag') }}</a></li>
                    <li><a href="{{ route('catalog', ['search' => 'Lady']) }}">{{ __('messages.lady') }}</a></li>
                    <li><a href="{{ route('catalog', ['search' => 'Earrings']) }}">{{ __('messages.earrings') }}</a></li>
                    <li><a href="{{ route('catalog', ['search' => 'Card holder']) }}">{{ __('messages.card_holder') }}</a></li>
                </ul>
            </div>

            <!-- Sản phẩm gợi ý -->
            <div class="search-recommended">
                <h4>{{ __('messages.you_may_also_like') }}</h4>
                <div class="search-products-grid">
                    <div class="search-product-card" onclick="window.location='#'">
                        <img src="https://images.unsplash.com/photo-1599643478524-fb66f72400de?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Earrings">
                    </div>
                    <div class="search-product-card" onclick="window.location='#'">
                        <img src="https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Sneakers">
                    </div>
                    <div class="search-product-card desktop-only-card" onclick="window.location='#'">
                        <img src="https://images.unsplash.com/photo-1627384113743-6bd5a479fffd?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Bag">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleSearchModal() {
        const overlay = document.getElementById('searchModalOverlay');
        overlay.classList.add('active');
        setTimeout(() => {
            document.getElementById('searchModalInput').focus();
        }, 300);
    }

    function closeSearchModal() {
        const overlay = document.getElementById('searchModalOverlay');
        overlay.classList.remove('active');
    }

    // Đóng khi click ra ngoài nội dung
    document.getElementById('searchModalOverlay').addEventListener('click', function(e) {
        if (e.target === this) {
            closeSearchModal();
        }
    });
</script>
