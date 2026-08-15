<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Nước hoa',
                'description' => 'Bộ sưu tập nước hoa cao cấp',
                'image' => 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=200&q=80',
                'children' => [
                    ['name' => 'Nước hoa nữ', 'description' => 'Nước hoa quyến rũ cho phái đẹp', 'image' => 'https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=200&q=80'],
                    ['name' => 'Nước hoa nam', 'description' => 'Nước hoa mạnh mẽ, nam tính', 'image' => 'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?auto=format&fit=crop&w=200&q=80'],
                ]
            ],
            [
                'name' => 'Túi xách',
                'description' => 'Túi xách thời trang cao cấp',
                'image' => 'https://images.unsplash.com/photo-1584916201218-f4242ceb4809?auto=format&fit=crop&w=200&q=80',
                'children' => []
            ],
            [
                'name' => 'Trang sức',
                'description' => 'Trang sức tinh xảo, sang trọng',
                'image' => 'https://images.unsplash.com/photo-1599643478524-fb66f81a799c?auto=format&fit=crop&w=200&q=80',
                'children' => []
            ],
            [
                'name' => 'Sẵn sàng để mặc',
                'description' => 'Thời trang ready-to-wear cao cấp',
                'image' => 'https://images.unsplash.com/photo-1550614000-4b95d466bcbe?auto=format&fit=crop&w=200&q=80',
                'children' => []
            ],
        ];

        foreach ($categories as $cat) {
            $parent = Category::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'description' => $cat['description'],
                'image' => $cat['image']
            ]);

            if (isset($cat['children'])) {
                foreach ($cat['children'] as $child) {
                    Category::create([
                        'name' => $child['name'],
                        'slug' => Str::slug($child['name']),
                        'description' => $child['description'],
                        'image' => $child['image'],
                        'parent_id' => $parent->id
                    ]);
                }
            }
        }
    }
}
