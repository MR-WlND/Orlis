<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Category::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categories = [
            [
                'name' => 'Thời trang & Phụ kiện',
                'description' => 'Khám phá thế giới thời trang và phụ kiện đẳng cấp',
                'image' => 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=400&q=80',
                'children' => [
                    [
                        'name' => 'Thời trang Nữ',
                        'description' => 'Trang phục nữ cao cấp',
                        'image' => null,
                        'children' => [
                            ['name' => 'Áo thun & Áo sơ mi Nữ', 'description' => 'Áo nữ thanh lịch', 'image' => null],
                            ['name' => 'Váy & Đầm', 'description' => 'Đầm thiết kế', 'image' => null],
                            ['name' => 'Áo khoác Nữ', 'description' => 'Áo khoác sành điệu', 'image' => null],
                            ['name' => 'Quần & Chân váy', 'description' => 'Quần và chân váy', 'image' => null],
                        ]
                    ],
                    [
                        'name' => 'Thời trang Nam',
                        'description' => 'Trang phục nam lịch lãm',
                        'image' => null,
                        'children' => [
                            ['name' => 'Áo thun & Polo', 'description' => 'Áo thun nam', 'image' => null],
                            ['name' => 'Áo sơ mi Nam', 'description' => 'Áo sơ mi nam', 'image' => null],
                            ['name' => 'Áo khoác & Vest', 'description' => 'Vest nam lịch lãm', 'image' => null],
                            ['name' => 'Quần âu & Quần jean', 'description' => 'Quần nam', 'image' => null],
                        ]
                    ],
                    [
                        'name' => 'Túi xách',
                        'description' => 'Bộ sưu tập túi xách',
                        'image' => null,
                        'children' => [
                            ['name' => 'Túi xách tay', 'description' => 'Túi xách tay', 'image' => null],
                            ['name' => 'Túi đeo chéo', 'description' => 'Túi đeo chéo', 'image' => null],
                            ['name' => 'Balo', 'description' => 'Balo thời trang', 'image' => null],
                            ['name' => 'Ví & Clutch', 'description' => 'Ví dự tiệc', 'image' => null],
                        ]
                    ],
                    [
                        'name' => 'Giày dép',
                        'description' => 'Thế giới giày dép',
                        'image' => null,
                        'children' => [
                            ['name' => 'Giày cao gót', 'description' => 'Giày cao gót', 'image' => null],
                            ['name' => 'Giày thể thao', 'description' => 'Sneakers', 'image' => null],
                            ['name' => 'Giày lười & Moccasins', 'description' => 'Giày lười', 'image' => null],
                            ['name' => 'Sandal & Dép', 'description' => 'Sandal mùa hè', 'image' => null],
                        ]
                    ],
                    [
                        'name' => 'Trang sức & Đồng hồ',
                        'description' => 'Trang sức tinh xảo',
                        'image' => null,
                        'children' => [
                            ['name' => 'Dây chuyền', 'description' => 'Dây chuyền vàng bạc', 'image' => null],
                            ['name' => 'Nhẫn & Khuyên tai', 'description' => 'Nhẫn kim cương', 'image' => null],
                            ['name' => 'Đồng hồ Nam', 'description' => 'Đồng hồ cơ', 'image' => null],
                            ['name' => 'Đồng hồ Nữ', 'description' => 'Đồng hồ thời trang', 'image' => null],
                        ]
                    ],
                    [
                        'name' => 'Trẻ em & Em bé',
                        'description' => 'Thời trang cho bé',
                        'image' => null,
                        'children' => [
                            ['name' => 'Bé gái', 'description' => 'Thời trang bé gái', 'image' => null],
                            ['name' => 'Bé trai', 'description' => 'Thời trang bé trai', 'image' => null],
                            ['name' => 'Sơ sinh', 'description' => 'Đồ sơ sinh', 'image' => null],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'Nước hoa & Làm đẹp',
                'description' => 'Hương thơm quyến rũ và nghệ thuật làm đẹp',
                'image' => 'https://images.unsplash.com/photo-1596462502278-27bf85033e5a?auto=format&fit=crop&w=400&q=80',
                'children' => [
                    [
                        'name' => 'Nước hoa',
                        'description' => 'Bộ sưu tập nước hoa cao cấp',
                        'image' => null,
                        'children' => [
                            ['name' => 'Nước hoa Nữ (EDP & EDT)', 'description' => 'Hương thơm phái đẹp', 'image' => null],
                            ['name' => 'Nước hoa Nam', 'description' => 'Hương thơm phái mạnh', 'image' => null],
                            ['name' => 'Nước hoa Unisex', 'description' => 'Hương thơm phi giới tính', 'image' => null],
                            ['name' => 'Giftset Nước hoa', 'description' => 'Bộ quà tặng', 'image' => null],
                        ]
                    ],
                    [
                        'name' => 'Trang điểm',
                        'description' => 'Mỹ phẩm trang điểm',
                        'image' => null,
                        'children' => [
                            ['name' => 'Trang điểm Môi', 'description' => 'Son môi, son bóng', 'image' => null],
                            ['name' => 'Trang điểm Mặt', 'description' => 'Kem nền, phấn phủ', 'image' => null],
                            ['name' => 'Trang điểm Mắt', 'description' => 'Mascara, phấn mắt', 'image' => null],
                        ]
                    ],
                    [
                        'name' => 'Chăm sóc da',
                        'description' => 'Sản phẩm dưỡng da',
                        'image' => null,
                        'children' => [
                            ['name' => 'Làm sạch sâu', 'description' => 'Sữa rửa mặt, tẩy trang', 'image' => null],
                            ['name' => 'Dưỡng ẩm & Phục hồi', 'description' => 'Kem dưỡng, Serum', 'image' => null],
                            ['name' => 'Mặt nạ & Đặc trị', 'description' => 'Mặt nạ, kem mắt', 'image' => null],
                        ]
                    ]
                ]
            ],
        ];

        foreach ($categories as $cat) {
            $level1 = Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                    'image' => $cat['image']
                ]
            );

            if (isset($cat['children'])) {
                foreach ($cat['children'] as $child) {
                    $level2 = Category::updateOrCreate(
                        ['slug' => Str::slug($level1->name . '-' . $child['name'])],
                        [
                            'name' => $child['name'],
                            'description' => $child['description'],
                            'image' => $child['image'],
                            'parent_id' => $level1->id
                        ]
                    );

                    if (isset($child['children'])) {
                        foreach ($child['children'] as $grandchild) {
                            Category::updateOrCreate(
                                ['slug' => Str::slug($level1->name . '-' . $level2->name . '-' . $grandchild['name'])],
                                [
                                    'name' => $grandchild['name'],
                                    'description' => $grandchild['description'],
                                    'image' => $grandchild['image'],
                                    'parent_id' => $level2->id
                                ]
                            );
                        }
                    }
                }
            }
        }
    }
}
