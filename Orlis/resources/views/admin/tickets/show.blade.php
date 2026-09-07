@extends('layouts.admin')
@section('title', 'Ticket #' . $ticket->id)
@section('content')
    <div class="page-header">
        <div class="header-text">
            <h2 class="page-title">Ticket #{{ $ticket->id }}</h2>
            <p class="page-subtitle">{{ $ticket->subject }}</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.tickets.index') }}" class="btn-cancel">QUAY LẠI</a>
        </div>
    </div>

    <div class="edit-grid">
        <!-- Cột trái: Nội dung thảo luận -->
        <div class="form-card" style="padding: 0;">
            <div style="padding: 24px 30px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-family: var(--font-sans); font-size: 14px; font-weight: 600; margin: 0;">Nội dung phản hồi</h3>
                @if($ticket->status == 'open')
                    <span class="status-badge">Đang mở</span>
                @else
                    <span class="status-badge inactive">Đã đóng</span>
                @endif
            </div>

            <div style="padding: 30px; max-height: 500px; overflow-y: auto; background: #fcfcfc;">
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    @foreach($ticket->replies as $reply)
                        <div style="display: flex; gap: 15px; {{ $reply->admin_id ? 'flex-direction: row-reverse;' : '' }}">
                            <img src="{{ $reply->admin_id ? ($reply->admin->avatar ? Storage::url($reply->admin->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($reply->admin->name).'&background=random') : ($reply->user->avatar ? Storage::url($reply->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($reply->user->name).'&background=random') }}" alt="Avatar" style="width: 36px; height: 36px; border-radius: 50%;">
                            <div style="max-width: 80%; background: {{ $reply->admin_id ? '#111' : '#fff' }}; color: {{ $reply->admin_id ? '#fff' : '#333' }}; padding: 15px 20px; border: 1px solid {{ $reply->admin_id ? '#111' : '#ddd' }}; border-radius: 4px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 11px; color: {{ $reply->admin_id ? '#ccc' : '#888' }};">
                                    <strong>{{ $reply->admin_id ? 'Bạn (Admin)' : $reply->user->name }}</strong>
                                    <span style="margin-left: 20px;">{{ $reply->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div style="font-size: 13px; line-height: 1.6; white-space: pre-wrap;">{{ $reply->message }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div style="padding: 30px; border-top: 1px solid #eee;">
                <form action="{{ route('admin.tickets.reply', $ticket) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <textarea name="message" class="form-control" rows="4" required placeholder="Nhập nội dung phản hồi..." style="resize: vertical;"></textarea>
                    </div>
                    <div style="text-align: right; margin-top: 15px;">
                        <button type="submit" class="btn-submit">GỬI PHẢN HỒI</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Cột phải: Thông tin -->
        <div class="right-col">
            <div class="form-card" style="padding: 24px;">
                <h3 style="font-family: var(--font-sans); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #000; margin: 0 0 16px 0; padding-bottom: 12px; border-bottom: 1px solid #eee;">THÔNG TIN TICKET</h3>
                
                <div style="display: flex; flex-direction: column; gap: 15px; font-size: 13px;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #888;">Khách hàng:</span>
                        <strong style="color: #111;">{{ $ticket->user->name }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #888;">Email:</span>
                        <span style="color: #111;">{{ $ticket->user->email }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: #888;">Độ ưu tiên:</span>
                        <span>
                            @if($ticket->priority == 'high') <span class="status-badge" style="background: #fff; color: #d93025; border-color: #d93025;">Gấp</span>
                            @elseif($ticket->priority == 'normal') <span class="status-badge inactive">Bình thường</span>
                            @else <span class="status-badge inactive" style="opacity: 0.7;">Thấp</span> @endif
                        </span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #888;">Ngày tạo:</span>
                        <span style="color: #111;">{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #eee;">
                    @if($ticket->status == 'open')
                    <form action="{{ route('admin.tickets.close', $ticket) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn-cancel" style="width: 100%; border-color: #d93025; color: #d93025;">ĐÓNG TICKET NÀY</button>
                    </form>
                    @else
                    <div style="text-align: center; color: #888; font-size: 12px; font-weight: 600; text-transform: uppercase;">Ticket Đã Đóng</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
