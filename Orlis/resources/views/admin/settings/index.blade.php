@extends('layouts.admin')

@section('title', 'Cấu Hình Hệ Thống')

@section('content')
<div class="header-container">
    <h1 class="page-title">Cấu Hình Hệ Thống</h1>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card" style="margin-top: 20px;">
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="tabs">
            <button type="button" class="tab-btn active" onclick="openTab(event, 'general')">Chung</button>
            <button type="button" class="tab-btn" onclick="openTab(event, 'payment')">Thanh toán (VNPay)</button>
            <button type="button" class="tab-btn" onclick="openTab(event, 'mail')">Email (SMTP)</button>
        </div>

        <div class="tab-content" id="general" style="display: block;">
            <h3>Cấu hình chung</h3>
            <div class="form-group" style="margin-top:20px;">
                <label>Tên cửa hàng</label>
                <input type="text" name="store_name" class="form-control" value="{{ $settings['store_name'] ?? 'Orlis Privé' }}">
            </div>
            <div class="form-group">
                <label>Email liên hệ</label>
                <input type="email" name="contact_email" class="form-control" value="{{ $settings['contact_email'] ?? 'contact@orlis.com' }}">
            </div>
            <div class="form-group">
                <label>Hotline</label>
                <input type="text" name="contact_phone" class="form-control" value="{{ $settings['contact_phone'] ?? '1800 6868' }}">
            </div>
        </div>

        <div class="tab-content" id="payment" style="display: none;">
            <h3>Cổng thanh toán VNPay</h3>
            <div class="form-group" style="margin-top:20px;">
                <label>VNP_TMNCODE</label>
                <input type="text" name="vnpay_tmncode" class="form-control" value="{{ $settings['vnpay_tmncode'] ?? env('VNP_TMNCODE') }}">
            </div>
            <div class="form-group">
                <label>VNP_HASHSECRET</label>
                <input type="text" name="vnpay_hashsecret" class="form-control" value="{{ $settings['vnpay_hashsecret'] ?? env('VNP_HASHSECRET') }}">
            </div>
            <div class="form-group">
                <label>VNP_URL</label>
                <input type="text" name="vnpay_url" class="form-control" value="{{ $settings['vnpay_url'] ?? env('VNP_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html') }}">
            </div>
        </div>

        <div class="tab-content" id="mail" style="display: none;">
            <h3>Cấu hình gửi Mail (SMTP)</h3>
            <div class="form-group" style="margin-top:20px;">
                <label>MAIL_MAILER</label>
                <input type="text" name="mail_mailer" class="form-control" value="{{ $settings['mail_mailer'] ?? env('MAIL_MAILER', 'smtp') }}">
            </div>
            <div class="form-group">
                <label>MAIL_HOST</label>
                <input type="text" name="mail_host" class="form-control" value="{{ $settings['mail_host'] ?? env('MAIL_HOST') }}">
            </div>
            <div class="form-group">
                <label>MAIL_PORT</label>
                <input type="text" name="mail_port" class="form-control" value="{{ $settings['mail_port'] ?? env('MAIL_PORT', 587) }}">
            </div>
            <div class="form-group">
                <label>MAIL_USERNAME</label>
                <input type="text" name="mail_username" class="form-control" value="{{ $settings['mail_username'] ?? env('MAIL_USERNAME') }}">
            </div>
            <div class="form-group">
                <label>MAIL_PASSWORD</label>
                <input type="password" name="mail_password" class="form-control" value="{{ $settings['mail_password'] ?? env('MAIL_PASSWORD') }}">
            </div>
        </div>

        <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
            <button type="submit" class="btn btn-primary">Lưu Cấu Hình</button>
        </div>
    </form>
</div>

<style>
.tabs { display: flex; border-bottom: 1px solid #eee; margin-bottom: 20px; }
.tab-btn { background: none; border: none; padding: 12px 24px; font-weight: 600; cursor: pointer; color: #666; font-size: 14px; border-bottom: 2px solid transparent; }
.tab-btn.active { color: #111; border-bottom-color: #111; }
.tab-btn:hover:not(.active) { color: #111; }
.tab-content { animation: fadeIn 0.3s; }
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>

<script>
function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tab-btn");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}
</script>
@endsection
