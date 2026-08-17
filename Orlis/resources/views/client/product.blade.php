@extends('layouts.client')

@section('title', 'Orlis - Váy Lụa Đen Tuyền')

@section('content')
<div class="pdp-container">
    <div class="pdp-gallery">
        <img src="{{ asset('images/orlis_model_1.png') }}" alt="Váy Lụa Đen Tuyền 1">
        <img src="{{ asset('images/orlis_bag.png') }}" alt="Váy Lụa Đen Tuyền 2">
        <img src="{{ asset('images/orlis_shoes.png') }}" alt="Váy Lụa Đen Tuyền 3">
    </div>
    
    <div class="pdp-info-wrapper">
        <div class="pdp-info">
            <div class="pdp-breadcrumb">Trang Chủ / Thời Trang Nữ / Váy Lụa</div>
            <h1 class="pdp-title">Váy Lụa Đen Tuyền</h1>
            <p class="pdp-price">25,000,000 ₫</p>
            
            <div class="pdp-description">
                Một biểu tượng của sự quyến rũ vượt thời gian. Váy lụa đen tuyền được dệt từ lụa tơ tằm thượng hạng, ôm sát những đường cong hoàn mỹ và tôn lên vẻ đẹp kiêu sa của người phụ nữ hiện đại. Phía sau lưng khoét chữ V sâu tinh tế, kết hợp với chi tiết đính kết thủ công từ những nghệ nhân Paris.
            </div>

            <div class="pdp-options">
                <div class="option-group">
                    <label>MÀU SẮC: ĐEN TUYỀN</label>
                    <div class="color-selector">
                        <span class="color-btn active" style="background: #111;"></span>
                        <span class="color-btn" style="background: #e6dac3;"></span>
                    </div>
                </div>

                <div class="option-group">
                    <div class="size-label-wrapper">
                        <label>KÍCH CỠ</label>
                        <a href="#" class="size-guide">Hướng Dẫn Kích Cỡ</a>
                    </div>
                    <div class="size-selector">
                        <span class="size-btn">34</span>
                        <span class="size-btn active">36</span>
                        <span class="size-btn">38</span>
                        <span class="size-btn disabled">40</span>
                    </div>
                </div>
            </div>

            <div class="pdp-actions">
                <button class="btn-pdp btn-dark">THÊM VÀO TÚI</button>
                <button class="btn-pdp btn-light">ĐẶT LỊCH THỬ ĐỒ VIP</button>
            </div>

            <div class="pdp-accordion">
                <div class="accordion-item">
                    <h4>CHI TIẾT SẢN PHẨM</h4>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 5v14M5 12h14"/></svg>
                </div>
                <div class="accordion-item">
                    <h4>GIAO HÀNG & ĐỔI TRẢ</h4>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 5v14M5 12h14"/></svg>
                </div>
                <div class="accordion-item">
                    <h4>DỊCH VỤ ĐỘC QUYỀN</h4>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 5v14M5 12h14"/></svg>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
