@extends('layouts.admin')
@section('title', 'Telesales / Tạo Đơn Mới')

@section('content')

<div class="page-header" style="margin-bottom: 30px;">
    <div class="header-text">
        <h2 class="page-title">Telesales / Tạo Đơn Mới (POS)</h2>
        <p class="page-subtitle">Tạo đơn hàng trực tiếp tại cửa hàng hoặc qua điện thoại.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start;">

    <!-- Left Column: Products -->
    <div>
        <div class="form-card" style="padding: 24px; margin-bottom: 0;">
            <h3 style="font-family: var(--font-sans); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: #999; margin: 0 0 20px 0;">SẢN PHẨM</h3>
            <div style="position: relative; margin-bottom: 20px;">
                <input type="text" id="productSearch" class="form-control" placeholder="Tìm kiếm sản phẩm (Tên, SKU)..." autocomplete="off" style="background: transparent;">
                <div id="productResults" style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #ddd; border-top: none; max-height: 220px; overflow-y: auto; z-index: 100; display: none; box-shadow: 0 8px 24px rgba(0,0,0,0.08);"></div>
            </div>

            <table class="luxury-table" style="margin-top: 0;">
                <thead>
                    <tr>
                        <th>SẢN PHẨM</th>
                        <th style="text-align: right;">ĐƠN GIÁ</th>
                        <th style="text-align: center; width: 90px;">SỐ LƯỢNG</th>
                        <th style="text-align: right;">THÀNH TIỀN</th>
                        <th style="width: 40px;"></th>
                    </tr>
                </thead>
                <tbody id="cartItems">
                    <tr><td colspan="5" style="text-align: center; padding: 30px; color: #bbb; font-size: 13px;">Chưa có sản phẩm nào. Tìm kiếm ở trên để thêm.</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right Column: Customer & Checkout -->
    <div>
        <div class="form-card" style="padding: 24px; margin-bottom: 16px;">
            <h3 style="font-family: var(--font-sans); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: #999; margin: 0 0 20px 0;">KHÁCH HÀNG</h3>
            <div style="position: relative; margin-bottom: 15px;">
                <input type="text" id="userSearch" class="form-control" placeholder="Tìm khách hàng (Tên, SĐT)..." autocomplete="off" style="background: transparent;">
                <div id="userResults" style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #ddd; border-top: none; max-height: 200px; overflow-y: auto; z-index: 100; display: none; box-shadow: 0 8px 24px rgba(0,0,0,0.08);"></div>
            </div>

            <div id="selectedUser" style="display: none; background: #f9f9f9; padding: 14px; border: 1px solid #eee; margin-bottom: 15px;">
                <input type="hidden" id="userId">
                <strong id="userName" style="font-size: 13px; color: #111;"></strong><br>
                <span id="userPhone" style="color: #666; font-size: 12px;"></span><br>
                <button type="button" onclick="clearUser()" style="background: none; border: none; color: #d93025; font-size: 11px; font-weight: 600; margin-top: 8px; cursor: pointer; padding: 0; text-decoration: underline;">Xóa chọn</button>
            </div>

            <div class="form-group" style="margin-bottom: 12px;">
                <label style="font-size: 11px; font-weight: 600; text-transform: uppercase; color: #888; display: block; margin-bottom: 6px;">Tên người nhận</label>
                <input type="text" id="customerName" class="form-control" style="background: transparent;">
            </div>
            <div class="form-group" style="margin-bottom: 12px;">
                <label style="font-size: 11px; font-weight: 600; text-transform: uppercase; color: #888; display: block; margin-bottom: 6px;">SĐT người nhận</label>
                <input type="text" id="customerPhone" class="form-control" style="background: transparent;">
            </div>
            <div class="form-group">
                <label style="font-size: 11px; font-weight: 600; text-transform: uppercase; color: #888; display: block; margin-bottom: 6px;">Địa chỉ giao hàng</label>
                <textarea id="shippingAddress" class="form-control" rows="2" style="background: transparent; resize: none;">Nhận tại cửa hàng</textarea>
            </div>
        </div>

        <div class="form-card" style="padding: 24px;">
            <h3 style="font-family: var(--font-sans); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: #999; margin: 0 0 20px 0;">THANH TOÁN</h3>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; font-size: 13px;">
                <span style="color: #666;">Tạm tính</span>
                <strong id="subtotal" style="font-family: var(--font-sans);">0 ₫</strong>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; font-size: 13px;">
                <span style="color: #666;">Giảm giá</span>
                <input type="number" id="discount" class="form-control" style="width: 110px; text-align: right; background: transparent; height: 34px; padding: 0 8px;" value="0" min="0" onchange="calculateTotal()">
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; font-size: 13px;">
                <span style="color: #666;">Phí giao hàng</span>
                <input type="number" id="shippingFee" class="form-control" style="width: 110px; text-align: right; background: transparent; height: 34px; padding: 0 8px;" value="0" min="0" onchange="calculateTotal()">
            </div>

            <div style="border-top: 1px solid #eee; padding-top: 16px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Tổng cộng</span>
                <strong id="grandTotal" style="font-size: 22px; font-family: var(--font-serif); color: #111;">0 ₫</strong>
            </div>

            <button type="button" class="btn-submit" style="width: 100%; height: 48px; font-size: 13px; cursor: pointer;" onclick="submitOrder()">TẠO ĐƠN HÀNG</button>
        </div>
    </div>
</div>

