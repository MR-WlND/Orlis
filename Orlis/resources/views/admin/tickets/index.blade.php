@extends('layouts.admin')
@section('title', 'Quản Lý Tickets')
@section('content')
<div class="container-fluid p-4">
    <h2 class="mb-4">Hỗ Trợ Khách Hàng (Tickets)</h2>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="mb-3">
        <a href="{{ route('admin.tickets.index', ['status' => 'open']) }}" class="btn {{ $status == 'open' ? 'btn-primary' : 'btn-outline-primary' }}">
            Đang mở ({{ $openCount }})
        </a>
        <a href="{{ route('admin.tickets.index', ['status' => 'closed']) }}" class="btn {{ $status == 'closed' ? 'btn-secondary' : 'btn-outline-secondary' }}">
            Đã đóng ({{ $closedCount }})
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4">Mã</th>
                        <th>Khách hàng</th>
                        <th>Tiêu đề</th>
                        <th>Ưu tiên</th>
                        <th>Trạng thái</th>
                        <th>Ngày gửi</th>
                        <th class="px-4 text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                    <tr>
                        <td class="px-4">#TCK-{{ $ticket->id }}</td>
                        <td>{{ $ticket->user->name }}</td>
                        <td><strong>{{ $ticket->subject }}</strong></td>
                        <td>
                            @if($ticket->priority == 'high') <span class="badge bg-danger">Gấp</span>
                            @elseif($ticket->priority == 'normal') <span class="badge bg-secondary">Bình thường</span>
                            @else <span class="badge bg-light text-dark">Thấp</span> @endif
                        </td>
                        <td>
                            @if($ticket->status == 'open') <span class="badge bg-info text-dark">Open</span>
                            @else <span class="badge bg-dark">Closed</span> @endif
                        </td>
                        <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 text-end">
                            <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-sm btn-dark">Xem / Trả lời</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center p-4 text-muted">Không có ticket nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $tickets->links() }}
        </div>
    </div>
</div>
@endsection
