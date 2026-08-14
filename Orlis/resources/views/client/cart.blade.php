@extends('layouts.client')

@section('title', 'Giỏ hàng - Orlis')

@section('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Alata&family=Castoro:ital,wght@0,400;1,400&family=Charis+SIL:ital,wght@0,400;0,700;1,400;1,700&display=swap');

        :root {
            --bg-color: #f1f4f5;
            --text-dark: #333;
            --text-light: #666;
            --border: #e2e8f0;
            --primary: #474747;
            --font-serif: 'Charis SIL', serif;
            --font-sans: 'Alata', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--bg-color);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        a {
            text-decoration: none;
            color: inherit;
        }


        /* Main Container */
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 20px;
            width: 100%;
            flex: 1;
        }

        .page-title {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 40px;
        }

        .page-title h1 {
            font-family: var(--font-serif);
            font-size: 24px;
            font-weight: 500;
        }

        .page-title span {
            font-size: 13px;
            color: var(--text-light);
        }

        /* Empty Cart State */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .login-banner {
            width: 100%;
            background-color: var(--primary);
            color: white;
            padding: 15px;
            border-radius: 4px;
            font-size: 14px;
            margin-bottom: 15px;
            cursor: pointer;
        }

        .login-note {
            font-size: 12px;
            color: var(--text-light);
            margin-bottom: 60px;
            max-width: 600px;
            line-height: 1.5;
        }

        .bag-icon {
            margin-bottom: 20px;
        }

        .bag-icon svg {
            width: 32px;
            height: 32px;
            stroke: var(--text-light);
            fill: none;
            stroke-width: 1.5;
        }

        .empty-msg {
            font-size: 13px;
            color: var(--text-light);
            margin-bottom: 30px;
        }

        .btn-continue {
            padding: 12px 30px;
            border: 1px solid var(--text-dark);
            background: transparent;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-continue:hover {
            background: var(--text-dark);
            color: white;
        }

        /* Filled Cart State */
        .filled-state {
            display: none; /* Hidden by default for JS toggle */
        }

        .cart-header {
            display: grid;
            grid-template-columns: 50px 3fr 1fr 1fr 1fr;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border);
            font-size: 12px;
            color: var(--text-light);
            text-align: center;
            margin-bottom: 20px;
        }

        .cart-header > :nth-child(2) {
            text-align: left;
        }

        .cart-item {
            display: grid;
            grid-template-columns: 50px 3fr 1fr 1fr 1fr;
            align-items: center;
            background: white;
            padding: 20px 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .cart-item > :nth-child(2) {
            display: flex;
            gap: 20px;
            text-align: left;
            align-items: center;
        }

        .item-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border: 1px solid #f0f0f0;
            background: #fafafa;
        }

        .item-info h4 {
            font-size: 14px;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .item-info p {
            font-size: 12px;
            color: var(--text-light);
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .item-remove {
            font-size: 12px;
            color: var(--text-light);
            text-decoration: underline;
            cursor: pointer;
        }

        .item-price, .item-total {
            font-size: 14px;
            font-weight: 500;
        }

        .qty-control {
            display: inline-flex;
            align-items: center;
            background: #f5f5f5;
            border-radius: 4px;
        }

        .qty-btn {
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: none;
            background: none;
            font-weight: bold;
            color: var(--text-light);
        }

        .qty-input {
            width: 30px;
            text-align: center;
            border: none;
            background: none;
            font-size: 13px;
            font-weight: bold;
        }

        .cart-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 20px;
            border-radius: 4px;
            margin-top: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .footer-left {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }

        .footer-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .total-price {
            font-size: 14px;
            font-weight: bold;
        }

        .btn-checkout {
            padding: 12px 30px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            font-weight: 500;
        }

        /* Custom Checkbox */
        input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        /* Recommendations Section */
        .recommendations {
            margin-top: 80px;
            text-align: center;
        }

        .recommendations h3 {
            font-family: var(--font-serif);
            font-size: 20px;
            font-weight: 500;
            margin-bottom: 30px;
        }

        .product-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .product-card {
            text-align: center;
        }

        .product-img {
            width: 100%;
            aspect-ratio: 1;
            background: #f8f8f8;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .product-img img {
            width: 80%;
            height: 80%;
            object-fit: contain;
        }

        .product-card h4 {
            font-size: 13px;
            margin-bottom: 8px;
        }

        .product-card p {
            font-size: 12px;
            color: var(--text-light);
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .product-card .price {
            font-size: 13px;
            font-weight: bold;
        }

        .pagination {
            margin-top: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            font-size: 14px;
        }
        
        .pagination span {
            cursor: pointer;
        }

        /* Developer Toggle Tool */
        .dev-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #fff;
            padding: 10px 15px;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            font-size: 12px;
            cursor: pointer;
            z-index: 1000;
            border: 1px solid var(--border);
        }
    </style>
@endsection

@section('content')
    <div class="container" style="margin-top: 80px;">
        
        <!-- Shared Title -->
        <div class="page-title">
            <h2>Túi của bạn</h2>
            @auth
            <span id="item-count">2 mặt hàng</span>
            @else
            <span id="item-count">0 mặt hàng</span>
            @endauth
        </div>

        @guest
        <!-- Empty State (Guest) -->
        <div class="empty-state" id="empty-state">
            <a href="#" onclick="toggleLoginModal(event)" style="width: 100%; display: flex; justify-content: center;">
                <button class="login-banner" style="max-width: 600px;">Đăng nhập hoặc tạo tài khoản Orlis</button>
            </a>
            <p class="login-note">Nhận quà tặng và phần thưởng dành riêng cho thành viên - bao gồm quyền lựa chọn các sản phẩm thu nhỏ độc quyền khi thanh toán với hóa đơn từ 100 đô la trở lên.</p>
            
            <div class="bag-icon">
                <svg viewBox="0 0 24 24">
                    <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            
            <p class="empty-msg">Vui lòng đăng nhập để xem giỏ hàng của bạn</p>
            
            <a href="/"><button class="btn-continue">Tiếp tục mua sắm</button></a>
        </div>
        @endguest

        @auth
        <!-- Filled State (Logged In) -->
        <div class="filled-state" id="filled-state" style="display: block;">
            <div class="cart-header">
                <div></div>
                <div>Thông tin chi tiết</div>
                <div>Đơn giá</div>
                <div>Số lượng</div>
                <div>Tổng giá</div>
            </div>

            <!-- Item 1 -->
            <div class="cart-item">
                <div><input type="checkbox" class="item-cb"></div>
                <div>
                    <img src="https://images.unsplash.com/photo-1590736969955-71cc94801759?auto=format&fit=crop&w=300&q=80" alt="Perfume 1" class="item-img">
                    <div class="item-info">
                        <h4>Tinh chất miss Orlis</h4>
                        <p>Essence miss Orlis<br>Hương thơm mát -<br>Hoa và gỗ</p>
                        <span class="item-remove">Xóa</span>
                    </div>
                </div>
                <div class="item-price">$162,00</div>
                <div>
                    <div class="qty-control">
                        <button class="qty-btn">-</button>
                        <input type="text" value="1" class="qty-input" readonly>
                        <button class="qty-btn">+</button>
                    </div>
                </div>
                <div class="item-total">$162,00</div>
            </div>

            <!-- Item 2 -->
            <div class="cart-item">
                <div><input type="checkbox" class="item-cb"></div>
                <div>
                    <img src="https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=300&q=80" alt="Perfume 2" class="item-img">
                    <div class="item-info">
                        <h4>Nước hoa miss Orlis</h4>
                        <p>Essence miss Orlis<br>Hương thơm ngọt ngào -<br>Hoa và gỗ</p>
                        <span class="item-remove">Xóa</span>
                    </div>
                </div>
                <div class="item-price">$184,00</div>
                <div>
                    <div class="qty-control">
                        <button class="qty-btn">-</button>
                        <input type="text" value="1" class="qty-input" readonly>
                        <button class="qty-btn">+</button>
                    </div>
                </div>
                <div class="item-total">$184,00</div>
            </div>

            <!-- Footer Check -->
            <div class="cart-footer">
                <div class="footer-left">
                    <input type="checkbox" id="check-all">
                    <label for="check-all">Chọn tất cả</label>
                </div>
                <div class="footer-right">
                    <div class="total-price">Tổng: $0</div>
                    <button class="btn-checkout">Thanh toán</button>
                </div>
            </div>
        </div>
        @endauth

        <!-- Recommendations (Shared) -->
        <div class="recommendations">
            <h3>Có thể bạn sẽ thích</h3>
            <div class="product-list">
                <div class="product-card">
                    <div class="product-img">
                        <img src="https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=300&q=80" alt="Product">
                    </div>
                    <h4>Tinh chất miss Orlis</h4>
                    <p>Nước hoa - Hương thơm<br>ngọt ngào và quyến rũ</p>
                    <div class="price">$184,00</div>
                </div>
                <div class="product-card">
                    <div class="product-img">
                        <img src="https://images.unsplash.com/photo-1590736969955-71cc94801759?auto=format&fit=crop&w=300&q=80" alt="Product">
                    </div>
                    <h4>Tinh chất miss Orlis</h4>
                    <p>Essence miss Orlis<br>Hương thơm mát -<br>Hoa và gỗ</p>
                    <div class="price">$162,00</div>
                </div>
                <div class="product-card">
                    <div class="product-img">
                        <img src="https://images.unsplash.com/photo-1588405748880-12d1d2a59f75?auto=format&fit=crop&w=300&q=80" alt="Product">
                    </div>
                    <h4>Nước hoa miss Orlis</h4>
                    <p>Nước hoa - Hương thơm<br>ngọt ngào và quyến rũ</p>
                    <div class="price">$184,00</div>
                </div>
            </div>
            
            <div class="pagination">
                <span>&lt;</span>
                <span>1/1</span>
                <span>&gt;</span>
            </div>
        </div>

    </div>
@endsection
