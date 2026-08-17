<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Xóa sạch dữ liệu sản phẩm cũ
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Chỉ thêm sản phẩm vào danh mục con (Level 3 - không có danh mục con nào khác)
        // Hoặc thêm vào toàn bộ. Theo yêu cầu là "mỗi danh mục 10 sản phẩm", ta lấy toàn bộ.
        $categories = Category::all();

        // Hình ảnh ngẫu nhiên từ Unsplash
        $images = [
            'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?auto=format&fit=crop&w=400&q=80',
            'https://images.unsplash.com/photo-1594911772125-07fc7a2d8d9f?auto=format&fit=crop&w=400&q=80',
            'https://images.unsplash.com/photo-1520608552192-3211516e87f3?auto=format&fit=crop&w=400&q=80',
            'https://images.unsplash.com/photo-1550246140-5119ae4790b8?auto=format&fit=crop&w=400&q=80',
            'https://images.unsplash.com/photo-1522204523234-8729aa6e3d5f?auto=format&fit=crop&w=400&q=80',
            'https://images.unsplash.com/photo-1596462502278-27bf85033e5a?auto=format&fit=crop&w=400&q=80',
            'https://images.unsplash.com/photo-1608248543803-ba4f8c70ae0b?auto=format&fit=crop&w=400&q=80',
        ];

        foreach ($categories as $category) {
            for ($i = 1; $i <= 10; $i++) {
                $price = rand(50, 1500) * 10000; // Giá từ 500k đến 15 triệu
                $hasSale = rand(0, 3) === 0; // Tỷ lệ 25% có sale
                
                Product::create([
                    'category_id' => $category->id,
                    'name' => 'Sản phẩm ' . $category->name . ' - Phiên bản ' . $i,
                    'description' => 'Mô tả chi tiết cho sản phẩm thuộc danh mục ' . $category->name . '. Đây là sản phẩm thiết kế mang đậm phong cách sang trọng và thanh lịch.',
                    'price' => $price,
                    'sale_price' => $hasSale ? $price * 0.8 : null, // Giảm 20%
                    'thumbnail' => $images[array_rand($images)],
                    'is_active' => true,
                    'is_featured' => rand(0, 5) === 0, // Tỷ lệ 20% nổi bật
                ]);
            }
        }
    }
}
