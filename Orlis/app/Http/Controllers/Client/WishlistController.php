<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Toggle thêm/xóa khỏi wishlist.
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'variant_id' => ['required', 'integer', 'exists:product_variants,id'],
        ]);

        $existing = Wishlist::where('user_id', Auth::id())
            ->where('variant_id', $request->variant_id)
            ->first();

        if ($existing) {
            $existing->delete();
            $inWishlist = false;
            $message = 'Đã xóa khỏi danh sách yêu thích.';
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'variant_id' => $request->variant_id,
            ]);
            $inWishlist = true;
            $message = 'Đã thêm vào danh sách yêu thích.';
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'in_wishlist' => $inWishlist,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
