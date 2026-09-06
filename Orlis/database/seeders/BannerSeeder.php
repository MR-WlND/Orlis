<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;
use Carbon\Carbon;

class BannerSeeder extends Seeder
{
    public function run()
    {
        Banner::truncate();

        $banners = [
            [
                'title' => 'BỘ SƯU TẬP MÙA THU',
                'description' => 'Khám phá những thiết kế mới nhất',
                'image_path' => 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=1920&q=80',
                'image_mobile_path' => 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=800&q=80',
                'link_url' => '/catalog',
                'text_color' => '#ffffff',
                'position' => 'home_hero',
                'is_global' => true,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'TÚI XÁCH ICONIC',
                'description' => 'Sự thanh lịch vượt thời gian',
                'image_path' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?auto=format&fit=crop&w=800&q=80',
                'image_mobile_path' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?auto=format&fit=crop&w=600&q=80',
                'link_url' => '/catalog/tui-xach',
                'text_color' => '#ffffff',
                'position' => 'home_double',
                'is_global' => true,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'GIÀY THỜI TRANG',
                'description' => 'Bước đi đầy tự tin',
                'image_path' => 'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?auto=format&fit=crop&w=800&q=80',
                'image_mobile_path' => 'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?auto=format&fit=crop&w=600&q=80',
                'link_url' => '/catalog/giay',
                'text_color' => '#ffffff',
                'position' => 'home_double',
                'is_global' => true,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'DI SẢN TÁI SINH',
                'description' => 'KHÁM PHÁ CÂU CHUYỆN',
                'image_path' => 'https://images.unsplash.com/photo-1550614000-4b95d4ed798a?auto=format&fit=crop&w=1920&q=80',
                'image_mobile_path' => 'https://images.unsplash.com/photo-1550614000-4b95d4ed798a?auto=format&fit=crop&w=800&q=80',
                'link_url' => '/magazine',
                'text_color' => '#ffffff',
                'position' => 'home_wide',
                'is_global' => true,
                'order' => 1,
                'is_active' => true,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }
    }
}
