@extends('layouts.admin')

@section('title', 'Telesales / POS')

@section('content')
<div class="header-container" style="margin-bottom: 20px;">
    <h1 class="page-title">Telesales / Tạo Đơn Mới</h1>
</div>

<div class="pos-container">
    <!-- Left Column: Products -->
    <div class="pos-left card">
        <div class="search-box">
            <input type="text" id="productSearch" class="form-control" placeholder="Tìm kiếm sản phẩm (Tên, SKU)..." autocomplete="off">
            <div id="productResults" class="search-results"></div>
        </div>

        <table class="table" style="margin-top: 20px;">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th width="100">Đơn giá</th>
                    <th width="100">Số lượng</th>
                    <th width="100">Thành tiền</th>
                    <th width="50"></th>
                </tr>
            </thead>
            <tbody id="cartItems">
                <!-- Items will be injected here -->
            </tbody>
        </table>
    </div>

    <!-- Right Column: Customer & Checkout -->
    <div class="pos-right card">
        <h3>Thông tin khách hàng</h3>
        <div class="search-box" style="margin-bottom: 15px;">
            <input type="text" id="userSearch" class="form-control" placeholder="Tìm khách hàng (Tên, SĐT)..." autocomplete="off">
            <div id="userResults" class="search-results"></div>
        </div>
        
        <div id="selectedUser" style="display:none; background:#f9f9f9; padding:15px; border:1px solid #eee; margin-bottom: 20px;">
            <input type="hidden" id="userId">
            <strong id="userName"></strong><br>
            <span id="userPhone" style="color:#666; font-size:13px;"></span><br>
            <button type="button" onclick="clearUser()" class="btn-link" style="color:red; font-size:12px; margin-top:5px; border:none; background:none; padding:0; cursor:pointer;">Xóa chọn</button>
        </div>

        <div class="form-group">
            <label>Tên người nhận (Tùy chọn)</label>
            <input type="text" id="customerName" class="form-control">
        </div>
        <div class="form-group">
            <label>SĐT người nhận (Tùy chọn)</label>
            <input type="text" id="customerPhone" class="form-control">
        </div>
        <div class="form-group">
            <label>Địa chỉ giao hàng</label>
            <textarea id="shippingAddress" class="form-control" rows="2">Nhận tại cửa hàng</textarea>
        </div>

        <hr style="margin: 20px 0; border:0; border-top:1px solid #eee;">
        
        <div class="summary-row">
            <span>Tạm tính</span>
            <strong id="subtotal">0 ₫</strong>
        </div>
        <div class="summary-row form-inline-row">
            <span>Giảm giá</span>
            <input type="number" id="discount" class="form-control" style="width:100px; text-align:right;" value="0" min="0" onchange="calculateTotal()">
        </div>
        <div class="summary-row form-inline-row">
            <span>Phí giao hàng</span>
            <input type="number" id="shippingFee" class="form-control" style="width:100px; text-align:right;" value="0" min="0" onchange="calculateTotal()">
        </div>
        
        <div class="summary-row grand-total">
            <span>TỔNG CỘNG</span>
            <strong id="grandTotal" style="color:#c0392b; font-size:18px;">0 ₫</strong>
        </div>

        <button type="button" class="btn btn-primary" style="width:100%; padding:15px; font-size:14px;" onclick="submitOrder()">TẠO ĐƠN HÀNG</button>
    </div>
</div>

