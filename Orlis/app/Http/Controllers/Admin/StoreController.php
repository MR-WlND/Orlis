<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::withCount('appointments')->latest()->paginate(15);
        return view('admin.stores.index', compact('stores'));
    }

    public function create()
    {
        return view('admin.stores.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'nullable|string|max:20',
            'opening_hours' => 'nullable|string|max:255',
        ]);

        Store::create($validated);

        return redirect()->route('admin.stores.index')->with('success', 'Đã thêm cửa hàng mới thành công!');
    }

    public function edit(Store $store)
    {
        return view('admin.stores.edit', compact('store'));
    }

    public function update(Request $request, Store $store)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'nullable|string|max:20',
            'opening_hours' => 'nullable|string|max:255',
        ]);

        $store->update($validated);

        return redirect()->route('admin.stores.index')->with('success', 'Đã cập nhật thông tin cửa hàng!');
    }

    public function destroy(Store $store)
    {
        if ($store->appointments()->count() > 0 || $store->inventory()->count() > 0) {
            return back()->with('error', 'Không thể xóa cửa hàng đang có lịch hẹn hoặc tồn kho!');
        }
        
        $store->delete();
        return back()->with('success', 'Đã xóa cửa hàng thành công!');
    }
}
