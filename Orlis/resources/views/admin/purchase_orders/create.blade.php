@extends('layouts.admin')

@section('title', 'Tạo Yêu Cầu Nhập Hàng (PO)')

@section('content')
<div class="header-container" style="margin-bottom: 20px;">
    <h1 class="page-title">Tạo Yêu Cầu Nhập Hàng (PO)</h1>
</div>

<div class="pos-container">
    <!-- Left Column: Products -->
    <div class="pos-left card">
        <div class="search-box">
            <input type="text" id="productSearch" class="form-control" placeholder="Tìm kiếm sản phẩm cần nhập..." autocomplete="off">
            <div id="productResults" class="search-results"></div>
        </div>

        <table class="table" style="margin-top: 20px;">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th width="120">Giá nhập (Dự kiến)</th>
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

    <!-- Right Column: Supplier & Info -->
    <div class="pos-right card">
        <h3>Thông tin PO</h3>
        
        <div class="form-group">
            <label>Nhà cung cấp (Supplier)</label>
            <select id="supplierId" class="form-control">
                <option value="">-- Chọn Nhà cung cấp --</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }} ({{ $supplier->email }})</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Ngày giao dự kiến</label>
            <input type="date" id="expectedDate" class="form-control" min="{{ date('Y-m-d') }}">
        </div>

        <div class="form-group">
            <label>Ghi chú</label>
            <textarea id="notes" class="form-control" rows="3"></textarea>
        </div>

        <hr style="margin: 20px 0; border:0; border-top:1px solid #eee;">
        
        <div class="summary-row grand-total">
            <span>TỔNG TIỀN NHẬP DỰ KIẾN</span>
            <strong id="grandTotal" style="color:#c0392b; font-size:18px;">0 ₫</strong>
        </div>

        <button type="button" class="btn btn-primary" style="width:100%; padding:15px; font-size:14px; margin-top:20px;" onclick="submitPO()">TẠO PURCHASE ORDER</button>
    </div>
</div>

<style>
.pos-container { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; }
.search-box { position: relative; }
.search-results { position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #ddd; border-top: none; max-height: 200px; overflow-y: auto; z-index: 100; display: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
.search-item { padding: 10px 15px; cursor: pointer; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
.search-item:hover { background: #f9f9f9; }
.summary-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; font-size: 14px; }
.grand-total { border-top: 2px solid #eee; padding-top: 15px; margin-top: 15px; }
</style>

<script>
let cart = [];

const formatMoney = (amount) => {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
};

// Re-using POS search API
document.getElementById('productSearch').addEventListener('input', function(e) {
    let q = e.target.value;
    if(q.length < 2) { document.getElementById('productResults').style.display = 'none'; return; }
    
    fetch(`/admin/pos/search-products?q=${q}`)
        .then(r => r.json())
        .then(data => {
            let html = '';
            data.forEach(p => {
                html += `<div class="search-item" onclick="addToCart(${p.id}, '${p.name.replace(/'/g, "\\'")}', 0)">
                    <span>${p.name} <small style="color:red">(Hiện còn: ${p.stock})</small></span>
                </div>`;
            });
            document.getElementById('productResults').innerHTML = html;
            document.getElementById('productResults').style.display = data.length ? 'block' : 'none';
        });
});

document.addEventListener('click', function(e) {
    if(!e.target.closest('.search-box')) {
        document.getElementById('productResults').style.display = 'none';
    }
});

function addToCart(id, name, price) {
    let existing = cart.find(i => i.id === id);
    if(existing) {
        existing.quantity++;
    } else {
        cart.push({id, name, unit_price: 0, quantity: 1}); // default cost is 0
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

function updatePrice(id, price) {
    let item = cart.find(i => i.id === id);
    if(item) {
        item.unit_price = parseFloat(price) || 0;
    }
    renderCart();
}

function removeFromCart(id) {
    cart = cart.filter(i => i.id !== id);
    renderCart();
}

function renderCart() {
    let html = '';
    let totalAmount = 0;
    cart.forEach(item => {
        let total = item.unit_price * item.quantity;
        totalAmount += total;
        html += `<tr>
            <td>${item.name}</td>
            <td><input type="number" class="form-control" style="width:100px;height:30px;padding:2px 5px;" value="${item.unit_price}" min="0" onchange="updatePrice(${item.id}, this.value)"></td>
            <td><input type="number" class="form-control" style="width:60px;height:30px;padding:2px 5px;" value="${item.quantity}" min="1" onchange="updateQty(${item.id}, this.value)"></td>
            <td><strong>${formatMoney(total)}</strong></td>
            <td><button type="button" class="btn-link" style="color:red;border:none;background:none;" onclick="removeFromCart(${item.id})">X</button></td>
        </tr>`;
    });
    if(cart.length === 0) {
        html = '<tr><td colspan="5" style="text-align:center;color:#999;padding:20px;">Chưa có sản phẩm nào</td></tr>';
    }
    document.getElementById('cartItems').innerHTML = html;
    document.getElementById('grandTotal').innerText = formatMoney(totalAmount);
}

function submitPO() {
    if(cart.length === 0) return alert('Vui lòng thêm sản phẩm!');
    let supplierId = document.getElementById('supplierId').value;
    if(!supplierId) return alert('Vui lòng chọn Nhà cung cấp!');
    let expectedDate = document.getElementById('expectedDate').value;
    if(!expectedDate) return alert('Vui lòng chọn ngày giao dự kiến!');
    
    let payload = {
        _token: '{{ csrf_token() }}',
        supplier_id: supplierId,
        expected_delivery_date: expectedDate,
        notes: document.getElementById('notes').value,
        items: cart
    };
    
    fetch('{{ route('admin.purchase_orders.store') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(res => {
        if(res.success) {
            alert('Tạo Purchase Order thành công!');
            window.location.reload();
        } else {
            alert('Lỗi: ' + res.message);
        }
    })
    .catch(err => alert('Đã có lỗi xảy ra.'));
}
</script>
@endsection
