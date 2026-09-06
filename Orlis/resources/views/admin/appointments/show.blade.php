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

<a href="{{ route('admin.appointments.index') }}" class="back-link">← Quay lại danh sách</a>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-error">{{ session('error') }}</div>@endif

<div class="page-head">
    <div>
        <div class="apt-code">{{ $appointment->appointment_code }}</div>
        <div class="apt-date">Đặt lúc {{ $appointment->created_at->format('H:i, d/m/Y') }}</div>
    </div>
    @php $color = $statusColors[$appointment->status] ?? '#999'; @endphp
    <span class="status-badge" style="color:{{ $color }};background:{{ $color }}22;">
        {{ $statusLabels[$appointment->status] ?? $appointment->status }}
    </span>
</div>

<div class="grid">
    {{-- LEFT --}}
    <div>
        <div class="card">
            <div class="card-title">Thông tin lịch hẹn</div>
            <div class="info-row"><span class="info-label">Showroom</span><span class="info-val">{{ $appointment->store?->name }}</span></div>
            <div class="info-row"><span class="info-label">Địa chỉ</span><span class="info-val">{{ $appointment->store?->address }}</span></div>
            <div class="info-row"><span class="info-label">Ngày hẹn</span><span class="info-val">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</span></div>
            <div class="info-row"><span class="info-label">Khung giờ</span><span class="info-val">{{ $appointment->time_slot }}</span></div>
            <div class="info-row"><span class="info-label">Loại dịch vụ</span><span class="info-val">{{ $serviceLabels[$appointment->service_type] ?? $appointment->service_type }}</span></div>
            <div class="info-row"><span class="info-label">Nhân viên phụ trách</span><span class="info-val">{{ $appointment->staff?->name ?? 'Chưa phân công' }}</span></div>
            @if($appointment->note)
            <div class="info-row"><span class="info-label">Ghi chú</span><span class="info-val" style="font-style:italic;">{{ $appointment->note }}</span></div>
            @endif
            @if($appointment->cancel_reason)
            <div class="info-row"><span class="info-label">Lý do hủy</span><span class="info-val" style="color:#c0392b;">{{ $appointment->cancel_reason }}</span></div>
            @endif
        </div>

        <div class="card">
            <div class="card-title">Thông tin khách hàng</div>
            <div class="info-row"><span class="info-label">Tên</span>
                <a href="{{ route('admin.users.edit', $appointment->user) }}" style="color:var(--accent);text-decoration:none;font-weight:500;">{{ $appointment->user?->name }}</a>
            </div>
            <div class="info-row"><span class="info-label">Email</span><span class="info-val">{{ $appointment->user?->email }}</span></div>
            <div class="info-row"><span class="info-label">Số điện thoại</span><span class="info-val">{{ $appointment->user?->phone ?? '—' }}</span></div>
            <div class="info-row"><span class="info-label">Hạng thành viên</span>
                <span class="info-val">{{ \App\Models\User::MEMBERSHIPS[$appointment->user?->membership_level] ?? 'Classic' }}</span>
            </div>
        </div>
    </div>

    {{-- RIGHT --}}
    <div>
        {{-- Phân công nhân viên --}}
        @if($appointment->status !== 'cancelled' && $appointment->status !== 'completed')
        <div class="card">
            <div class="card-title">Phân công nhân viên</div>
            <form method="POST" action="{{ route('admin.appointments.assignStaff', $appointment) }}">
                @csrf
                @method('PATCH')
                <select name="staff_id" class="form-select" required>
                    <option value="">-- Chọn nhân viên --</option>
                    @foreach($staffList as $staff)
                        <option value="{{ $staff->id }}" @selected($appointment->staff_id === $staff->id)>{{ $staff->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-secondary">Phân công & Xác nhận</button>
            </form>
        </div>

        <div class="card">
            <div class="card-title">Cập nhật trạng thái</div>
            <form method="POST" action="{{ route('admin.appointments.updateStatus', $appointment) }}">
                @csrf
                @method('PATCH')
                <select name="status" class="form-select">
                    @foreach($statusLabels as $key => $label)
                        <option value="{{ $key }}" @selected($appointment->status === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <textarea name="note" class="form-textarea" rows="2" placeholder="Lý do hủy / ghi chú..."></textarea>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
