@extends('layouts.client')
@section('title', __('messages.create_support_ticket'))
@section('content')
<div style="background: #f9f9f9; padding: 100px 0 60px; min-height: 80vh;">
    <div class="container" style="max-width: 700px; margin: 0 auto; padding: 0 20px;">
        <h2 style="font-family: var(--font-serif); font-size: 24px; margin-bottom: 20px;">{{ __('messages.submit_new_ticket') }}</h2>
        
        <div style="background: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); padding: 30px;">
            <form action="{{ route('tickets.store') }}" method="POST">
                @csrf
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; color: #333; margin-bottom: 8px; font-weight: 500;">{{ __('messages.subject_label') }}</label>
                    <input type="text" name="subject" required placeholder="{{ __('messages.subject_placeholder') }}" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-family: inherit;">
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; color: #333; margin-bottom: 8px; font-weight: 500;">{{ __('messages.priority_level') }}</label>
                    <select name="priority" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-family: inherit;">
                        <option value="normal">{{ __('messages.priority_normal') }}</option>
                        <option value="high">{{ __('messages.priority_high_desc') }}</option>
                        <option value="low">{{ __('messages.priority_low') }}</option>
                    </select>
                </div>
                
                <div style="margin-bottom: 25px;">
                    <label style="display: block; font-size: 14px; color: #333; margin-bottom: 8px; font-weight: 500;">{{ __('messages.detailed_message') }}</label>
                    <textarea name="message" rows="6" required placeholder="{{ __('messages.message_placeholder') }}" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-family: inherit; resize: vertical;"></textarea>
                </div>
                
                <div style="display: flex; gap: 15px; justify-content: flex-end;">
                    <a href="{{ route('tickets.index') }}" style="padding: 12px 20px; background: #f1f1f1; color: #333; text-decoration: none; border-radius: 4px; font-size: 14px;">{{ __('messages.cancel') }}</a>
                    <button type="submit" style="padding: 12px 25px; background: #111; color: white; border: none; border-radius: 4px; font-size: 14px; cursor: pointer;">{{ __('messages.submit_request') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
