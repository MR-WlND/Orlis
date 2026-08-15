<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            return;
        }

        $products = [
            [
                'name' => 'Chanel N°5 Eau de Parfum',
                'description' => 'Hương thơm kinh điển của Chanel.',
                'price' => 150.00,
                'sale_price' => null,
            ],
            [
                'name' => 'Dior Sauvage',
                'description' => 'Hương nam tính, mạnh mẽ.',
                'price' => 120.00,
                'sale_price' => 105.00,
            ],
            [
                'name' => 'Túi Gucci Marmont',
                'description' => 'Túi xách cao cấp từ Gucci.',
                'price' => 2500.00,
                'sale_price' => null,
            ],
            [
                'name' => 'Đồng hồ Rolex Submariner',
                'description' => 'Đồng hồ biểu tượng của Rolex.',
                'price' => 10500.00,
                'sale_price' => null,
            ],
            [
                'name' => 'Tom Ford Black Orchid',
                'description' => 'Nước hoa bí ẩn, sang trọng.',
                'price' => 180.00,
                'sale_price' => 165.00,
            ]
        ];

        foreach ($products as $index => $prod) {
            Product::create([
                'category_id' => $categories->random()->id,
                'name' => $prod['name'],
                'slug' => Str::slug($prod['name']),
                'sku' => strtoupper(Str::random(8)),
                'description' => $prod['description'],
                'price' => $prod['price'],
                'sale_price' => $prod['sale_price'],
                'is_active' => true,
                'is_featured' => ($index % 2 == 0)
            ]);
        }
    }
}
