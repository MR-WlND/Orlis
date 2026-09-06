<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run()
    {
        Post::truncate();

        $posts = [
            [
                'title' => 'Sự Trở Lại Của Những Thiết Kế Cổ Điển',
                'excerpt' => 'Một cái nhìn sâu sắc về cách các thiết kế thời trang cổ điển đang tái xuất và làm chủ xu hướng năm nay.',
                'slug' => Str::slug('Sự Trở Lại Của Những Thiết Kế Cổ Điển'),
                'content' => '<p>Năm nay đánh dấu sự trở lại mạnh mẽ của các thiết kế cổ điển...</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1550614000-4b95d4ed798a?auto=format&fit=crop&w=800&q=80',
                'status' => 'published',
                'department' => 'fashion',
                'user_id' => 1,
            ],
            [
                'title' => 'Nghệ Thuật Của Hương Thơm',
                'excerpt' => 'Khám phá bí mật đằng sau những chai nước hoa biểu tượng mang dấu ấn Orlis.',
                'slug' => Str::slug('Nghệ Thuật Của Hương Thơm'),
                'content' => '<p>Mỗi giọt nước hoa là một câu chuyện...</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=800&q=80',
                'status' => 'published',
                'department' => 'beauty',
                'user_id' => 1,
            ],
            [
                'title' => 'Phía Sau Hậu Trường Show Diễn Mùa Thu',
                'excerpt' => 'Cùng chiêm ngưỡng những khoảnh khắc đáng nhớ nhất trước giờ G của show diễn thời trang lớn nhất trong năm.',
                'slug' => Str::slug('Phía Sau Hậu Trường Show Diễn Mùa Thu'),
                'content' => '<p>Show diễn mùa thu năm nay đã mang đến...</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=800&q=80',
                'status' => 'published',
                'department' => 'fashion',
                'user_id' => 1,
            ]
        ];

        foreach ($posts as $post) {
            Post::create($post);
        }
    }
}
