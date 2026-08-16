<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(10);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
        ]);

        if (empty($request->discount_percent) && empty($request->discount_amount)) {
            return back()->withInput()->withErrors(['error' => 'Vui lòng nhập phần trăm giảm giá hoặc số tiền giảm giá.']);
        }

        Coupon::create($request->all());

        return redirect()->route('admin.coupons.index')->with('success', 'Tạo mã giảm giá thành công.');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.form', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
        ]);

        if (empty($request->discount_percent) && empty($request->discount_amount)) {
            return back()->withInput()->withErrors(['error' => 'Vui lòng nhập phần trăm giảm giá hoặc số tiền giảm giá.']);
        }

        $coupon->update($request->all());

        return redirect()->route('admin.coupons.index')->with('success', 'Cập nhật mã giảm giá thành công.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Xóa mã giảm giá thành công.');
    }
}
