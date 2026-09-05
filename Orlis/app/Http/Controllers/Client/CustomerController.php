<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    /**
     * Trang tổng quan tài khoản.
     */
    public function dashboard()
    {
        $user = Auth::user();

        $totalOrders = Order::where('user_id', $user->id)->count();
        $pendingOrders = Order::where('user_id', $user->id)->where('order_status', 'pending')->count();
        $completedOrders = Order::where('user_id', $user->id)->where('order_status', 'delivered')->count();
        $recentOrders = Order::where('user_id', $user->id)->latest()->take(3)->get();

        return view('client.customer.dashboard', compact(
            'user', 'totalOrders', 'pendingOrders', 'completedOrders', 'recentOrders'
        ));
    }

    /**
     * Lịch sử đơn hàng.
     */
    public function orders(Request $request)
    {
        $query = Order::where('user_id', Auth::id())->with(['items'])->latest();

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        $orders = $query->paginate(10)->withQueryString();
        $statuses = Order::STATUSES;

        return view('client.customer.orders', compact('orders', 'statuses'));
    }

    /**
     * Chi tiết 1 đơn hàng.
     */
    public function orderDetail(Order $order)
    {
        // Đảm bảo khách chỉ xem đơn của mình
        abort_if($order->user_id !== Auth::id(), 403);

        $order->load(['items.variant.product', 'coupon', 'statusLogs']);

        return view('client.customer.order-detail', compact('order'));
    }

    /**
     * Trang thông tin cá nhân.
     */
    public function profile()
    {
        $user = Auth::user();

        return view('client.customer.profile', compact('user'));
    }

    /**
     * Cập nhật thông tin cá nhân.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'current_password' => ['nullable', 'string'],
            'new_password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'name.required' => 'Vui lòng nhập tên.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        // Kiểm tra mật khẩu hiện tại nếu muốn đổi mật khẩu
        if ($request->filled('new_password')) {
            if (! $request->filled('current_password') || ! Hash::check($request->current_password, $user->password)) {
                return back()->with('error', 'Mật khẩu hiện tại không đúng.')->withInput();
            }

            $validated['password'] = Hash::make($validated['new_password']);
        }

        // Upload avatar
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        unset($validated['current_password'], $validated['new_password'], $validated['new_password_confirmation']);

        $user->update($validated);

        return back()->with('success', 'Cập nhật thông tin thành công!');
    }

    /**
     * Quản lý địa chỉ giao hàng.
     */
    public function addresses()
    {
        $addresses = Address::where('user_id', Auth::id())->orderByDesc('is_default')->get();

        return view('client.customer.addresses', compact('addresses'));
    }

    /**
     * Lưu địa chỉ mới.
     */
    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'recipient_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'province' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'ward' => ['required', 'string', 'max:100'],
            'detail_address' => ['required', 'string', 'max:500'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->boolean('is_default')) {
            Address::where('user_id', Auth::id())->update(['is_default' => false]);
        } elseif (! Address::where('user_id', Auth::id())->exists()) {
            $validated['is_default'] = true;
        }

        Address::create($validated);

        return back()->with('success', 'Đã thêm địa chỉ mới.');
    }

    /**
     * Xóa địa chỉ.
     */
    public function destroyAddress(Address $address)
    {
        abort_if($address->user_id !== Auth::id(), 403);

        $address->delete();

        return back()->with('success', 'Đã xóa địa chỉ.');
    }

    /**
     * Đặt địa chỉ mặc định.
     */
    public function setDefaultAddress(Address $address)
    {
        abort_if($address->user_id !== Auth::id(), 403);

        Address::where('user_id', Auth::id())->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('success', 'Đã đặt làm địa chỉ mặc định.');
    }

    /**
     * Danh sách yêu thích.
     */
    public function wishlist()
    {
        $wishlists = Auth::user()->wishlist()->with(['variant.product'])->latest()->get();

        return view('client.customer.wishlist', compact('wishlists'));
    }
}
