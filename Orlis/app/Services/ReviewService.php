<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use Exception;

class ReviewService
{
    public function createReview(
        int $userId,
        int $orderItemId,
        int $rating,
        ?string $comment = null,
        array $images = [],
        bool $isAnonymous = false
    ): Review {
        // 1. Kiểm tra OrderItem có hợp lệ, thuộc về User và Đã Giao Hàng Thành Công
        $orderItem = OrderItem::where('id', $orderItemId)
            ->whereHas('order', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->where('order_status', 'delivered');
            })
            ->first();

        if (!$orderItem) {
            throw new Exception("Bạn chỉ có thể đánh giá sản phẩm sau khi đơn hàng đã được giao thành công.");
        }

        // 2. Anti-Spam Check: Một order_item_id chỉ được review 1 lần
        $alreadyReviewed = Review::where('order_item_id', $orderItemId)->exists();
        if ($alreadyReviewed) {
            throw new Exception("Bạn đã gửi đánh giá cho sản phẩm này trong đơn hàng.");
        }

        // 3. Khởi tạo Review ở trạng thái 'pending' (Chờ Admin duyệt)
        return DB::transaction(function () use ($userId, $orderItem, $rating, $comment, $images, $isAnonymous) {
            return Review::create([
                'user_id' => $userId,
                'order_item_id' => $orderItem->id,
                // $orderItem->variant could be null or empty, depending on relations, 
                // but we assume product_id is accessible via variant.
                'product_id' => $orderItem->variant->product_id ?? null,
                'rating' => $rating,
                'comment' => $comment,
                'images' => json_encode($images),
                'is_anonymous' => $isAnonymous,
                'status' => 'pending', // Luôn qua bước kiểm duyệt Admin
            ]);
        });
    }

    public function approveReview(int $reviewId): bool
    {
        return DB::transaction(function () use ($reviewId) {
            $review = Review::findOrFail($reviewId);
            $review->update(['status' => 'approved']);

            // Recalculate Rating Cache cho Product
            if ($review->product_id) {
                $product = Product::find($review->product_id);
                if ($product) {
                    $approvedReviews = Review::where('product_id', $product->id)
                        ->where('status', 'approved');

                    $avgRating = $approvedReviews->avg('rating') ?? 0;
                    $count = $approvedReviews->count();

                    $product->update([
                        'rating_avg' => round($avgRating, 1),
                        'reviews_count' => $count,
                    ]);
                }
            }

            return true;
        });
    }
}
