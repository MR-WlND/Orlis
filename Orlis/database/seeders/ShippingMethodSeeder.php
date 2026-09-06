<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShippingMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\ShippingMethod::create([
            'name' => 'Giao hàng Bảo an Orlis Privé',
            'description' => 'Miễn phí vận chuyển. Đóng gói hộp quà Maison Orlis đặc biệt & kiểm tra sản phẩm tận tay.',
            'cost' => 0,
            'is_active' => true,
            'min_order_amount_for_free_shipping' => null,
        ]);

        \App\Models\ShippingMethod::create([
            'name' => 'Giao hàng Hỏa tốc Couture Express (Trong ngày)',
            'description' => 'Dành riêng cho khu vực Nội thành Hà Nội & TP. HCM. Khách hàng lựa chọn khung giờ nhận hàng.',
            'cost' => 150000,
            'is_active' => true,
            'min_order_amount_for_free_shipping' => 10000000,
        ]);
    }
}
