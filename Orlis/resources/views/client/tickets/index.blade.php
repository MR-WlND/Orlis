@extends('layouts.client')
@section('title', 'Hỗ Trợ Khách Hàng')
@section('content')
<div style="background: #f9f9f9; padding: 40px 0; min-height: 80vh;">
    <div class="container" style="max-width: 900px; margin: 0 auto; padding: 0 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h2 style="font-family: var(--font-serif); font-size: 24px;">Hỗ Trợ Khách Hàng (Tickets)</h2>
            <a href="{{ route('tickets.create') }}" style="padding: 10px 20px; background: #111; color: white; text-decoration: none; border-radius: 4px; font-size: 14px;">+ Tạo yêu cầu mới</a>
        </div>

        @if(session('success'))
            <div style="padding: 12px; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 20px;">{{ session('success') }}</div>
        @endif

        <div style="background: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <thead style="background: #f1f1f1;">
                    <tr>
                        <th style="padding: 15px; text-align: left; border-bottom: 1px solid #ddd;">Mã Hỗ Trợ</th>
                        <th style="padding: 15px; text-align: left; border-bottom: 1px solid #ddd;">Tiêu Đề</th>
                        <th style="padding: 15px; text-align: center; border-bottom: 1px solid #ddd;">Độ Ưu Tiên</th>
                        <th style="padding: 15px; text-align: center; border-bottom: 1px solid #ddd;">Trạng Thái</th>
                        <th style="padding: 15px; text-align: right; border-bottom: 1px solid #ddd;">Ngày Gửi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr>
                            <td style="padding: 15px; border-bottom: 1px solid #eee;">#TCK-{{ $ticket->id }}</td>
                            <td style="padding: 15px; border-bottom: 1px solid #eee;">
                                <a href="{{ route('tickets.show', $ticket) }}" style="color: var(--accent); text-decoration: none; font-weight: 500;">{{ $ticket->subject }}</a>
                            </td>
                            <td style="padding: 15px; text-align: center; border-bottom: 1px solid #eee;">
                                @if($ticket->priority == 'high') <span style="color: #dc3545;">Gấp</span>
                                @elseif($ticket->priority == 'normal') <span>Bình thường</span>
                                @else <span style="color: #6c757d;">Thấp</span> @endif
                            </td>
                            <td style="padding: 15px; text-align: center; border-bottom: 1px solid #eee;">
                                @if($ticket->status == 'open')
                                    <span style="padding: 4px 10px; background: #e6f7ff; color: #1890ff; border-radius: 12px; font-size: 12px;">Đang mở</span>
                                @else
                                    <span style="padding: 4px 10px; background: #f5f5f5; color: #555; border-radius: 12px; font-size: 12px;">Đã đóng</span>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: right; border-bottom: 1px solid #eee; color: #666; font-size: 13px;">
                                {{ $ticket->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 30px; text-align: center; color: #888;">Bạn chưa có yêu cầu hỗ trợ nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
