@extends('layouts.client')

@section('title', 'Giỏ hàng - Orlis')

@section('styles')
    <style>


        /* Main Container */
        .container {
            max-width: 800px;
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
            color: #666;
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
            grid-template-columns: 16px 4fr 1fr 1.5fr 1fr;
            gap: 24px;
            padding-bottom: 15px;
            padding-left: 24px;
            padding-right: 24px;
            border-bottom: 1px solid #d0d0d0;
            font-size: 13px;
            color: #666;
            text-align: center;
            margin-bottom: 15px;
            font-weight: 400;
        }

        .cart-header > :nth-child(2) {
            text-align: left;
        }

        .cart-item {
            display: grid;
            grid-template-columns: 16px 4fr 1fr 1.5fr 1fr;
            gap: 24px;
            align-items: center;
            background: white;
            padding: 16px 24px;
            border-radius: 8px;
            margin-bottom: 15px;
            text-align: center;
        }

        .cart-item > :nth-child(2) {
            display: flex;
            gap: 24px;
            text-align: left;
            align-items: center;
        }

        .item-img {
            width: 90px;
            height: 120px;
            object-fit: cover;
            border: 1px solid #e0e0e0;
            background: #ffffff;
            border-radius: 2px;
        }

        .item-info h4 {
            font-family: var(--font-sans);
            font-size: 14px;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
        }

        .item-info p {
            font-family: var(--font-sans);
            font-size: 13px;
            color: #666;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .item-remove {
            font-size: 12px;
            color: #555;
            text-decoration: underline;
            cursor: pointer;
        }

        .item-price, .item-total {
            font-family: var(--font-sans);
            font-size: 14px;
            font-weight: 700;
            color: #333;
        }

        .qty-control {
            display: inline-flex;
            align-items: center;
            background: #e2e2e2;
            border-radius: 4px;
            height: 32px;
            padding: 0 6px;
        }

        .qty-btn {
            width: 24px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: none;
            background: none;
            font-size: 14px;
            font-weight: 500;
            color: #333;
        }

        .qty-input {
            width: 28px;
            text-align: center;
            border: none;
            background: none;
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }

        .cart-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 16px 24px;
            border-radius: 8px;
            margin-top: 15px;
            position: sticky;
            bottom: 20px;
            z-index: 100;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.08);
        }

        .footer-left {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            color: #333;
        }

        .footer-right {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .total-price {
            font-family: var(--font-sans);
            font-size: 14px;
            font-weight: 700;
            color: #333;
        }

        .btn-checkout {
            padding: 14px 40px;
            background: #444444;
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
            appearance: none;
            background-color: #dbdbdb;
            border-radius: 2px;
            border: none;
        }
        
        input[type="checkbox"]:checked {
            background-color: #888;
            border-color: #888;
        }

        /* Recommendations Section */
        .recommendations {
            margin-top: 80px;
            text-align: center;
        }

        .recommendations h3 {
            font-family: var(--font-serif);
            font-size: 24px;
            font-weight: 400;
            margin-bottom: 40px;
            color: #333;
        }

        .product-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .product-card {
            text-align: center;
        }

        .product-img {
            width: 100%;
            aspect-ratio: 3/4;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .product-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-card h4 {
            font-family: var(--font-sans);
            font-size: 13px;
            font-weight: 600;
            color: #444;
            margin-bottom: 8px;
        }

        .product-card p {
            font-family: var(--font-sans);
            font-size: 12px;
            color: #666;
            margin-bottom: 12px;
            line-height: 1.5;
        }

        .product-card .price {
            font-family: var(--font-sans);
            font-size: 13px;
            font-weight: 700;
            color: #333;
        }

        .pagination {
            margin-top: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 40px;
            font-size: 14px;
            font-weight: 500;
            color: #333;
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
<div style="background-color: #f1f4f5; min-height: 100vh; padding-top: 80px; padding-bottom: 80px;">
    <div class="container">
        
        <!-- Shared Title -->
        <div class="page-title">
            <h1>Túi của bạn</h1>
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
                    <img src="{{ asset('images/perfume_gold_drop.png') }}" alt="Perfume 1" class="item-img">
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
                    <img src="{{ asset('images/perfume_pink_bow.png') }}" alt="Perfume 2" class="item-img">
                    <div class="item-info">
                        <h4>Tinh chất miss Orlis</h4>
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
                        <img src="{{ asset('images/perfume_pink_bow.png') }}" alt="Product">
                    </div>
                    <h4>Tinh chất miss Orlis</h4>
                    <p>Nước hoa - Hương thơm<br>ngọt ngào và quyến rũ</p>
                    <div class="price">$184,00</div>
                </div>
                <div class="product-card">
                    <div class="product-img">
                        <img src="{{ asset('images/perfume_gold_drop.png') }}" alt="Product">
                    </div>
                    <h4>Tinh chất miss Orlis</h4>
                    <p>Essence miss Orlis<br>Hương thơm mát -<br>Hoa và gỗ</p>
                    <div class="price">$162,00</div>
                </div>
                <div class="product-card">
                    <div class="product-img">
                        <img src="{{ asset('images/perfume_gold_drop.png') }}" alt="Product">
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
</div>
@endsection
