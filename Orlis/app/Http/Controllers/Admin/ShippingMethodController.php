<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;

class ShippingMethodController extends Controller
{
    public function index()
    {
        $methods = ShippingMethod::orderBy('cost')->get();
        return view('admin.shipping-methods.index', compact('methods'));
    }

    public function create()
    {
        return view('admin.shipping-methods.form', ['method' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'cost'        => ['required', 'numeric', 'min:0'],
            'min_order_amount_for_free_shipping' => ['nullable', 'numeric', 'min:0'],
        ], [
            'name.required' => 'Vui lòng nhập tên phương thức giao hàng.',
            'cost.required' => 'Vui lòng nhập phí vận chuyển.',
        ]);

        ShippingMethod::create([
            'name'        => $request->name,
            'description' => $request->description,
            'cost'        => $request->cost,
            'is_active'   => $request->has('is_active'),
            'min_order_amount_for_free_shipping' => $request->min_order_amount_for_free_shipping ?: null,
        ]);

        return redirect()->route('admin.shipping-methods.index')
            ->with('success', 'Đã thêm phương thức giao hàng thành công!');
    }

    public function edit(ShippingMethod $shippingMethod)
    {
        return view('admin.shipping-methods.form', ['method' => $shippingMethod]);
    }

    public function update(Request $request, ShippingMethod $shippingMethod)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'cost'        => ['required', 'numeric', 'min:0'],
            'min_order_amount_for_free_shipping' => ['nullable', 'numeric', 'min:0'],
        ], [
            'name.required' => 'Vui lòng nhập tên phương thức giao hàng.',
            'cost.required' => 'Vui lòng nhập phí vận chuyển.',
        ]);

        $shippingMethod->update([
            'name'        => $request->name,
            'description' => $request->description,
            'cost'        => $request->cost,
            'is_active'   => $request->has('is_active'),
            'min_order_amount_for_free_shipping' => $request->min_order_amount_for_free_shipping ?: null,
        ]);

        return redirect()->route('admin.shipping-methods.index')
            ->with('success', 'Đã cập nhật phương thức giao hàng!');
    }

    public function destroy(ShippingMethod $shippingMethod)
    {
        $shippingMethod->delete();
        return redirect()->route('admin.shipping-methods.index')
            ->with('success', 'Đã xóa phương thức giao hàng!');
    }
}
