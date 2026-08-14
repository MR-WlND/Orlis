@extends('layouts.client')

@section('title', 'Túi xách Dior Andise - Orlis')

@section('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Alata&family=Castoro:ital,wght@0,400;1,400&family=Charis+SIL:ital,wght@0,400;0,700;1,400;1,700&display=swap');

        :root {
            --bg-color: #f8f8f8;
            --text-dark: #333;
            --text-light: #666;
            --border: #e2e8f0;
            --primary: #1a1a1a;
            --font-serif: 'Charis SIL', serif;
            --font-sans: 'Alata', sans-serif;
            --font-logo: 'Castoro', serif;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: var(--font-sans);
            background-color: #fff;
            color: var(--text-dark);
            min-height: 100vh;
        }
        a { text-decoration: none; color: inherit; }



        /* Product Layout */
        .product-container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* Left Column - Images */
        .product-images {
            width: 50%;
            display: flex;
            flex-direction: column;
            background-color: #e5e5e5; /* Match screenshot background */
        }
        .product-images img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: contain; /* Don't crop if it's already on a colored background */
            mix-blend-mode: multiply; /* Helps blend with grey background */
        }

        /* Right Column - Details */
        .product-details-col {
            width: 50%;
            padding: 60px 80px;
            background-color: #fbfbfb;
            position: relative;
        }
        .product-details-sticky {
            position: sticky;
            top: 70px; /* Sát header hơn */
            max-height: calc(200vh - 100px);
            padding-top: 20px;
            overflow-y: auto;
            
            /* Hide scrollbar visually but keep functionality */
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE and Edge */
        }
        .product-details-sticky::-webkit-scrollbar {
            display: none; /* Chrome, Safari and Opera */
        }

        .product-title {
            font-family: var(--font-logo);
            font-size: 34px;
            font-weight: 400;
            margin-bottom: 5px;
            line-height: 1.2;
            color: #111;
        }
        .product-subtitle {
            font-size: 13px;
            color: #555;
            margin-bottom: 40px;
        }

        /* Color Swatches */
        .swatch-title {
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #333;
        }
        .swatches {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
        }
        .swatch {
            width: 36px;
            height: 24px;
            border-radius: 2px;
            border: 1px solid transparent;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .swatch:hover { opacity: 0.8; }
        .swatch.active { 
            outline: 2px solid #333;
            outline-offset: 2px;
        }

        /* Buttons */
        .btn-action {
            width: 100%;
            padding: 16px 20px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            border-radius: 2px;
            margin-bottom: 12px;
            transition: all 0.3s;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-add-cart {
            background-color: #444;
            color: #fff;
            border: none;
        }
        .btn-add-cart:hover { background-color: #222; }
        
        .btn-checkout {
            background-color: #fff;
            color: #333;
            border: 1px solid #ddd;
        }
        .btn-checkout:hover { background-color: #f9f9f9; }
        
        .delivery-info {
            font-size: 11px;
            color: #666;
            margin-top: 15px;
            margin-bottom: 25px;
        }
        
        .support-info {
            font-size: 11px;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        /* Tabs */
        .tabs-header {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }
        .tab-item {
            font-size: 11px;
            color: #888;
            padding-bottom: 8px;
            cursor: pointer;
            text-transform: uppercase;
        }
        .tab-item.active {
            color: #111;
            font-weight: 600;
            border-bottom: 2px solid #111;
        }
        .tab-content {
            font-size: 12px;
            color: #444;
            line-height: 1.6;
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .tab-content ul {
            padding-left: 15px;
            margin-top: 10px;
        }
        .tab-content ul li {
            margin-bottom: 5px;
        }

        /* Bottom Sections */
        .bottom-section {
            padding: 80px 40px;
            background-color: #fafafa;
            text-align: center;
        }
        .section-title {
            font-family: var(--font-serif);
            font-size: 24px;
            margin-bottom: 40px;
        }
        
        .product-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .product-card {
            background: #fff;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s;
        }
        .product-card:hover { transform: translateY(-5px); }
        .product-card img {
            width: 100%;
            aspect-ratio: 4/5;
            object-fit: cover;
            margin-bottom: 15px;
            background: #f5f5f5;
        }
        .product-card h4 {
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 5px;
        }
        .product-card p {
            font-size: 12px;
            color: var(--text-light);
        }

        /* Footer */
        footer {
            background-color: #fff;
            padding: 60px 40px 20px;
            border-top: 1px solid #eaeaea;
        }
        .footer-links {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }
        .footer-col h4 {
            font-size: 13px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .footer-col ul { list-style: none; }
        .footer-col ul li { margin-bottom: 10px; }
        .footer-col ul li a { font-size: 12px; color: var(--text-light); }
        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #eaeaea;
            font-size: 12px;
            color: var(--text-light);
        }

        @media (max-width: 900px) {
            .product-container { flex-direction: column; }
            .product-images { width: 100%; }
            .product-details-col { width: 100%; padding: 30px 20px; }
            .product-details-sticky { position: static; }
            .product-grid { grid-template-columns: 1fr; }
            .footer-links { flex-direction: column; gap: 30px; }
        }
    </style>
@endsection

@section('content')
    <!-- Product Layout -->
    <div class="product-container">
        <!-- Left: Images Stack -->
        <div class="product-images">
            <!-- Source images imitating the provided design -->
            <img src="https://images.unsplash.com/photo-1549465220-1a8b9238cd48?auto=format&fit=crop&w=1000&q=80" alt="Product 1">
            <img src="https://images.unsplash.com/photo-1590736704728-f4730bb30770?auto=format&fit=crop&w=1000&q=80" alt="Product 2">
            <img src="https://images.unsplash.com/photo-1591561954557-26941169b49e?auto=format&fit=crop&w=1000&q=80" alt="Product 3">
            <img src="https://images.unsplash.com/photo-1598532163257-ae3c6b2524b6?auto=format&fit=crop&w=1000&q=80" alt="Product Detail">
        </div>

        <!-- Right: Sticky Details -->
        <div class="product-details-col">
            <div class="product-details-sticky">
                
                <h1 class="product-title">Small Dior Bow Bag</h1>
                <p class="product-subtitle">Latte Lambskin</p>
                
                <div class="swatch-title">Other color</div>
                <div class="swatches">
                    <div class="swatch" style="background: #ffcc99;"></div>
                    <div class="swatch active" style="background: #99ccff;"></div>
                    <div class="swatch" style="background: #ff99ff;"></div>
                </div>

                <button class="btn-action btn-add-cart">
                    <span>Thêm vào giỏ hàng</span>
                    <span>$1,000.00</span>
                </button>
                <button class="btn-action btn-checkout">
                    <span>Thanh toán nhanh</span>
                    <span style="display: flex; align-items: center; gap: 8px;">VNpay <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg></span>
                </button>
                
                <div class="delivery-info">
                    Nhận hàng sớm nhất vào ngày 11 tháng 4.
                </div>
                
                <div class="support-info">
                    Đội ngũ tư vấn khách hàng của chúng tôi rất hân hạnh được hỗ trợ bạn.<br>
                    Vui lòng liên hệ với chúng tôi theo số +1 800 929 3467
                </div>

                <!-- Tabs Navigation -->
                <div class="tabs-header">
                    <div class="tab-item active" onclick="switchProductTab(this, 'tab-desc')">Mô tả</div>
                    <div class="tab-item" onclick="switchProductTab(this, 'tab-size')">Kích thước</div>
                    <div class="tab-item" onclick="switchProductTab(this, 'tab-contact')">Thông tin liên hệ</div>
                    <div class="tab-item" onclick="switchProductTab(this, 'tab-shipping')">Giao hàng & trả hàng</div>
                </div>

                <!-- Tabs Content -->
                <div class="tab-content active" id="tab-desc">
                    Chiếc túi Dior Bow Bag mới của Jonathan Anderson thể hiện sự thanh lịch kiểu Pháp và tay nghề thủ công tinh xảo của các xưởng chế tác. Chất liệu mềm mại từ da cừu tự nhiên cao cấp với lớp hoàn thiện bán bóng tôn vinh chiếc nơ, một biểu tượng được trân trọng của nhà mốt. Thiết kế nhỏ gọn này có thể đeo vai hoặc cầm tay như một chiếc clutch.
                    <ul>
                        <li>Thành phần chính: da cừu</li>
                        <li>Lớp lót bằng da bê và da cừu</li>
                        <li>Khóa từ</li>
                        <li>Logo Dior mạ bạc được dập nổi ở mặt trước.</li>
                        <li>Dây chuyền với nơ đặc trưng của Dior và chi tiết da.</li>
                        <li>Túi khóa kéo bên trong</li>
                        <li>Bao đựng bụi đi kèm.</li>
                        <li>Sản xuất tại Ý</li>
                    </ul>
                </div>
                
                <div class="tab-content" id="tab-size">
                    <ul>
                        <li>Kích thước: 26 x 16 x 10 cm / 10 x 6.5 x 4 inch (Chiều dài x Chiều cao x Chiều rộng)</li>
                        <li>Kích thước phù hợp để đựng điện thoại, ví đựng thẻ, kính râm và son môi.</li>
                        <li>Chiều dài dây chuyền: 88 cm / 34,5 inch</li>
                        <li>Chiều dài dây chuyền: 42 cm / 16,5 inch</li>
                        <li>Trọng lượng: 340 g / 12 ounce</li>
                        <li>Thông tin về kích thước và trọng lượng có thể thay đổi tùy thuộc vào chất liệu sản phẩm.</li>
                    </ul>
                </div>
                
                <div class="tab-content" id="tab-contact">
                    
                </div>
                
                <div class="tab-content" id="tab-shipping">
                    <strong>Đổi trả miễn phí trong vòng 30 ngày</strong><br>
                    Bạn có thể trả lại hoặc đổi bất kỳ đơn hàng nào - trong tình trạng nguyên vẹn - trong vòng 30 ngày kể từ khi nhận hàng, trừ trường hợp đó là mặt hàng được cá nhân hóa (đặc biệt là những mặt hàng còn nguyên tem mác và các miếng dán).<br>
                    Để biết thêm thông tin chi tiết, vui lòng tham khảo phần Câu hỏi thường gặp (FAQ).<br><br>
                    <strong>Giao hàng tiêu chuẩn miễn phí</strong><br>
                    Thời gian giao hàng được ước tính từ thời điểm đơn hàng của bạn được gửi đi. Tùy thuộc vào địa điểm giao hàng và tính chất cụ thể của đơn hàng, Dior cung cấp tối đa bốn tùy chọn giao hàng nhanh chóng và an toàn:<br>
                    - Giao hàng tiêu chuẩn (1 đến 5 ngày làm việc tùy thuộc vào địa điểm): Miễn phí cho tất cả các đơn hàng
                </div>
                
                <script>
                    window.switchProductTab = function(element, tabId) {
                        // Remove active class from all tabs
                        document.querySelectorAll('.tab-item').forEach(t => t.classList.remove('active'));
                        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                        
                        // Add active class to clicked tab and corresponding content
                        element.classList.add('active');
                        document.getElementById(tabId).classList.add('active');
                    };
                </script>

            </div>
        </div>
    </div>

    <!-- Recommendations -->
    <div class="bottom-section">
        <h2 class="section-title">Có thể bạn sẽ thích</h2>
        <div class="perfume-product-grid">
            <a href="#" class="perfume-product-card" style="text-decoration: none; color: inherit; display: block;">
                <div class="product-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1560769629-975ec94e6a86?auto=format&fit=crop&w=400&q=80" alt="Giày">
                </div>
                <div class="product-info">
                    <span class="product-category">Giày thể thao</span>
                    <h3 class="product-name">Giày thể thao Dior B23 cổ thấp</h3>
                    <p class="product-price">$1,050.00</p>
                </div>
            </a>
            <a href="#" class="perfume-product-card" style="text-decoration: none; color: inherit; display: block;">
                <div class="product-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1556821840-3a63f95609a7?auto=format&fit=crop&w=400&q=80" alt="Áo len">
                </div>
                <div class="product-info">
                    <span class="product-category">Áo len</span>
                    <h3 class="product-name">Áo len Dior Oblique</h3>
                    <p class="product-price">$1,850.00</p>
                </div>
            </a>
            <a href="#" class="perfume-product-card" style="text-decoration: none; color: inherit; display: block;">
                <div class="product-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1588850561407-ed78c282e89b?auto=format&fit=crop&w=400&q=80" alt="Mũ">
                </div>
                <div class="product-info">
                    <span class="product-category">Mũ</span>
                    <h3 class="product-name">Mũ bóng chày Dior</h3>
                    <p class="product-price">$590.00</p>
                </div>
            </a>
            <a href="#" class="perfume-product-card" style="text-decoration: none; color: inherit; display: block;">
                <div class="product-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1574251144186-880521e053f3?auto=format&fit=crop&w=400&q=80" alt="Túi">
                </div>
                <div class="product-info">
                    <span class="product-category">Kính râm</span>
                    <h3 class="product-name">Kính râm Dior Bobby</h3>
                    <p class="product-price">$450.00</p>
                </div>
            </a>
        </div>
    </div>

    <div class="bottom-section" style="background: white; border-top: 1px solid #eaeaea;">
        <h2 class="section-title">Đã xem gần đây</h2>
        <div class="perfume-product-grid">
            <a href="#" class="perfume-product-card" style="text-decoration: none; color: inherit; display: block;">
                <div class="product-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=400&q=80" alt="Áo thun">
                </div>
                <div class="product-info">
                    <span class="product-category">Áo thun</span>
                    <h3 class="product-name">Áo thun CD Icon</h3>
                    <p class="product-price">$650.00</p>
                </div>
            </a>
            <a href="#" class="perfume-product-card" style="text-decoration: none; color: inherit; display: block;">
                <div class="product-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1548036328-c9fa89d128fa?auto=format&fit=crop&w=400&q=80" alt="Túi Tote">
                </div>
                <div class="product-info">
                    <span class="product-category">Túi xách</span>
                    <h3 class="product-name">Túi xách Dior Book Tote</h3>
                    <p class="product-price">$3,500.00</p>
                </div>
            </a>
            <a href="#" class="perfume-product-card" style="text-decoration: none; color: inherit; display: block;">
                <div class="product-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1627384113743-6bd5a479fffd?auto=format&fit=crop&w=400&q=80" alt="Trang sức">
                </div>
                <div class="product-info">
                    <span class="product-category">Trang sức</span>
                    <h3 class="product-name">Vòng cổ Dior Tribales</h3>
                    <p class="product-price">$850.00</p>
                </div>
            </a>
            <a href="#" class="perfume-product-card" style="text-decoration: none; color: inherit; display: block;">
                <div class="product-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1584916201218-f4242ceb4809?auto=format&fit=crop&w=400&q=80" alt="Nước hoa">
                </div>
                <div class="product-info">
                    <span class="product-category">Nước hoa</span>
                    <h3 class="product-name">Nước hoa Sauvage Elixir</h3>
                    <p class="product-price">$195.00</p>
                </div>
            </a>
        </div>
    </div>

@endsection
