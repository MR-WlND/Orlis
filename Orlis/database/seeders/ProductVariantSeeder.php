<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductVariantSeeder extends Seeder
{
    /**
     * Biến thể theo danh mục sản phẩm
     */
    private array $fashionConfig = [
        'colors' => [
            ['name' => 'Đen', 'hex' => '#1a1a1a'],
            ['name' => 'Trắng Ngà', 'hex' => '#f5f0e8'],
            ['name' => 'Be Camel', 'hex' => '#c9a96e'],
            ['name' => 'Xanh Navy', 'hex' => '#1b2a4a'],
            ['name' => 'Đỏ Bordeaux', 'hex' => '#722f37'],
        ],
        'sizes' => ['XS', 'S', 'M', 'L', 'XL'],
    ];

    private array $beautyConfig = [
        'colors' => [
            ['name' => 'Hồng Nude', 'hex' => '#e8b4a0'],
            ['name' => 'Đỏ Cherry', 'hex' => '#8b1a2f'],
            ['name' => 'Cam Đào', 'hex' => '#e8734a'],
            ['name' => 'Tím Mận', 'hex' => '#6b2d5e'],
            ['name' => 'Hồng Baby', 'hex' => '#f4a7b9'],
        ],
        'sizes' => ['3ml', '5ml', '10ml', '30ml'],
    ];

    private array $accessoryConfig = [
        'colors' => [
            ['name' => 'Vàng Gold', 'hex' => '#c9a96e'],
            ['name' => 'Bạc Silver', 'hex' => '#b0b0b0'],
            ['name' => 'Đen', 'hex' => '#1a1a1a'],
            ['name' => 'Nâu Da', 'hex' => '#8B4513'],
        ],
        'sizes' => ['One Size', 'S/M', 'M/L'],
    ];

    private array $perfumeConfig = [
        'colors' => [
            ['name' => 'Original', 'hex' => '#c9a96e'],
        ],
        'sizes' => ['30ml', '50ml', '100ml'],
    ];

    public function run(): void
    {
        $this->command->info('Bắt đầu tạo biến thể sản phẩm...');

        $products = Product::with('category')->get();
        $count = 0;

        foreach ($products as $product) {
            $categoryName = strtolower($product->category?->name ?? '');
            $config = $this->getConfig($categoryName);

            $colors = $config['colors'];
            $sizes  = $config['sizes'];

            // Cập nhật variant mặc định (rỗng) với màu và size đầu tiên
            $defaultVariant = ProductVariant::where('product_id', $product->id)
                ->whereNull('color')
                ->first();

            if ($defaultVariant) {
                $defaultColor = $colors[0];
                $defaultSize  = $sizes[0];
                $defaultVariant->update([
                    'attributes' => [
                        'color'      => $defaultColor['hex'],
                        'size'       => $defaultSize,
                        'color_name' => $defaultColor['name'],
                    ],
                ]);
                $count++;
            }

            // Chọn 2-3 màu và 2-4 size để tạo thêm biến thể mới
            $selectedColors = collect($colors)->shuffle()->take(rand(2, 3))->values();
            $selectedSizes  = collect($sizes)->shuffle()->take(rand(2, min(4, count($sizes))))->sort()->values();

            foreach ($selectedColors as $color) {
                foreach ($selectedSizes as $size) {
                    // Kiểm tra đã có variant cùng màu và size chưa
                    $exists = ProductVariant::where('product_id', $product->id)
                        ->where('color', $color['hex'])
                        ->where('size', $size)
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    $sku = strtoupper(Str::random(6)) . '-' . Str::slug($color['name']) . '-' . Str::slug($size);

                    $sizeMultiplier = match ($size) {
                        'XS', '3ml'        => 0.9,
                        'S', '5ml', '50ml' => 0.95,
                        'M', '10ml'        => 1.0,
                        'L', 'M/L'         => 1.05,
                        'XL', '100ml'      => 1.1,
                        default            => 1.0,
                    };

                    $basePrice     = $product->sale_price ?? $product->price;
                    $priceOverride = round($basePrice * $sizeMultiplier / 1000) * 1000;

                    ProductVariant::create([
                        'product_id'     => $product->id,
                        'sku'            => $sku,
                        'price_override' => abs($priceOverride - $product->price) > 1000 ? $priceOverride : null,
                        'attributes'     => [
                            'color'      => $color['hex'],
                            'size'       => $size,
                            'color_name' => $color['name'],
                        ],
                    ]);

                    $count++;
                }
            }
        }

        $this->command->info("✅ Đã xử lý {$count} biến thể cho " . $products->count() . " sản phẩm.");
    }

    private function getConfig(string $categoryName): array
    {
        if (str_contains($categoryName, 'nước hoa') || str_contains($categoryName, 'perfume') || str_contains($categoryName, 'fragrance')) {
            return $this->perfumeConfig;
        }

        if (
            str_contains($categoryName, 'trang điểm') ||
            str_contains($categoryName, 'beauty') ||
            str_contains($categoryName, 'makeup') ||
            str_contains($categoryName, 'chăm sóc da') ||
            str_contains($categoryName, 'skincare') ||
            str_contains($categoryName, 'làm sạch') ||
            str_contains($categoryName, 'dưỡng') ||
            str_contains($categoryName, 'mặt nạ')
        ) {
            return $this->beautyConfig;
        }

        if (
            str_contains($categoryName, 'túi') ||
            str_contains($categoryName, 'phụ kiện') ||
            str_contains($categoryName, 'accessory') ||
            str_contains($categoryName, 'trang sức') ||
            str_contains($categoryName, 'giày') ||
            str_contains($categoryName, 'khăn') ||
            str_contains($categoryName, 'mũ')
        ) {
            return $this->accessoryConfig;
        }

        // Mặc định: thời trang
        return $this->fashionConfig;
    }
}
