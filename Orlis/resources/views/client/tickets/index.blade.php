@extends('layouts.client')
@section('title', __('messages.customer_support'))
@section('content')
<div style="background: #f9f9f9; padding: 100px 0 60px; min-height: 80vh;">
    <div class="container" style="max-width: 900px; margin: 0 auto; padding: 0 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h2 style="font-family: var(--font-serif); font-size: 24px;">{{ __('messages.customer_support_tickets') }}</h2>
            <a href="{{ route('tickets.create') }}" style="padding: 10px 20px; background: #111; color: white; text-decoration: none; border-radius: 4px; font-size: 14px;">{{ __('messages.create_new_ticket') }}</a>
        </div>

        @if(session('success'))
            <div style="padding: 12px; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 20px;">{{ session('success') }}</div>
        @endif

        <div style="background: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <thead style="background: #f1f1f1;">
                    <tr>
                        <th style="padding: 15px; text-align: left; border-bottom: 1px solid #ddd;">{{ __('messages.ticket_id') }}</th>
                        <th style="padding: 15px; text-align: left; border-bottom: 1px solid #ddd;">{{ __('messages.subject') }}</th>
                        <th style="padding: 15px; text-align: center; border-bottom: 1px solid #ddd;">{{ __('messages.priority') }}</th>
                        <th style="padding: 15px; text-align: center; border-bottom: 1px solid #ddd;">{{ __('messages.status') }}</th>
                        <th style="padding: 15px; text-align: right; border-bottom: 1px solid #ddd;">{{ __('messages.date_sent') }}</th>
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
                                @if($ticket->priority == 'high') <span style="color: #dc3545;">{{ __('messages.priority_high') }}</span>
                                @elseif($ticket->priority == 'normal') <span>{{ __('messages.priority_normal') }}</span>
                                @else <span style="color: #6c757d;">{{ __('messages.priority_low') }}</span> @endif
                            </td>
                            <td style="padding: 15px; text-align: center; border-bottom: 1px solid #eee;">
                                @if($ticket->status == 'open')
                                    <span style="padding: 4px 10px; background: #e6f7ff; color: #1890ff; border-radius: 12px; font-size: 12px;">{{ __('messages.status_open') }}</span>
                                @else
                                    <span style="padding: 4px 10px; background: #f5f5f5; color: #555; border-radius: 12px; font-size: 12px;">{{ __('messages.status_closed') }}</span>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: right; border-bottom: 1px solid #eee; color: #666; font-size: 13px;">
                                {{ $ticket->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 30px; text-align: center; color: #888;">{{ __('messages.no_tickets') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
