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

        return view('client.home', compact('homeHero', 'homeDouble', 'homeWide'));
    }

    public function beauty()
    {
        $banners = Banner::active()->orderBy('order')->get();
        
        $beautyHero = $banners->where('position', 'beauty_hero');
        $beautyDouble = $banners->where('position', 'beauty_double');
        $beautyWide = $banners->where('position', 'beauty_wide');

        return view('client.perfume', compact('beautyHero', 'beautyDouble', 'beautyWide'));
    }

}
