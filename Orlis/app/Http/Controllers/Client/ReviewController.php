<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $product = Product::findOrFail($id);
        $user = Auth::user();

        // Check if user has purchased this product
        $hasPurchased = Order::where('user_id', $user->id)
            ->whereIn('status', ['completed', 'delivered'])
            ->whereHas('items', function ($query) use ($product) {
                $query->whereHas('variant', function ($q) use ($product) {
                    $q->where('product_id', $product->id);
                });
            })->exists();

        if (!$hasPurchased) {
            return back()->with('error', 'Bạn chỉ có thể đánh giá sản phẩm sau khi đã mua và nhận hàng thành công.');
        }

        // Check if already reviewed
        $existingReview = Review::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi.');
        }

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('reviews', 'public');
                $imagePaths[] = $path;
            }
        }

        Review::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'images' => $imagePaths,
            'status' => 'approved', // Auto approve for now, or 'pending' if manual moderation is needed
        ]);

        // Update product rating cache
        $averageRating = Review::where('product_id', $product->id)
            ->where('status', 'approved')
            ->avg('rating');
            
        $product->update(['rating_cache' => $averageRating]);

        return back()->with('success', 'Cảm ơn bạn đã gửi đánh giá!');
    }
}
