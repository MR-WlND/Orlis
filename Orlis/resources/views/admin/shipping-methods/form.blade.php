@extends('layouts.admin')

@section('title', isset($method) ? 'Chỉnh sửa Phương thức Giao hàng' : 'Thêm Phương thức Giao hàng')

@section('content')
<div class="page-header">
    <div class="header-text">
        <h2 class="page-title">{{ isset($method) ? 'Chỉnh sửa gói giao hàng' : 'Thêm gói giao hàng mới' }}</h2>
        <p class="page-subtitle">Cấu hình tên, mô tả, phí vận chuyển và các điều kiện miễn phí giao hàng.</p>
    </div>
    <a href="{{ route('admin.shipping-methods.index') }}" class="btn-add-new" style="background: #fff; color: #111; border: 1px solid #111;">
        ← QUAY LẠI
    </a>
</div>

<div style="max-width: 720px;">
    <form action="{{ isset($method) ? route('admin.shipping-methods.update', $method->id) : route('admin.shipping-methods.store') }}" method="POST">
        @csrf
        @if(isset($method)) @method('PUT') @endif

        @if($errors->any())
            <div style="padding: 15px 20px; background: #fff0f0; border: 1px solid #c00; color: #c00; margin-bottom: 24px; font-size: 12px; line-height: 1.8;">
                @foreach($errors->all() as $error) • {{ $error }}<br> @endforeach
            </div>
        @endif

        {{-- Tên gói --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #777; margin-bottom: 8px;">
                Tên phương thức giao hàng <span style="color: red;">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name', $method?->name) }}"
                placeholder="Ví dụ: Giao hàng Hỏa tốc Couture Express (Trong ngày)"
                style="width: 100%; padding: 13px 16px; border: 1px solid #e0e0e0; font-size: 14px; color: #333; border-radius: 0; box-sizing: border-box;">
        </div>

        {{-- Mô tả --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #777; margin-bottom: 8px;">
                Mô tả (Hiển thị cho khách hàng)
            </label>
            <textarea name="description" rows="3"
                placeholder="Ví dụ: Dành riêng cho khu vực Nội thành Hà Nội & TP. HCM."
                style="width: 100%; padding: 13px 16px; border: 1px solid #e0e0e0; font-size: 14px; color: #333; border-radius: 0; box-sizing: border-box; resize: vertical;">{{ old('description', $method?->description) }}</textarea>
        </div>

        {{-- Phí vận chuyển --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #777; margin-bottom: 8px;">
                    Phí vận chuyển (VNĐ) <span style="color: red;">*</span>
                </label>
                <input type="number" name="cost" value="{{ old('cost', $method?->cost ?? 0) }}" min="0" step="1000"
                    style="width: 100%; padding: 13px 16px; border: 1px solid #e0e0e0; font-size: 14px; color: #333; border-radius: 0; box-sizing: border-box;">
                <small style="display: block; margin-top: 6px; font-size: 11px; color: #999;">Nhập 0 nếu là gói Miễn phí.</small>
            </div>
            <div>
                <label style="display: block; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #777; margin-bottom: 8px;">
                    Miễn phí từ giá trị đơn (VNĐ)
                </label>
                <input type="number" name="min_order_amount_for_free_shipping"
                    value="{{ old('min_order_amount_for_free_shipping', $method?->min_order_amount_for_free_shipping) }}"
                    min="0" step="100000" placeholder="Ví dụ: 10000000"
                    style="width: 100%; padding: 13px 16px; border: 1px solid #e0e0e0; font-size: 14px; color: #333; border-radius: 0; box-sizing: border-box;">
                <small style="display: block; margin-top: 6px; font-size: 11px; color: #999;">Để trống nếu không có ưu đãi miễn phí.</small>
            </div>
        </div>

        {{-- Kích hoạt --}}
        <div style="margin-bottom: 32px; display: flex; align-items: center; gap: 12px;">
            <input type="checkbox" name="is_active" id="is_active" value="1"
                {{ old('is_active', $method?->is_active ?? true) ? 'checked' : '' }}
                style="width: 18px; height: 18px; accent-color: #111;">
            <label for="is_active" style="font-size: 13px; font-weight: 600; color: #333; cursor: pointer;">
                Kích hoạt phương thức giao hàng này (hiển thị cho khách hàng)
            </label>
        </div>

        <button type="submit" style="padding: 15px 40px; background: #111; color: #fff; border: none; font-size: 13px; font-weight: 600; letter-spacing: 1px; cursor: pointer; text-transform: uppercase;">
            {{ isset($method) ? 'CẬP NHẬT' : 'LƯU GÓI GIAO HÀNG' }}
        </button>
    </form>
</div>
@endsection
