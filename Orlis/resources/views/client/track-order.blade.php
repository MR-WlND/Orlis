@extends('layouts.client')
@section('title', __('messages.track_order_title') . ' - Orlis')
@section('content')
<div style="background: #f9f9f9; padding: 60px 0; min-height: 80vh;">
    <div class="container" style="max-width: 800px; margin: 0 auto; padding: 0 20px;">
        <h2 style="font-family: var(--font-serif); font-size: 28px; text-align: center; margin-bottom: 10px;">{{ __('messages.track_order_title') }}</h2>
        <p style="text-align: center; color: #666; font-size: 14px; margin-bottom: 40px;">{{ __('messages.track_order_desc') }}</p>

        <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 30px;">
            <form action="{{ route('track-order.post') }}" method="POST" style="display: flex; gap: 15px; align-items: flex-end;">
                @csrf
                <div style="flex: 1;">
                    <label style="display: block; font-size: 13px; color: #555; margin-bottom: 8px;">{{ __('messages.order_code') }}</label>
                    <input type="text" name="order_code" value="{{ request('order_code') }}" required placeholder="{{ __('messages.order_code_ph') }}" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div style="flex: 1;">
                    <label style="display: block; font-size: 13px; color: #555; margin-bottom: 8px;">{{ __('messages.order_phone') }}</label>
                    <input type="text" name="phone" value="{{ request('phone') }}" required placeholder="{{ __('messages.order_phone_ph') }}" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div>
                    <button type="submit" style="padding: 13px 25px; background: #111; color: white; border: none; border-radius: 4px; font-weight: 500; cursor: pointer;">{{ __('messages.track_btn') }}</button>
                </div>
            </form>
            
            @if(session('error'))
                <div style="margin-top: 15px; color: #dc3545; font-size: 14px; padding: 10px; background: #fff5f5; border-radius: 4px; border: 1px solid #ffe3e3;">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        @if(isset($order))
        <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 20px;">
                <h3 style="font-size: 18px;">{{ __('messages.order_info') }} <span style="color: var(--accent);">#{{ $order->order_code }}</span></h3>
                <span style="padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 600; color: {{ $order->status_color }}; background: {{ $order->status_color }}15;">
                    {{ $order->status_label }}
                </span>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; font-size: 14px;">
                <div>
                    <p style="color: #666; margin-bottom: 5px;">{{ __('messages.recipient') }}</p>
                    <p style="font-weight: 500;">{{ $order->shipping_address['recipient_name'] }}</p>
                    <p style="color: #666; margin-top: 5px;">{{ $order->shipping_address['recipient_phone'] }}</p>
                </div>
                <div>
                    <p style="color: #666; margin-bottom: 5px;">{{ __('messages.delivery_address') }}</p>
                    <p style="font-weight: 500; line-height: 1.5;">{{ $order->shipping_address['detail_address'] }}, {{ $order->shipping_address['ward'] }}, {{ $order->shipping_address['district'] }}, {{ $order->shipping_address['province'] }}</p>
                </div>
            </div>

            <h4 style="font-size: 15px; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">{{ __('messages.product_details') }}</h4>
            @foreach($order->items as $item)
            <div style="display: flex; align-items: center; gap: 15px; padding: 15px 0; border-bottom: 1px dashed #eee;">
                <img src="{{ $item->variant->product->thumbnail ? Storage::url($item->variant->product->thumbnail) : 'https://placehold.co/60' }}" alt="Product" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                <div style="flex: 1;">
                    <h5 style="font-size: 14px; margin-bottom: 4px;">{{ $item->variant->product->name ?? __('messages.product') }}</h5>
                    <p style="font-size: 13px; color: #666;">{{ __('messages.variant') }} {{ $item->variant->display_name ?? '' }} x {{ $item->quantity }}</p>
                </div>
                <div style="font-weight: 600; font-size: 14px;">
                    {{ number_format($item->subtotal, 0, ',', '.') }}₫
                </div>
            </div>
            @endforeach

            <div style="text-align: right; margin-top: 25px; font-size: 15px;">
                <p style="margin-bottom: 8px;">{{ __('messages.subtotal_colon') }} {{ number_format($order->subtotal, 0, ',', '.') }}₫</p>
                <p style="margin-bottom: 8px; color: #dc3545;">{{ __('messages.discount_colon') }} -{{ number_format($order->discount_amount, 0, ',', '.') }}₫</p>
                <p style="margin-top: 15px; font-size: 18px; font-weight: 600;">{{ __('messages.grand_total_colon') }} <span style="color: var(--accent);">{{ number_format($order->grand_total, 0, ',', '.') }}₫</span></p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
