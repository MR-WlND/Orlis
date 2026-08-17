<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Admin;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kiểm tra xem có admin nào chưa, nếu chưa thì tạo 1 admin tạm
        $admin = Admin::first();
        if (!$admin) {
            $admin = Admin::create([
                'name' => 'Admin Orlis',
                'email' => 'admin@orlis.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Tạo danh mục nếu chưa có
        $catNews = PostCategory::firstOrCreate(['name' => 'Tin tức'], ['slug' => 'tin-tuc']);
        $catEvent = PostCategory::firstOrCreate(['name' => 'Sự kiện'], ['slug' => 'su-kien']);
        $catMag = PostCategory::firstOrCreate(['name' => 'Tạp chí'], ['slug' => 'tap-chi']);

        $fashionImages = [
            'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=1600&q=80',
            'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?auto=format&fit=crop&w=1600&q=80',
            'https://images.unsplash.com/photo-1550639525-c97d455acf70?auto=format&fit=crop&w=1600&q=80',
            'https://images.unsplash.com/photo-1445205170230-053b83016050?auto=format&fit=crop&w=1600&q=80',
            'https://images.unsplash.com/photo-1469334031218-e382a71b716b?auto=format&fit=crop&w=1600&q=80',
            'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=1600&q=80',
            'https://images.unsplash.com/photo-1509631179647-0177331693ae?auto=format&fit=crop&w=1600&q=80',
            'https://images.unsplash.com/photo-1485230895905-ef2030f0fbfb?auto=format&fit=crop&w=1600&q=80',
            'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=1600&q=80',
            'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=1600&q=80'
        ];

        $fashionTitles = [
            'BST Thu Đông 2026: Nét Cổ Điển Giao Thoa Hiện Đại',
            'Phong Cách Street Style Lên Ngôi Cùng Túi Xách Puffy',
            'Sự Tái Sinh Của Chất Liệu Tweed Huyền Thoại',
            'Khám Phá Hậu Trường Show Diễn Xuân Hè Tại Milan',
            '5 Phụ Kiện Không Thể Thiếu Cho Cô Nàng Công Sở',
            'Xu Hướng Thời Trang Tối Giản (Minimalism) Trở Lại',
            'Bộ Sưu Tập Kính Mát Đậm Chất Retro Thập Niên 70',
            'Bí Quyết Phối Đồ Layering Đỉnh Cao Cho Ngày Gió Mùa',
            'Chân Dung Giám Đốc Sáng Tạo Mới Của Orlis',
            'Hành Trình Tạo Ra Chiếc Túi Signature Của Hãng'
        ];

        $beautyImages = [
            'https://images.unsplash.com/photo-1594035987173-16a5d9333919?auto=format&fit=crop&w=1600&q=80',
            'https://images.unsplash.com/photo-1590736969955-71cc94801759?auto=format&fit=crop&w=1600&q=80',
            'https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=1600&q=80',
            'https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=1600&q=80',
            'https://images.unsplash.com/photo-1617897903246-719242758050?auto=format&fit=crop&w=1600&q=80',
            'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?auto=format&fit=crop&w=1600&q=80',
            'https://images.unsplash.com/photo-1571781564947-68725838421c?auto=format&fit=crop&w=1600&q=80',
            'https://images.unsplash.com/photo-1599305090598-fe179d501227?auto=format&fit=crop&w=1600&q=80',
            'https://images.unsplash.com/photo-1570194065650-d73dfc9e830c?auto=format&fit=crop&w=1600&q=80',
            'https://images.unsplash.com/photo-1615397322928-857eec077978?auto=format&fit=crop&w=1600&q=80'
        ];

        $beautyTitles = [
            'Sự kiện Ra mắt Nước hoa "Midnight Rose" Độc Quyền',
            'Bí Quyết Chăm Sóc Da Mùa Hanh Khô Hoàn Hảo',
            'Lễ Kỷ Niệm 10 Năm Dòng Nước Hoa Cổ Điển',
            '3 Bước Dưỡng Trắng Sáng Bật Tông Mỗi Sáng',
            'Review Dòng Son Môi Mới Nhất Mùa Lễ Hội',
            'Đánh Thức Mọi Giác Quan Cùng Hương Thơm Mùa Hè',
            'Nghệ Thuật Chiết Xuất Tinh Dầu Hoa Hồng Tại Grasse',
            'Chu Trình Skincare Ban Đêm Chống Lão Hóa Của Các Ngôi Sao',
            'Khám Phá Bộ Sưu Tập Trang Điểm Mắt Ánh Kim',
            'Sức Mạnh Của Hyaluronic Acid Trong Việc Phục Hồi Da'
        ];

        $posts = [];

        // Tạo 10 bài Fashion
        for ($i = 0; $i < 10; $i++) {
            $posts[] = [
                'department' => 'fashion',
                'title' => $fashionTitles[$i],
                'category_id' => $i % 3 == 0 ? $catEvent->id : $catMag->id,
                'summary' => 'Theo dõi những xu hướng và tin tức mới nhất từ hệ sinh thái Thời trang Orlis mang đậm dấu ấn phong cách của riêng bạn.',
                'content' => '<p>Đây là nội dung chi tiết bài viết thời trang. Trải nghiệm sự pha trộn giữa vẻ đẹp di sản cổ điển và phong cách đương đại qua từng đường kim mũi chỉ.</p><h2>Cảm Hứng Thiết Kế</h2><p>Mỗi tác phẩm đều mang đậm dấu ấn cá nhân, giúp tôn lên vẻ đẹp tự nhiên và khí chất người mặc. Khám phá các thiết kế mới và để bản thân được tỏa sáng.</p>',
                'thumbnail' => $fashionImages[$i],
            ];
        }

        // Tạo 10 bài Beauty
        for ($i = 0; $i < 10; $i++) {
            $posts[] = [
                'department' => 'beauty',
                'title' => $beautyTitles[$i],
                'category_id' => $i % 2 == 0 ? $catNews->id : $catMag->id,
                'summary' => 'Khám phá bí mật làm đẹp đằng sau những mùi hương nước hoa tinh tế và quy trình chăm sóc da đẳng cấp từ các chuyên gia.',
                'content' => '<p>Đây là nội dung chi tiết bài viết làm đẹp. Làn da của bạn xứng đáng được nâng niu bằng những dưỡng chất thuần khiết nhất từ thiên nhiên.</p><h2>Sự Giao Thoa Của Hương Thơm</h2><p>Những nốt hương độc bản được chế tác bởi các bậc thầy điều chế nước hoa hàng đầu thế giới, mang đến sự quyến rũ đầy bí ẩn khó chối từ.</p>',
                'thumbnail' => $beautyImages[$i],
            ];
        }

        foreach ($posts as $post) {
            Post::firstOrCreate(
                ['slug' => Str::slug($post['title'])],
                [
                    'admin_id' => $admin->id,
                    'category_id' => $post['category_id'],
                    'department' => $post['department'],
                    'title' => $post['title'],
                    'summary' => $post['summary'],
                    'content' => $post['content'],
                    'thumbnail' => $post['thumbnail'],
                    'status' => 'published',
                    'meta_title' => $post['title'],
                    'published_at' => now()->subDays(rand(1, 30)),
                ]
            );
        }
    }
}
