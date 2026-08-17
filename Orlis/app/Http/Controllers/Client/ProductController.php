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
        $product = Product::with(['category', 'variants', 'reviews' => function($q) {
            $q->where('status', 'approved');
        }])
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

        return view('client.product', compact('product', 'images', 'relatedProducts'));
    }
}
