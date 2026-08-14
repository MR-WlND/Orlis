@extends('layouts.client')

@section('title', 'Dior Andise Zipped Bag - Orlis')

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
            width: 65%;
            display: flex;
            flex-direction: column;
        }
        .product-images img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: cover;
        }

        /* Right Column - Details */
        .product-details-col {
            width: 35%;
            padding: 40px 60px;
            background-color: #fff;
            position: relative;
        }
        .product-details-sticky {
            position: sticky;
            top: 100px; /* offset from header */
        }

        .product-title {
            font-family: var(--font-serif);
            font-size: 28px;
            font-weight: 500;
            margin-bottom: 10px;
            line-height: 1.3;
        }
        .product-subtitle {
            font-size: 13px;
            color: var(--text-light);
            margin-bottom: 30px;
        }

        /* Color Swatches */
        .swatch-title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .swatches {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }
        .swatch {
            width: 40px;
            height: 40px;
            border-radius: 4px;
            border: 1px solid transparent;
            cursor: pointer;
            padding: 2px;
            transition: border 0.2s;
        }
        .swatch img {
            width: 100%;
            height: 100%;
            border-radius: 2px;
            object-fit: cover;
        }
        .swatch.active { border-color: #333; }
        
        .product-attributes {
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #eaeaea;
            padding-bottom: 10px;
        }

        /* Buttons */
        .btn-action {
            width: 100%;
            padding: 15px;
            font-size: 13px;
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            border-radius: 2px;
            margin-bottom: 15px;
            transition: all 0.3s;
        }
        .btn-find {
            background-color: #fff;
            color: #333;
            border: 1px solid #333;
        }
        .btn-find:hover { background-color: #f5f5f5; }
        .btn-contact {
            background-color: #1a1a1a;
            color: #fff;
            border: 1px solid #1a1a1a;
        }
        .btn-contact:hover { background-color: #333; }

        /* Accordions */
        .accordion {
            margin-top: 30px;
            border-top: 1px solid #eaeaea;
        }
        .accordion-item {
            border-bottom: 1px solid #eaeaea;
        }
        .accordion-header {
            padding: 20px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
        }
        .accordion-content {
            padding-bottom: 20px;
            font-size: 13px;
            color: var(--text-light);
            line-height: 1.6;
            display: none;
        }
        .accordion-item.active .accordion-content { display: block; }
        .accordion-header svg { width: 14px; height: 14px; transition: transform 0.3s; }
        .accordion-item.active .accordion-header svg { transform: rotate(180deg); }

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
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .grid-2 { grid-template-columns: repeat(2, 1fr); max-width: 800px; }

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
                
                <h1 class="product-title">Dior Andise Zipped Bag with Strap</h1>
                <p class="product-subtitle">Blue Dior Oblique Jacquard<br><br>Reference: 12BO1234_C123</p>
                
                <div class="swatch-title">Color (Blue)</div>
                <div class="swatches">
                    <div class="swatch"><img src="https://images.unsplash.com/photo-1591561954557-26941169b49e?auto=format&fit=crop&w=50&q=80" alt="Black"></div>
                    <div class="swatch"><img src="https://images.unsplash.com/photo-1590736704728-f4730bb30770?auto=format&fit=crop&w=50&q=80" alt="Brown"></div>
                    <div class="swatch"><img src="https://images.unsplash.com/photo-1549465220-1a8b9238cd48?auto=format&fit=crop&w=50&q=80" alt="Tan"></div>
                    <div class="swatch active"><img src="https://images.unsplash.com/photo-1598532163257-ae3c6b2524b6?auto=format&fit=crop&w=50&q=80" alt="Blue"></div>
                    <div class="swatch"><img src="https://images.unsplash.com/photo-1548036328-c9fa89d128fa?auto=format&fit=crop&w=50&q=80" alt="Green"></div>
                </div>

                <div class="product-attributes">
                    <span>One Size</span>
                    <span>$1,400.00</span>
                </div>

                <button class="btn-action btn-find">Find in boutique</button>
                <button class="btn-action btn-contact">Contact us</button>

                <!-- Accordions -->
                <div class="accordion">
                    <div class="accordion-item active" onclick="this.classList.toggle('active')">
                        <div class="accordion-header">
                            Description 
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round"/></svg>
                        </div>
                        <div class="accordion-content">
                            The Dior Andise zipped bag with strap offers a practical and modern silhouette. Crafted in blue Dior Oblique jacquard, it is embellished with the 'DIOR' signature on the front. A two-way zip closure on top reveals a spacious compartment. Featuring an adjustable and removable leather shoulder strap, the versatile bag can be carried by hand, worn over the shoulder or crossbody.
                        </div>
                    </div>
                    <div class="accordion-item" onclick="this.classList.toggle('active')">
                        <div class="accordion-header">
                            Details 
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round"/></svg>
                        </div>
                        <div class="accordion-content">
                            - Dimensions: 17 x 12.5 x 5 cm / 6.5 x 5 x 2 inches<br>
                            - Blue Dior Oblique jacquard<br>
                            - Ruthenium-finish brass 'DIOR' signature<br>
                            - Interior patch pocket<br>
                            - Adjustable and removable leather shoulder strap
                        </div>
                    </div>
                    <div class="accordion-item" onclick="this.classList.toggle('active')">
                        <div class="accordion-header">
                            Delivery & Returns 
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round"/></svg>
                        </div>
                        <div class="accordion-content">
                            Free standard delivery for all orders. Returns are accepted within 30 days of purchase.
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Recommendations -->
    <div class="bottom-section">
        <h2 class="section-title">You may also like</h2>
        <div class="product-grid">
            <div class="product-card">
                <img src="https://images.unsplash.com/photo-1560769629-975ec94e6a86?auto=format&fit=crop&w=400&q=80" alt="Shoes">
                <h4>Dior B23 Low-Top Sneaker</h4>
                <p>$1,050.00</p>
            </div>
            <div class="product-card">
                <img src="https://images.unsplash.com/photo-1556821840-3a63f95609a7?auto=format&fit=crop&w=400&q=80" alt="Sweater">
                <h4>Dior Oblique Sweater</h4>
                <p>$1,850.00</p>
            </div>
            <div class="product-card">
                <img src="https://images.unsplash.com/photo-1588850561407-ed78c282e89b?auto=format&fit=crop&w=400&q=80" alt="Cap">
                <h4>Dior Baseball Cap</h4>
                <p>$590.00</p>
            </div>
        </div>
    </div>

    <div class="bottom-section" style="background: white; border-top: 1px solid #eaeaea;">
        <h2 class="section-title">Recently viewed</h2>
        <div class="product-grid grid-2">
            <div class="product-card">
                <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=400&q=80" alt="T-Shirt">
                <h4>CD Icon T-Shirt</h4>
                <p>$650.00</p>
            </div>
            <div class="product-card">
                <img src="https://images.unsplash.com/photo-1548036328-c9fa89d128fa?auto=format&fit=crop&w=400&q=80" alt="Tote Bag">
                <h4>Dior Book Tote</h4>
                <p>$3,500.00</p>
            </div>
        </div>
    </div>

@endsection