<style>
.search-item { padding: 12px 16px; cursor: pointer; border-bottom: 1px solid #f5f5f5; display: flex; justify-content: space-between; align-items: center; font-size: 13px; }
.search-item:hover { background: #fafafa; }
</style>

<script>
let cart = [];

const formatMoney = (amount) => new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);

document.getElementById('productSearch').addEventListener('input', function(e) {
    let q = e.target.value;
    if(q.length < 2) { document.getElementById('productResults').style.display = 'none'; return; }
    fetch(`/admin/pos/search-products?q=${q}`)
        .then(r => r.json())
        .then(data => {
            let html = data.map(p => `<div class="search-item" onclick="addToCart(${p.id}, '${p.name.replace(/'/g, "\\'")}', ${p.price})"><span>${p.name} <small style="color:#aaa">(Tồn: ${p.stock})</small></span><strong>${formatMoney(p.price)}</strong></div>`).join('');
            document.getElementById('productResults').innerHTML = html || '<div style="padding:12px 16px; color:#999; font-size:13px;">Không tìm thấy</div>';
            document.getElementById('productResults').style.display = 'block';
        });
});

document.getElementById('userSearch').addEventListener('input', function(e) {
    let q = e.target.value;
    if(q.length < 2) { document.getElementById('userResults').style.display = 'none'; return; }
    fetch(`/admin/pos/search-users?q=${q}`)
        .then(r => r.json())
        .then(data => {
            let html = data.map(u => `<div class="search-item" onclick="selectUser(${u.id}, '${u.name.replace(/'/g, "\\'")}', '${u.phone || ''}')"><span>${u.name}</span><small style="color:#999">${u.phone || ''}</small></div>`).join('');
            document.getElementById('userResults').innerHTML = html || '<div style="padding:12px 16px; color:#999; font-size:13px;">Không tìm thấy</div>';
            document.getElementById('userResults').style.display = 'block';
        });
});

document.addEventListener('click', function(e) {
    if(!e.target.closest('[id="productSearch"]') && !e.target.closest('#productResults')) document.getElementById('productResults').style.display = 'none';
    if(!e.target.closest('[id="userSearch"]') && !e.target.closest('#userResults')) document.getElementById('userResults').style.display = 'none';
});

function selectUser(id, name, phone) {
    document.getElementById('userId').value = id;
    document.getElementById('userName').innerText = name;
    document.getElementById('userPhone').innerText = phone;
    document.getElementById('customerName').value = name;
    document.getElementById('customerPhone').value = phone;
    document.getElementById('selectedUser').style.display = 'block';
    document.getElementById('userSearch').style.display = 'none';
    document.getElementById('userResults').style.display = 'none';
}

function clearUser() {
    document.getElementById('userId').value = '';
    document.getElementById('selectedUser').style.display = 'none';
    document.getElementById('userSearch').style.display = 'block';
}

function addToCart(id, name, price) {
    let existing = cart.find(i => i.id === id);
    if(existing) { existing.quantity++; } else { cart.push({id, name, price, quantity: 1}); }
    document.getElementById('productSearch').value = '';
    document.getElementById('productResults').style.display = 'none';
    renderCart();
}

function updateQty(id, qty) {
    let item = cart.find(i => i.id === id);
    if(item) { item.quantity = Math.max(1, parseInt(qty)); }
    renderCart();
}

function removeFromCart(id) {
    cart = cart.filter(i => i.id !== id);
    renderCart();
}

function renderCart() {
    let html = '', sub = 0;
    cart.forEach(item => {
        let total = item.price * item.quantity; sub += total;
        html += `<tr>
            <td style="font-size:13px; font-weight:600;">${item.name}</td>
            <td style="text-align:right; font-size:13px;">${formatMoney(item.price)}</td>
            <td style="text-align:center;"><input type="number" style="width:60px; height:30px; padding:2px 6px; border:1px solid #eee; text-align:center; font-size:13px;" value="${item.quantity}" min="1" onchange="updateQty(${item.id}, this.value)"></td>
            <td style="text-align:right; font-weight:700; font-size:13px;">${formatMoney(total)}</td>
            <td style="text-align:center;"><button type="button" onclick="removeFromCart(${item.id})" style="background:none; border:none; color:#d93025; font-size:16px; cursor:pointer; padding:0; line-height:1;">×</button></td>
        </tr>`;
    });
    if(!cart.length) html = '<tr><td colspan="5" style="text-align:center;padding:30px;color:#bbb;font-size:13px;">Chưa có sản phẩm nào. Tìm kiếm ở trên để thêm.</td></tr>';
    document.getElementById('cartItems').innerHTML = html;
    document.getElementById('subtotal').innerText = formatMoney(sub);
    document.getElementById('subtotal').dataset.val = sub;
    calculateTotal();
}

function calculateTotal() {
    let sub = parseInt(document.getElementById('subtotal').dataset.val || 0);
    let discount = parseInt(document.getElementById('discount').value || 0);
    let shipping = parseInt(document.getElementById('shippingFee').value || 0);
    document.getElementById('grandTotal').innerText = formatMoney(sub + shipping - discount);
}

function submitOrder() {
    if(!cart.length) return alert('Vui lòng thêm sản phẩm vào đơn hàng!');
    if(!document.getElementById('userId').value) return alert('Vui lòng chọn khách hàng!');
    let payload = {
        _token: '{{ csrf_token() }}',
        user_id: document.getElementById('userId').value,
        customer_name: document.getElementById('customerName').value,
        customer_phone: document.getElementById('customerPhone').value,
        shipping_address: document.getElementById('shippingAddress').value,
        discount: document.getElementById('discount').value,
        shipping_fee: document.getElementById('shippingFee').value,
        items: cart
    };
    fetch('{{ route('admin.pos.store') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(res => {
        if(res.success) { window.location.href = '/admin/orders/' + res.order_id; }
        else { alert('Lỗi: ' + res.message); }
    })
    .catch(() => alert('Đã có lỗi xảy ra.'));
}
</script>

@endsection
