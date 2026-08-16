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

            // Deep GDPR: Xóa ảnh vật lý và ẩn comment
            $reviews = \App\Models\Review::where('user_id', $user->id)->get();
            foreach ($reviews as $review) {
                if (!empty($review->rating_media)) {
                    // Giả định dùng cột JSON hoặc string
                    $medias = is_array($review->rating_media) ? $review->rating_media : json_decode($review->rating_media, true) ?? [$review->rating_media];
                    foreach ($medias as $media) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($media);
                    }
                }
                
                $review->update([
                    'user_id' => null,
                    'is_anonymous' => true,
                    'comment' => '[Nội dung đã bị ẩn theo chính sách bảo mật GDPR]',
                    'rating_media' => null,
                ]);
            }

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
