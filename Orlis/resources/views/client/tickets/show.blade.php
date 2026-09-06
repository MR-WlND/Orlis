@extends('layouts.client')
@section('title', __('messages.ticket_hash') . $ticket->id)
@section('content')
<div style="background: #f9f9f9; padding: 100px 0 60px; min-height: 80vh;">
    <div class="container" style="max-width: 800px; margin: 0 auto; padding: 0 20px;">
        
        <div style="margin-bottom: 20px;">
            <a href="{{ route('tickets.index') }}" style="color: #666; text-decoration: none; font-size: 14px;">{!! __('messages.back_to_list') !!}</a>
        </div>

        @if(session('success'))
            <div style="padding: 12px; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 20px;">{{ session('success') }}</div>
        @endif

        <div style="background: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); padding: 30px; margin-bottom: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px;">
                <div>
                    <h2 style="font-size: 20px; margin-bottom: 5px;">{{ $ticket->subject }}</h2>
                    <p style="color: #666; font-size: 13px;">{{ __('messages.sent_on') }} {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    @if($ticket->status == 'open')
                        <span style="padding: 4px 10px; background: #e6f7ff; color: #1890ff; border-radius: 12px; font-size: 12px;">{{ __('messages.status_open') }}</span>
                    @else
                        <span style="padding: 4px 10px; background: #f5f5f5; color: #555; border-radius: 12px; font-size: 12px;">{{ __('messages.status_closed') }}</span>
                    @endif
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 20px;">
                @foreach($ticket->replies as $reply)
                    <div style="display: flex; gap: 15px; {{ $reply->user_id == Auth::id() ? 'flex-direction: row-reverse;' : '' }}">
                        <img src="{{ $reply->user->avatar ? Storage::url($reply->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($reply->user->name).'&background=random' }}" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%;">
                        <div style="max-width: 80%; background: {{ $reply->user_id == Auth::id() ? '#111' : '#f1f1f1' }}; color: {{ $reply->user_id == Auth::id() ? 'white' : '#333' }}; padding: 15px; border-radius: 8px;">
                            <div style="font-size: 12px; margin-bottom: 5px; opacity: 0.8; display: flex; justify-content: space-between;">
                                <span>{{ $reply->user_id == Auth::id() ? __('messages.you') : ($reply->user->role == 'admin' ? 'Orlis Support' : $reply->user->name) }}</span>
                                <span style="margin-left: 15px;">{{ $reply->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div style="font-size: 14px; line-height: 1.5; white-space: pre-wrap;">{{ $reply->message }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div style="background: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); padding: 30px;">
            <h3 style="font-size: 16px; margin-bottom: 15px;">{{ $ticket->status == 'closed' ? __('messages.ticket_closed_notice') : __('messages.send_reply') }}</h3>
            <form action="{{ route('tickets.reply', $ticket) }}" method="POST">
                @csrf
                <textarea name="message" rows="4" required placeholder="{{ __('messages.reply_placeholder') }}" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-family: inherit; resize: vertical; margin-bottom: 15px;"></textarea>
                <div style="text-align: right;">
                    <button type="submit" style="padding: 10px 25px; background: #111; color: white; border: none; border-radius: 4px; cursor: pointer;">{{ __('messages.send') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
