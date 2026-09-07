@extends('layouts.admin')
@section('title', 'Chi tiết lịch hẹn ' . $appointment->appointment_code)
@section('page-style')

@endsection
@section('content')
@php
$statusColors = ['pending' => '#faad14', 'confirmed' => '#1890ff', 'cancelled' => '#f5222d', 'completed' => '#52c41a'];
$statusLabels = ['pending' => 'Chờ xác nhận', 'confirmed' => 'Đã xác nhận', 'cancelled' => 'Đã hủy', 'completed' => 'Hoàn thành'];
$serviceLabels = ['consultation' => 'Tư vấn Nước hoa', 'trial' => 'Thử Mùi Hương', 'vip_service' => 'VIP Experience ✨'];
@endphp

<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.appointments.index') }}" style="color: #666; text-decoration: none; font-size: 13px;">&larr; Quay lại danh sách</a>
</div>

<div class="page-header">
    <div class="header-text">
        <h2 class="page-title">Lịch Hẹn {{ $appointment->appointment_code }}</h2>
        <p class="page-subtitle">Đặt lúc {{ $appointment->created_at->format('H:i, d/m/Y') }}</p>
    </div>
    <div class="header-actions">
        @if($appointment->status == 'pending') <span class="status-badge" style="background: #fff; color: #d93025; border-color: #d93025; padding: 6px 16px; font-size: 13px;">{{ $statusLabels[$appointment->status] ?? $appointment->status }}</span>
        @elseif($appointment->status == 'confirmed') <span class="status-badge" style="padding: 6px 16px; font-size: 13px;">{{ $statusLabels[$appointment->status] ?? $appointment->status }}</span>
        @else <span class="status-badge inactive" style="padding: 6px 16px; font-size: 13px;">{{ $statusLabels[$appointment->status] ?? $appointment->status }}</span> @endif
    </div>
</div>

<div class="edit-grid">
    {{-- Cột trái --}}
    <div>
        <div class="form-card" style="padding: 30px; margin-bottom: 20px;">
            <h3 style="font-family: var(--font-sans); font-size: 14px; font-weight: 600; margin: 0 0 20px 0; padding-bottom: 15px; border-bottom: 1px solid #eee; text-transform: uppercase;">THÔNG TIN LỊCH HẸN</h3>
            
            <div style="display: flex; flex-direction: column; gap: 15px; font-size: 13px;">
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #888;">Showroom:</span>
                    <span style="color: #111; font-weight: 500;">{{ $appointment->store?->name }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #888;">Địa chỉ:</span>
                    <span style="color: #111; text-align: right; max-width: 60%;">{{ $appointment->store?->address }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #888;">Ngày hẹn:</span>
                    <span style="color: #111;">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #888;">Khung giờ:</span>
                    <span style="color: #111;">{{ $appointment->time_slot }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #888;">Loại dịch vụ:</span>
                    <span style="color: #111; font-weight: 500;">{{ $serviceLabels[$appointment->service_type] ?? $appointment->service_type }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #888;">Nhân viên phụ trách:</span>
                    <span style="color: #111;">{{ $appointment->staff?->name ?? 'Chưa phân công' }}</span>
                </div>
                
                @if($appointment->note)
                <div style="display: flex; justify-content: space-between; border-top: 1px dashed #eee; padding-top: 15px; margin-top: 5px;">
                    <span style="color: #888;">Ghi chú:</span>
                    <span style="color: #111; font-style: italic; text-align: right; max-width: 70%;">{{ $appointment->note }}</span>
                </div>
                @endif
                
                @if($appointment->cancel_reason)
                <div style="display: flex; justify-content: space-between; border-top: 1px dashed #eee; padding-top: 15px; margin-top: 5px;">
                    <span style="color: #d93025;">Lý do hủy:</span>
                    <span style="color: #d93025; font-weight: 500; text-align: right; max-width: 70%;">{{ $appointment->cancel_reason }}</span>
                </div>
                @endif
            </div>
        </div>

        <div class="form-card" style="padding: 30px;">
            <h3 style="font-family: var(--font-sans); font-size: 14px; font-weight: 600; margin: 0 0 20px 0; padding-bottom: 15px; border-bottom: 1px solid #eee; text-transform: uppercase;">THÔNG TIN KHÁCH HÀNG</h3>
            
            <div style="display: flex; flex-direction: column; gap: 15px; font-size: 13px;">
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #888;">Tên khách hàng:</span>
                    <a href="{{ route('admin.users.show', $appointment->user) }}" style="color: #111; font-weight: 600; text-decoration: underline;">{{ $appointment->user?->name }}</a>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #888;">Email:</span>
                    <span style="color: #111;">{{ $appointment->user?->email }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #888;">Số điện thoại:</span>
                    <span style="color: #111;">{{ $appointment->user?->phone ?? '—' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #888;">Hạng thành viên:</span>
                    <span style="color: #111; font-weight: 600; text-transform: uppercase;">{{ \App\Models\User::MEMBERSHIPS[$appointment->user?->membership_level] ?? 'Classic' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Cột phải --}}
    <div class="right-col">
        @if($appointment->status !== 'cancelled' && $appointment->status !== 'completed')
        <div class="form-card" style="padding: 24px; margin-bottom: 20px;">
            <h3 style="font-family: var(--font-sans); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #000; margin: 0 0 16px 0; padding-bottom: 12px; border-bottom: 1px solid #eee;">PHÂN CÔNG NHÂN VIÊN</h3>
            
            <form method="POST" action="{{ route('admin.appointments.assignStaff', $appointment) }}">
                @csrf
                @method('PATCH')
                <div class="form-group" style="margin-bottom: 15px;">
                    <select name="staff_id" class="form-control" required>
                        <option value="">-- Chọn nhân viên --</option>
                        @foreach($staffList as $staff)
                            <option value="{{ $staff->id }}" @selected($appointment->staff_id === $staff->id)>{{ $staff->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-cancel" style="width: 100%; border: 1px solid #111; color: #111; font-weight: 600;">PHÂN CÔNG & XÁC NHẬN</button>
            </form>
        </div>

        <div class="form-card" style="padding: 24px;">
            <h3 style="font-family: var(--font-sans); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #000; margin: 0 0 16px 0; padding-bottom: 12px; border-bottom: 1px solid #eee;">CẬP NHẬT TRẠNG THÁI</h3>
            
            <form method="POST" action="{{ route('admin.appointments.updateStatus', $appointment) }}">
                @csrf
                @method('PATCH')
                <div class="form-group" style="margin-bottom: 15px;">
                    <select name="status" class="form-control">
                        @foreach($statusLabels as $key => $label)
                            <option value="{{ $key }}" @selected($appointment->status === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <textarea name="note" class="form-control" rows="3" placeholder="Lý do hủy / ghi chú..."></textarea>
                </div>
                <button type="submit" class="btn-submit" style="width: 100%;">CẬP NHẬT TRẠNG THÁI</button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
