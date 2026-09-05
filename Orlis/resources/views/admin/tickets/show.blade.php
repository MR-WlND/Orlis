@extends('layouts.admin')
@section('title', 'Ticket #' . $ticket->id)
@section('content')
<div class="container-fluid p-4">
    <div class="mb-3">
        <a href="{{ route('admin.tickets.index') }}" class="text-decoration-none text-muted">&larr; Quay lại danh sách</a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $ticket->subject }}</h5>
                    @if($ticket->status == 'open')
                        <span class="badge bg-info text-dark">Open</span>
                    @else
                        <span class="badge bg-dark">Closed</span>
                    @endif
                </div>
                <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                    <div class="d-flex flex-column gap-3">
                        @foreach($ticket->replies as $reply)
                            <div class="d-flex gap-3 {{ $reply->user->role == 'admin' ? 'flex-row-reverse' : '' }}">
                                <img src="{{ $reply->user->avatar ? Storage::url($reply->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($reply->user->name).'&background=random' }}" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%;">
                                <div class="p-3 rounded" style="max-width: 80%; background: {{ $reply->user->role == 'admin' ? '#e6f7ff' : '#f8f9fa' }}; border: 1px solid {{ $reply->user->role == 'admin' ? '#91d5ff' : '#e9ecef' }};">
                                    <div class="d-flex justify-content-between mb-1" style="font-size: 12px; color: #666;">
                                        <strong>{{ $reply->user->role == 'admin' ? 'Bạn (Admin)' : $reply->user->name }}</strong>
                                        <span class="ms-3">{{ $reply->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div style="font-size: 14px; white-space: pre-wrap;">{{ $reply->message }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Phản hồi khách hàng</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.tickets.reply', $ticket) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <textarea name="message" rows="4" class="form-control" required placeholder="Nhập nội dung phản hồi..."></textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-dark">Gửi phản hồi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-white"><h6 class="mb-0">Thông tin Ticket</h6></div>
                <ul class="list-group list-group-flush text-sm">
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Khách hàng:</span>
                        <strong>{{ $ticket->user->name }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Email:</span>
                        <span>{{ $ticket->user->email }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Độ ưu tiên:</span>
                        <span>
                            @if($ticket->priority == 'high') <span class="badge bg-danger">Gấp</span>
                            @elseif($ticket->priority == 'normal') <span class="badge bg-secondary">Bình thường</span>
                            @else <span class="badge bg-light text-dark">Thấp</span> @endif
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Ngày tạo:</span>
                        <span>{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
                    </li>
                </ul>
                <div class="card-footer bg-white text-center">
                    @if($ticket->status == 'open')
                    <form action="{{ route('admin.tickets.close', $ticket) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">Đóng Ticket</button>
                    </form>
                    @else
                    <span class="text-muted small">Ticket đã đóng</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
