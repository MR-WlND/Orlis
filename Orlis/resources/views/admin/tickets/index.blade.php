@extends('layouts.admin')
@section('title', 'Quản Lý Tickets')
@section('content')
    <div class="page-header">
        <div class="header-text">
            <h2 class="page-title">Hỗ Trợ Khách Hàng (Tickets)</h2>
            <p class="page-subtitle">Quản lý và phản hồi các yêu cầu hỗ trợ từ khách hàng.</p>
        </div>
        <div class="header-actions">
            <!-- Optional Actions -->
        </div>
    </div>

    @if(session('success'))
        <div style="padding: 15px 20px; background: #fff; border: 1px solid #000; color: #000; margin-bottom: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="margin-bottom: 20px; display: flex; gap: 10px;">
        <a href="{{ route('admin.tickets.index', ['status' => 'open']) }}" class="btn-cancel" style="{{ $status == 'open' ? 'background: #111; color: #fff;' : '' }}">
            Đang mở ({{ $openCount }})
        </a>
        <a href="{{ route('admin.tickets.index', ['status' => 'closed']) }}" class="btn-cancel" style="{{ $status == 'closed' ? 'background: #111; color: #fff;' : '' }}">
            Đã đóng ({{ $closedCount }})
        </a>
    </div>

    <div class="table-container">
        <table class="luxury-table">
            <thead>
                <tr>
                    <th>MÃ</th>
                    <th>KHÁCH HÀNG</th>
                    <th>TIÊU ĐỀ</th>
                    <th>ƯU TIÊN</th>
                    <th>TRẠNG THÁI</th>
                    <th>NGÀY GỬI</th>
                    <th style="text-align: right;">THAO TÁC</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                <tr>
                    <td><span style="font-size: 13px; font-weight: 600;">#TCK-{{ $ticket->id }}</span></td>
                    <td>{{ $ticket->user->name }}</td>
                    <td><strong style="font-family: var(--font-serif); font-size: 15px; font-weight: 500;">{{ Str::limit($ticket->subject, 50) }}</strong></td>
                    <td>
                        @if($ticket->priority == 'high') <span class="status-badge" style="background: #fff; color: #d93025; border-color: #d93025;">Gấp</span>
                        @elseif($ticket->priority == 'normal') <span class="status-badge inactive">Bình thường</span>
                        @else <span class="status-badge inactive" style="opacity: 0.7;">Thấp</span> @endif
                    </td>
                    <td>
                        @if($ticket->status == 'open') <span class="status-badge">Đang mở</span>
                        @else <span class="status-badge inactive">Đã đóng</span> @endif
                    </td>
                    <td style="font-size: 12px; color: #666;">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                    <td style="text-align: right;">
                        <div class="action-links" style="display: flex; gap: 15px; justify-content: flex-end;">
                            <a href="{{ route('admin.tickets.show', $ticket) }}" class="action-btn" title="Xử lý Ticket">
                                <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: #999;">Không có yêu cầu hỗ trợ nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($tickets->hasPages())
    <div style="margin-top: 20px;">
        {{ $tickets->links('vendor.pagination.admin') }}
    </div>
    @endif
@endsection
