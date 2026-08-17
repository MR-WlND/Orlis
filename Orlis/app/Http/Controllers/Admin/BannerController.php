<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('position')->orderBy('order')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        // Lấy tất cả danh mục theo dạng cây (phẳng) để admin dễ chọn
        $categories = Category::all();
        return view('admin.banners.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|max:5120',
            'image_mobile' => 'nullable|image|max:5120',
            'link_url' => 'nullable|string|max:255',
            'link_target' => 'required|in:_self,_blank',
            'text_color' => 'nullable|string|max:50',
            'position' => 'required|string|max:50',
            'order' => 'required|integer',
            'is_active' => 'boolean',
            'is_global' => 'boolean',
            'category_ids' => 'nullable|array',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
        ]);

        $imagePath = $request->file('image')->store('banners', 'public');
        
        $imageMobilePath = null;
        if ($request->hasFile('image_mobile')) {
            $imageMobilePath = $request->file('image_mobile')->store('banners', 'public');
        }

        Banner::create([
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imagePath,
            'image_mobile_path' => $imageMobilePath,
            'link_url' => $request->link_url,
            'link_target' => $request->link_target,
            'text_color' => $request->text_color ?? '#FFFFFF',
            'position' => $request->position,
            'order' => $request->order,
            'is_active' => $request->has('is_active'),
            'is_global' => $request->has('is_global'),
            'category_ids' => $request->category_ids,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Đã thêm Banner thành công!');
    }

    public function edit(Banner $banner)
    {
        $categories = Category::all();
        return view('admin.banners.edit', compact('banner', 'categories'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
            'image_mobile' => 'nullable|image|max:5120',
            'link_url' => 'nullable|string|max:255',
            'link_target' => 'required|in:_self,_blank',
            'text_color' => 'nullable|string|max:50',
            'position' => 'required|string|max:50',
            'order' => 'required|integer',
            'is_active' => 'boolean',
            'is_global' => 'boolean',
            'category_ids' => 'nullable|array',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'link_url' => $request->link_url,
            'link_target' => $request->link_target,
            'text_color' => $request->text_color ?? '#FFFFFF',
            'position' => $request->position,
            'order' => $request->order,
            'is_active' => $request->has('is_active'),
            'is_global' => $request->has('is_global'),
            'category_ids' => $request->category_ids,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ];

        if ($request->hasFile('image')) {
            if (Storage::disk('public')->exists($banner->image_path)) {
                Storage::disk('public')->delete($banner->image_path);
            }
            $data['image_path'] = $request->file('image')->store('banners', 'public');
        }

        if ($request->hasFile('image_mobile')) {
            if ($banner->image_mobile_path && Storage::disk('public')->exists($banner->image_mobile_path)) {
                Storage::disk('public')->delete($banner->image_mobile_path);
            }
            $data['image_mobile_path'] = $request->file('image_mobile')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', 'Đã cập nhật Banner thành công!');
    }

    public function destroy(Banner $banner)
    {
        if (Storage::disk('public')->exists($banner->image_path)) {
            Storage::disk('public')->delete($banner->image_path);
        }
        
        if ($banner->image_mobile_path && Storage::disk('public')->exists($banner->image_mobile_path)) {
            Storage::disk('public')->delete($banner->image_mobile_path);
        }
        
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Đã xóa Banner thành công!');
    }
}
