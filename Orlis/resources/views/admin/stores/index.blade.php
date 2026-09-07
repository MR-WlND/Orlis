@extends('layouts.admin')

@section('title', 'Quản lý Showroom')

@section('content')
<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <div class="header-text">
        <h2 class="page-title">Quản Lý Showroom</h2>
        <p class="page-subtitle">Danh sách các cửa hàng và địa điểm phục vụ khách hàng.</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.stores.create') }}" class="btn-submit" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center; height: 42px; line-height: 1;">+ THÊM SHOWROOM MỚI</a>
    </div>
</div>

@if(session('success'))
<div style="padding: 15px 20px; background: #fff; border: 1px solid #000; color: #000; margin-bottom: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div style="padding: 15px 20px; background: #fff; border: 1px solid #d93025; color: #d93025; margin-bottom: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
    {{ session('error') }}
</div>
@endif

<div class="table-container">
    <table class="luxury-table">
        <thead>
            <tr>
                <th>TÊN SHOWROOM</th>
                <th>ĐỊA CHỈ</th>
                <th>SỐ ĐIỆN THOẠI</th>
                <th>GIỜ MỞ CỬA</th>
                <th style="text-align: center;">SỐ LỊCH HẸN</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stores as $store)
            <tr onclick="window.location.href='{{ route('admin.stores.edit', $store) }}'" style="cursor: pointer;" onmouseover="this.style.backgroundColor='#fafafa'" onmouseout="this.style.backgroundColor='transparent'">
                <td><span style="font-weight: 600; color: #111; font-size: 13px;">{{ $store->name }}</span></td>
                <td><span style="font-size: 13px; color: #666;">{{ $store->address }}</span></td>
                <td><span style="font-size: 13px; font-weight: 500;">{{ $store->phone ?? '—' }}</span></td>
                <td><span style="font-size: 13px;">{{ $store->opening_hours ?? '—' }}</span></td>
                <td style="text-align: center;"><span class="status-badge" style="background: #eee; color: #111; border: none;">{{ $store->appointments_count }}</span></td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px; color: #999;">Không có showroom nào trong hệ thống.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($stores->hasPages())
<div style="margin-top: 20px;">
    {{ $stores->links('vendor.pagination.admin') }}
</div>
@endif

@endsection
