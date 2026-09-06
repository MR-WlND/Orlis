<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::active()->orderBy('order')->get();
        
        $homeHero = $banners->where('position', 'home_hero')->first();
        $homeDouble = $banners->where('position', 'home_double')->take(2);
        $homeWide = $banners->where('position', 'home_wide')->first();

        $recentPosts = \App\Models\Post::where('status', 'published')->orderBy('created_at', 'desc')->take(3)->get();
        
        $services = \App\Models\Service::where('is_active', true)->orderBy('order')->get();

        return view('client.home', compact('homeHero', 'homeDouble', 'homeWide', 'recentPosts', 'services'));
    }

    public function beauty()
    {
        $banners = Banner::active()->orderBy('order')->get();
        
        $beautyHero = $banners->where('position', 'beauty_hero');
        $beautyDouble = $banners->where('position', 'beauty_double');
        $beautyWide = $banners->where('position', 'beauty_wide');

        // Lấy danh mục nước hoa (ID = 3 theo seeder, hoặc name LIKE '%nước hoa%')
        $perfumeCategory = \App\Models\Category::where('name', 'like', '%Nước hoa%')->first();
        
        $recommendedPerfumes = collect();
        $bestSellingPerfumes = collect();
        
        if ($perfumeCategory) {
            $recommendedPerfumes = \App\Models\Product::where('category_id', $perfumeCategory->id)
                ->where('is_active', true)
                ->inRandomOrder()
                ->take(4)
                ->get();
                
            $bestSellingPerfumes = \App\Models\Product::where('category_id', $perfumeCategory->id)
                ->where('is_active', true)
                ->orderBy('price', 'desc') // Tạm thời dùng giá cao nhất làm bán chạy
                ->take(4)
                ->get();
        }

        return view('client.perfume', compact('beautyHero', 'beautyDouble', 'beautyWide', 'recommendedPerfumes', 'bestSellingPerfumes'));
    }

}
