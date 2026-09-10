<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Banner;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Cache::remember('banners_home_active', 300, function () {
            return Banner::active()->orderBy('order')->get();
        });

        $homeHero   = $banners->where('position', 'home_hero')->first();
        $homeDouble = $banners->where('position', 'home_double')->take(2);
        $homeWide   = $banners->where('position', 'home_wide')->first();

        $recentPosts = Cache::remember('home_recent_posts', 300, function () {
            return \App\Models\Post::where('status', 'published')->orderBy('created_at', 'desc')->take(3)->get();
        });

        $services = Cache::remember('home_services', 300, function () {
            return \App\Models\Service::where('is_active', true)->orderBy('order')->get();
        });

        return view('client.home', compact('homeHero', 'homeDouble', 'homeWide', 'recentPosts', 'services'));
    }

    public function beauty()
    {
        $banners = Cache::remember('banners_beauty_active', 300, function () {
            return Banner::active()->orderBy('order')->get();
        });

        $beautyHero   = $banners->where('position', 'beauty_hero');
        $beautyDouble = $banners->where('position', 'beauty_double');
        $beautyWide   = $banners->where('position', 'beauty_wide');

        $perfumeCategory = Cache::remember('category_nuoc_hoa', 600, function () {
            return \App\Models\Category::where('name', 'like', '%Nước hoa%')->first();
        });

        $recommendedPerfumes = collect();
        $bestSellingPerfumes = collect();

        if ($perfumeCategory) {
            $recommendedPerfumes = \App\Models\Product::where('category_id', $perfumeCategory->id)
                ->where('is_active', true)
                ->inRandomOrder()
                ->take(4)
                ->get();

            $bestSellingPerfumes = Cache::remember('beauty_best_selling_' . $perfumeCategory->id, 300, function () use ($perfumeCategory) {
                return \App\Models\Product::where('category_id', $perfumeCategory->id)
                    ->where('is_active', true)
                    ->orderBy('price', 'desc')
                    ->take(4)
                    ->get();
            });
        }

        if ($recommendedPerfumes->isEmpty()) {
            $recommendedPerfumes = \App\Models\Product::where('is_active', true)->inRandomOrder()->take(4)->get();
        }
        if ($bestSellingPerfumes->isEmpty()) {
            $bestSellingPerfumes = \App\Models\Product::where('is_active', true)->orderBy('price', 'desc')->take(4)->get();
        }

        return view('client.perfume', compact('beautyHero', 'beautyDouble', 'beautyWide', 'recommendedPerfumes', 'bestSellingPerfumes'));
    }
}
