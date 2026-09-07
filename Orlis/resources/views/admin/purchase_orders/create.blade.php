@extends('layouts.admin')
@section('title', 'Tạo Yêu Cầu Nhập Hàng (PO)')

@section('content')

<div class="page-header" style="margin-bottom: 30px;">
    <div class="header-text">
        <h2 class="page-title">Tạo Yêu Cầu Nhập Hàng (PO)</h2>
        <p class="page-subtitle">Lập phiếu nhập hàng từ nhà cung cấp vào kho.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start;">

    <!-- Left Column: Products -->
    <div>
        <div class="form-card" style="padding: 24px; margin-bottom: 0;">
            <h3 style="font-family: var(--font-sans); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: #999; margin: 0 0 20px 0;">SẢN PHẨM CẦN NHẬP</h3>
            <div style="position: relative; margin-bottom: 20px;">
                <input type="text" id="productSearch" class="form-control" placeholder="Tìm kiếm sản phẩm cần nhập..." autocomplete="off" style="background: transparent;">
                <div id="productResults" style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #ddd; border-top: none; max-height: 220px; overflow-y: auto; z-index: 100; display: none; box-shadow: 0 8px 24px rgba(0,0,0,0.08);"></div>
            </div>

            <table class="luxury-table" style="margin-top: 0;">
                <thead>
                    <tr>
                        <th>SẢN PHẨM</th>
                        <th style="text-align: right; width: 140px;">GIÁ NHẬP (DỰ KIẾN)</th>
                        <th style="text-align: center; width: 90px;">SỐ LƯỢNG</th>
                        <th style="text-align: right; width: 120px;">THÀNH TIỀN</th>
                        <th style="width: 40px;"></th>
                    </tr>
                </thead>
                <tbody id="cartItems">
                    <tr><td colspan="5" style="text-align: center; padding: 30px; color: #bbb; font-size: 13px;">Chưa có sản phẩm nào. Tìm kiếm ở trên để thêm.</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right Column: Supplier & Info -->
    <div>
        <div class="form-card" style="padding: 24px; margin-bottom: 16px;">
            <h3 style="font-family: var(--font-sans); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: #999; margin: 0 0 20px 0;">THÔNG TIN PO</h3>

            <div class="form-group" style="margin-bottom: 16px;">
                <label style="font-size: 11px; font-weight: 600; text-transform: uppercase; color: #888; display: block; margin-bottom: 6px;">Nhà cung cấp *</label>
                <select id="supplierId" class="form-control" style="background: transparent;">
                    <option value="">-- Chọn Nhà cung cấp --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }} ({{ $supplier->email }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label style="font-size: 11px; font-weight: 600; text-transform: uppercase; color: #888; display: block; margin-bottom: 6px;">Ngày giao dự kiến *</label>
                <input type="date" id="expectedDate" class="form-control" min="{{ date('Y-m-d') }}" style="background: transparent;">
            </div>

            <div class="form-group">
                <label style="font-size: 11px; font-weight: 600; text-transform: uppercase; color: #888; display: block; margin-bottom: 6px;">Ghi chú</label>
                <textarea id="notes" class="form-control" rows="3" style="background: transparent; resize: none;"></textarea>
            </div>
        </div>

        <div class="form-card" style="padding: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid #eee;">
                <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Tổng tiền nhập dự kiến</span>
                <strong id="grandTotal" style="font-size: 22px; font-family: var(--font-serif); color: #111;">0 ₫</strong>
            </div>
            <button type="button" class="btn-submit" style="width: 100%; height: 48px; font-size: 13px; cursor: pointer;" onclick="submitPO()">TẠO PURCHASE ORDER</button>
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
            let html = data.map(p => `<div class="search-item" onclick="addToCart(${p.id}, '${p.name.replace(/'/g, "\\'")}')"><span>${p.name}</span><small style="color:#aaa">Hiện tồn: ${p.stock}</small></div>`).join('');
            document.getElementById('productResults').innerHTML = html || '<div style="padding:12px 16px; color:#999; font-size:13px;">Không tìm thấy</div>';
            document.getElementById('productResults').style.display = 'block';
        });
});

document.addEventListener('click', function(e) {
    if(!e.target.closest('[id="productSearch"]') && !e.target.closest('#productResults')) document.getElementById('productResults').style.display = 'none';
});

function addToCart(id, name) {
    let existing = cart.find(i => i.id === id);
    if(existing) { existing.quantity++; } else { cart.push({id, name, unit_price: 0, quantity: 1}); }
    document.getElementById('productSearch').value = '';
    document.getElementById('productResults').style.display = 'none';
    renderCart();
}

function updateQty(id, qty) {
    let item = cart.find(i => i.id === id);
    if(item) item.quantity = Math.max(1, parseInt(qty));
    renderCart();
}

function updatePrice(id, price) {
    let item = cart.find(i => i.id === id);
    if(item) item.unit_price = parseFloat(price) || 0;
    renderCart();
}

function removeFromCart(id) {
    cart = cart.filter(i => i.id !== id);
    renderCart();
}

function renderCart() {
    let html = '', total = 0;
    cart.forEach(item => {
        let sub = item.unit_price * item.quantity; total += sub;
        html += `<tr>
            <td style="font-size:13px; font-weight:600;">${item.name}</td>
            <td><input type="number" style="width:110px; height:30px; padding:2px 8px; border:1px solid #eee; text-align:right; font-size:13px;" value="${item.unit_price}" min="0" onchange="updatePrice(${item.id}, this.value)"></td>
            <td style="text-align:center;"><input type="number" style="width:60px; height:30px; padding:2px 6px; border:1px solid #eee; text-align:center; font-size:13px;" value="${item.quantity}" min="1" onchange="updateQty(${item.id}, this.value)"></td>
            <td style="text-align:right; font-weight:700; font-size:13px;">${formatMoney(sub)}</td>
            <td style="text-align:center;"><button type="button" onclick="removeFromCart(${item.id})" style="background:none; border:none; color:#d93025; font-size:16px; cursor:pointer; padding:0; line-height:1;">×</button></td>
        </tr>`;
    });
    if(!cart.length) html = '<tr><td colspan="5" style="text-align:center;padding:30px;color:#bbb;font-size:13px;">Chưa có sản phẩm nào. Tìm kiếm ở trên để thêm.</td></tr>';
    document.getElementById('cartItems').innerHTML = html;
    document.getElementById('grandTotal').innerText = formatMoney(total);
}

function submitPO() {
    if(!cart.length) return alert('Vui lòng thêm sản phẩm!');
    if(!document.getElementById('supplierId').value) return alert('Vui lòng chọn Nhà cung cấp!');
    if(!document.getElementById('expectedDate').value) return alert('Vui lòng chọn ngày giao dự kiến!');

    let payload = {
        _token: '{{ csrf_token() }}',
        supplier_id: document.getElementById('supplierId').value,
        expected_delivery_date: document.getElementById('expectedDate').value,
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
        if(res.success) { alert('Tạo Purchase Order thành công!'); window.location.reload(); }
        else { alert('Lỗi: ' + (res.message || 'Không xác định')); }
    })
    .catch(() => alert('Đã có lỗi xảy ra.'));
}
</script>

@endsection