<style>
.pos-container { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; }
.search-box { position: relative; }
.search-results { position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #ddd; border-top: none; max-height: 200px; overflow-y: auto; z-index: 100; display: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
.search-item { padding: 10px 15px; cursor: pointer; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
.search-item:hover { background: #f9f9f9; }
.summary-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; font-size: 14px; }
.form-inline-row input { padding: 4px 8px; height: 30px; }
.grand-total { border-top: 2px solid #eee; padding-top: 15px; margin-top: 15px; }
</style>

<script>
let cart = [];

// Format currency
const formatMoney = (amount) => {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
};

// Search Products
document.getElementById('productSearch').addEventListener('input', function(e) {
    let q = e.target.value;
    if(q.length < 2) { document.getElementById('productResults').style.display = 'none'; return; }
    
    fetch(`/admin/pos/search-products?q=${q}`)
        .then(r => r.json())
        .then(data => {
            let html = '';
            data.forEach(p => {
                html += `<div class="search-item" onclick="addToCart(${p.id}, '${p.name.replace(/'/g, "\\'")}', ${p.price})">
                    <span>${p.name} <small style="color:red">(Tồn: ${p.stock})</small></span>
                    <strong>${formatMoney(p.price)}</strong>
                </div>`;
            });
            document.getElementById('productResults').innerHTML = html;
            document.getElementById('productResults').style.display = data.length ? 'block' : 'none';
        });
});

// Search Users
document.getElementById('userSearch').addEventListener('input', function(e) {
    let q = e.target.value;
    if(q.length < 2) { document.getElementById('userResults').style.display = 'none'; return; }
    
    fetch(`/admin/pos/search-users?q=${q}`)
        .then(r => r.json())
        .then(data => {
            let html = '';
            data.forEach(u => {
                html += `<div class="search-item" onclick="selectUser(${u.id}, '${u.name.replace(/'/g, "\\'")}', '${u.phone || ''}')">
                    <span>${u.name} - ${u.phone || 'No phone'}</span>
                </div>`;
            });
            document.getElementById('userResults').innerHTML = html;
            document.getElementById('userResults').style.display = data.length ? 'block' : 'none';
        });
});

// Close dropdowns on click outside
document.addEventListener('click', function(e) {
    if(!e.target.closest('.search-box')) {
        document.getElementById('productResults').style.display = 'none';
        document.getElementById('userResults').style.display = 'none';
    }
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
    document.getElementById('userSearch').value = '';
}

function clearUser() {
    document.getElementById('userId').value = '';
    document.getElementById('selectedUser').style.display = 'none';
    document.getElementById('userSearch').style.display = 'block';
}

function addToCart(id, name, price) {
    let existing = cart.find(i => i.id === id);
    if(existing) {
        existing.quantity++;
    } else {
        cart.push({id, name, price, quantity: 1});
    }
    document.getElementById('productSearch').value = '';
    document.getElementById('productResults').style.display = 'none';
    renderCart();
}

function updateQty(id, qty) {
    let item = cart.find(i => i.id === id);
    if(item) {
        item.quantity = parseInt(qty);
        if(item.quantity < 1) item.quantity = 1;
    }
    renderCart();
}

function removeFromCart(id) {
    cart = cart.filter(i => i.id !== id);
    renderCart();
}

function renderCart() {
    let html = '';
    let sub = 0;
    cart.forEach(item => {
        let total = item.price * item.quantity;
        sub += total;
        html += `<tr>
            <td>${item.name}</td>
            <td>${formatMoney(item.price)}</td>
            <td><input type="number" class="form-control" style="width:60px;height:30px;padding:2px 5px;" value="${item.quantity}" min="1" onchange="updateQty(${item.id}, this.value)"></td>
            <td><strong>${formatMoney(total)}</strong></td>
            <td><button type="button" class="btn-link" style="color:red;border:none;background:none;" onclick="removeFromCart(${item.id})">X</button></td>
        </tr>`;
    });
    if(cart.length === 0) {
        html = '<tr><td colspan="5" style="text-align:center;color:#999;padding:20px;">Chưa có sản phẩm nào</td></tr>';
    }
    document.getElementById('cartItems').innerHTML = html;
    document.getElementById('subtotal').innerText = formatMoney(sub);
    document.getElementById('subtotal').dataset.val = sub;
    calculateTotal();
}

function calculateTotal() {
    let sub = parseInt(document.getElementById('subtotal').dataset.val || 0);
    let discount = parseInt(document.getElementById('discount').value || 0);
    let shipping = parseInt(document.getElementById('shippingFee').value || 0);
    let grand = sub + shipping - discount;
    document.getElementById('grandTotal').innerText = formatMoney(grand);
}

function submitOrder() {
    if(cart.length === 0) return alert('Vui lòng thêm sản phẩm vào đơn hàng!');
    let userId = document.getElementById('userId').value;
    if(!userId) return alert('Vui lòng chọn khách hàng!');
    
    let payload = {
        _token: '{{ csrf_token() }}',
        user_id: userId,
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
        if(res.success) {
            alert('Tạo đơn hàng thành công!');
            window.location.href = '/admin/orders/' + res.order_id;
        } else {
            alert('Lỗi: ' + res.message);
        }
    })
    .catch(err => alert('Đã có lỗi xảy ra.'));
}
</script>
@endsection
