<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function show($id)
    {
        $product = Product::with(['category', 'variants'])
        ->where('is_active', true)
        ->where(function($q) use ($id) {
            $q->where('id', $id)->orWhere('slug', $id);
        })
        ->firstOrFail();

        // Lấy danh sách ảnh (bao gồm ảnh đại diện và các ảnh phụ)
        $images = [$product->thumbnail];
        try {
            $extraImages = DB::table('product_images')
                ->where('product_id', $product->id)
                ->orderBy('order')
                ->pluck('image_path')
                ->toArray();
            $images = array_merge($images, $extraImages);
        } catch (\Exception $e) {
            // Bỏ qua nếu bảng chưa được migrate hoàn chỉnh hoặc có lỗi
        }
        
        // Lọc bỏ giá trị rỗng/null
        $images = array_filter($images);

        // Lấy sản phẩm gợi ý cùng danh mục
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        // Xử lý sản phẩm đã xem gần đây
        $viewed = session()->get('recently_viewed', []);
        
        $recentlyViewed = collect();
        if (!empty($viewed)) {
            // Lấy sản phẩm và giữ nguyên thứ tự trong mảng viewed
            $ids = implode(',', $viewed);
            $recentlyViewed = Product::whereIn('id', $viewed)
                ->where('id', '!=', $product->id)
                ->where('is_active', true)
                ->orderByRaw("FIELD(id, $ids)")
                ->take(4)
                ->get();
        }

        // Thêm sản phẩm hiện tại vào đầu danh sách đã xem
        if (($key = array_search($product->id, $viewed)) !== false) {
            unset($viewed[$key]);
        }
        array_unshift($viewed, $product->id);
        $viewed = array_slice($viewed, 0, 10); // Lưu tối đa 10 sản phẩm
        session()->put('recently_viewed', $viewed);

        return view('client.product', compact('product', 'images', 'relatedProducts', 'recentlyViewed'));
    }
}
