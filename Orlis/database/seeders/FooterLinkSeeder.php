<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FooterLink;

class FooterLinkSeeder extends Seeder
{
    public function run()
    {
        FooterLink::truncate();

        $links = [
            // Cửa Hàng Orlis
            ['group_name' => 'Cửa Hàng Orlis', 'title' => 'Christian Orlis Couture', 'url' => '#', 'order' => 1],
            ['group_name' => 'Cửa Hàng Orlis', 'title' => 'Parfums Christian Orlis', 'url' => '#', 'order' => 2],
            ['group_name' => 'Cửa Hàng Orlis', 'title' => 'Tuyển dụng', 'url' => '#', 'order' => 3],
            // Dịch Vụ Khách Hàng
            ['group_name' => 'Dịch Vụ Khách Hàng', 'title' => 'Chính sách đổi trả', 'url' => '#', 'order' => 1],
            ['group_name' => 'Dịch Vụ Khách Hàng', 'title' => 'Hướng dẫn chọn size', 'url' => '#', 'order' => 2],
            ['group_name' => 'Dịch Vụ Khách Hàng', 'title' => 'Chính sách vận chuyển', 'url' => '#', 'order' => 3],
            // Nhà Orlis
            ['group_name' => 'Nhà Orlis', 'title' => 'Cam kết chất lượng', 'url' => '#', 'order' => 1],
            ['group_name' => 'Nhà Orlis', 'title' => 'Trách nhiệm xã hội', 'url' => '#', 'order' => 2],
            // Thuật Ngữ Pháp Lý
            ['group_name' => 'Thuật Ngữ Pháp Lý', 'title' => 'Chính sách bảo mật', 'url' => '#', 'order' => 1],
            ['group_name' => 'Thuật Ngữ Pháp Lý', 'title' => 'Điều khoản sử dụng', 'url' => '#', 'order' => 2],
            ['group_name' => 'Thuật Ngữ Pháp Lý', 'title' => 'Chính sách thanh toán', 'url' => '#', 'order' => 3],
        ];

        foreach ($links as $link) {
            FooterLink::create($link);
        }
    }
}
