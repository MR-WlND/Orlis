<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product'])->latest();

        if ($request->has('status') && in_array($request->status, ['pending', 'approved', 'hidden'])) {
            $query->where('status', $request->status);
        }

        $reviews = $query->paginate(10);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function updateStatus(Request $request, Review $review)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,hidden',
        ]);

        $review->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Đã cập nhật trạng thái đánh giá.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->back()->with('success', 'Xóa đánh giá thành công.');
    }
}
