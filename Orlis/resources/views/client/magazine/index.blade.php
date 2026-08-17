@extends('layouts.client')

@section('title', 'Tạp chí & Sự kiện - Orlis')

@section('content')
<div class="magazine-page">
    <div class="magazine-header">
        <h1>Tin tức và Sự kiện</h1>
        <p>Theo dõi những tin tức mới nhất từ Orlis và chiêm ngưỡng tất cả các ngôi sao mặc đồ Orlis<br>từ các sự kiện trước đây.</p>
    </div>

    @if($posts->count() > 0)
        <div class="magazine-grid">
            @foreach($posts as $post)
            <a href="{{ route('magazine.show', $post->slug) }}" class="magazine-card">
                <div class="magazine-card-img">
                    <img src="{{ $post->thumbnail ? (str_starts_with($post->thumbnail, 'http') ? $post->thumbnail : Storage::url($post->thumbnail)) : 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=800&q=80' }}" alt="{{ $post->title }}">
                </div>
                <div class="magazine-card-body">
                    <h3 class="magazine-card-title">{{ $post->title }}</h3>
                    <p class="magazine-card-summary">{{ $post->summary ?? Str::limit(strip_tags($post->content), 120) }}</p>
                    <span class="magazine-card-link">Khám phá</span>
                </div>
            </a>
            @endforeach
        </div>

        <div class="magazine-pagination">
            {{ $posts->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 100px 0; color: #666;">
            <p>Chưa có bài viết nào được xuất bản.</p>
        </div>
    @endif
</div>
@endsection
