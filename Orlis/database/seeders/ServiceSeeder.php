<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        Service::truncate();

        $services = [
            [
                'title' => 'Gói Quà Nghệ Thuật',
                'image_path' => 'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?auto=format&fit=crop&w=600&q=80',
                'link_url' => '#',
                'order' => 1,
            ],
            [
                'title' => 'Giao Hàng Hỏa Tốc',
                'image_path' => 'https://images.unsplash.com/photo-1580674285054-bed31e145f59?auto=format&fit=crop&w=600&q=80',
                'link_url' => '#',
                'order' => 2,
            ],
            [
                'title' => 'Đổi Trả Dễ Dàng',
                'image_path' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=600&q=80',
                'link_url' => '#',
                'order' => 3,
            ],
            [
                'title' => 'Tư Vấn Cá Nhân',
                'image_path' => 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=600&q=80',
                'link_url' => '#',
                'order' => 4,
            ],
            [
                'title' => 'Bảo Dưỡng Đồ Da',
                'image_path' => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=600&q=80',
                'link_url' => '#',
                'order' => 5,
            ]
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
