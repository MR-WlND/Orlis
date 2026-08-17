<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductCacheService
{
    /**
     * Lấy danh sách sản phẩm có phân trang (Cache 60 phút)
     */
    public function getPaginatedProducts($perPage = 12, $page = 1)
    {
        $cacheKey = "products_page_{$page}_perpage_{$perPage}";

        return Cache::remember($cacheKey, now()->addMinutes(60), function () use ($perPage) {
            return Product::with(['category'])->where('status', 'active')->paginate($perPage);
        });
    }

    /**
     * Lấy chi tiết sản phẩm (Cache 1 ngày)
     */
    public function getProductDetail($slug)
    {
        $cacheKey = "product_detail_{$slug}";

        return Cache::remember($cacheKey, now()->addDays(1), function () use ($slug) {
            return Product::with(['category', 'reviews' => function($q) {
                $q->where('status', 'approved');
            }])->where('slug', $slug)->firstOrFail();
        });
    }

    /**
     * Xóa toàn bộ Cache liên quan đến Product
     */
    public function clearProductCache()
    {
        // Vì project có thể chạy trên file driver (không hỗ trợ Cache Tags),
        // Cách triệt để nhất để đảm bảo đồng bộ dữ liệu là Cache::flush().
        // Trên hệ thống Redis thực tế, sẽ dùng: Cache::tags(['products'])->flush();
        Cache::flush();
    }
}
