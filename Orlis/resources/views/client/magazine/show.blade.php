@extends('layouts.client')

@section('title', $post->title . ' - Orlis')

@section('content')
<div class="article-page">
    <div class="article-hero">
        <img src="{{ $post->thumbnail ? (str_starts_with($post->thumbnail, 'http') ? $post->thumbnail : Storage::url($post->thumbnail)) : 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=1600&q=80' }}" alt="{{ $post->title }}">
        <div class="article-hero-content">
            <div class="article-meta">
                @if($post->category)
                    {{ $post->category->name }} • 
                @endif
                {{ $post->published_at ? $post->published_at->format('d/m/Y') : $post->created_at->format('d/m/Y') }}
            </div>
            <h1 class="article-title">{{ $post->title }}</h1>
        </div>
    </div>

    <div class="article-content">
        {!! $post->content !!}
    </div>

    @if(isset($relatedPosts) && $relatedPosts->count() > 0)
    <div class="related-articles">
        <h2 class="related-title">Bài viết liên quan</h2>
        <div class="related-grid">
            @foreach($relatedPosts as $related)
            <a href="{{ route('magazine.show', $related->slug) }}" class="magazine-card">
                <div class="magazine-card-img" style="aspect-ratio: 3/2;">
                    <img src="{{ $related->thumbnail ? (str_starts_with($related->thumbnail, 'http') ? $related->thumbnail : Storage::url($related->thumbnail)) : 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=800&q=80' }}" alt="{{ $related->title }}">
                </div>
                <h3 class="magazine-card-title" style="font-size: 16px;">{{ $related->title }}</h3>
                <div class="magazine-card-meta">
                    {{ $related->published_at ? $related->published_at->format('d/m/Y') : $related->created_at->format('d/m/Y') }}
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
