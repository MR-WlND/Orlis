<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $department = $request->query('department', session('department', 'fashion'));
        // Cập nhật lại session department nếu có truyền qua url
        if ($request->has('department')) {
            session(['department' => $department]);
        }

        $posts = Post::with('author')
            ->where('status', 'published')
            ->where('department', $department)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('client.magazine.index', compact('posts'));
    }

    public function show($slug)
    {
        $post = Post::with('author')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Lấy các bài viết liên quan
        $relatedPosts = Post::where('id', '!=', $post->id)
            ->where('status', 'published')
            ->where('department', $post->department)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('client.magazine.show', compact('post', 'relatedPosts'));
    }
}
