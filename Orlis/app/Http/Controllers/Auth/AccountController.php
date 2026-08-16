<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => ['required'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Mật khẩu không chính xác.']);
        }

        DB::transaction(function () use ($user) {
            // Ẩn danh hóa đơn hàng
            \App\Models\Order::where('user_id', $user->id)->update([
                'user_id' => null,
                'recipient_name' => 'Deleted User',
                'recipient_phone' => '0000000000',
                'shipping_address_snapshot' => json_encode(['status' => 'anonymized', 'message' => 'Data removed due to GDPR request']),
            ]);

            // Ẩn danh hóa đánh giá
            \App\Models\Review::where('user_id', $user->id)->update([
                'user_id' => null,
                'is_anonymous' => true,
            ]);

            // Xóa dữ liệu cá nhân liên quan
            \DB::table('addresses')->where('user_id', $user->id)->delete();
            \DB::table('carts')->where('user_id', $user->id)->delete();
            \DB::table('wishlists')->where('user_id', $user->id)->delete();

            // Hard Delete User
            $user->delete();
        });

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Tài khoản của bạn đã được xóa hoàn toàn khỏi hệ thống.');
    }
}
