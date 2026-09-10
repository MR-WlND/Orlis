<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CatalogController extends Controller
{
    public function index(Request $request, $slug = null)
    {
        if ($slug && str_contains($slug, 'nuoc-hoa-lam-dep-nuoc-hoa')) {
            return view('client.perfume');
        }

        $categoryBanner = null;
        $category = null;
        $isParentCategory = false;
        $subcategoriesData = [];

        $query = Product::where('is_active', true);

        // Tải trước tất cả banners 1 lần duy nhất (cache 5 phút)
        $allBanners = Cache::remember('banners_category_header', 300, function () {
            return Banner::active()->position('category_header')->orderBy('order')->get();
        });

        // Filter by category
        if ($slug) {
            $category = Category::with('children.products')->where('slug', $slug)->first();
            if ($category) {
                $categoryIds = [];
                $current = $category;
                while ($current) {
                    $categoryIds[] = (string) $current->id;
                    $current = $current->parent;
                }

                // Tìm banner trong danh sách đã load sẵn (không query DB lại)
                $categoryBanner = $allBanners->first(function ($banner) use ($categoryIds) {
                    foreach ($categoryIds as $id) {
                        $ids = is_array($banner->category_ids) ? $banner->category_ids : json_decode($banner->category_ids ?? '[]', true);
                        if (in_array($id, array_map('strval', $ids))) return true;
                    }
                    return false;
                });

                if ($category->children->count() > 0) {
                    $isParentCategory = true;
                    // Nếu danh mục cha và không có filter, hiện các khối danh mục con
                    if (!$request->hasAny(['search', 'min_price', 'max_price', 'sort'])) {
                        foreach ($category->children as $child) {
                            // Tìm banner trong danh sách đã load sẵn
                            $childBanner = $allBanners->first(function ($banner) use ($child) {
                                $ids = is_array($banner->category_ids) ? $banner->category_ids : json_decode($banner->category_ids ?? '[]', true);
                                return in_array((string)$child->id, array_map('strval', $ids));
                            });
                            $subcategoriesData[] = [
                                'category' => $child,
                                'banner'   => $childBanner,
                                // Products đã eager-load từ Category::with('children.products')
                                'products' => $child->products->where('is_active', true)->take(8),
                            ];
                        }
                    }
                }

                // Lấy tất cả ID danh mục con để lọc sản phẩm
                $allCategoryIds = $this->getAllCategoryIds($category);
                $query->whereIn('category_id', $allCategoryIds);
            }
        } else {
            // Không chọn danh mục - cache root categories
            $rootCategories = Cache::remember('root_categories_with_products', 300, function () {
                return Category::with('products')->whereNull('parent_id')->get();
            });
            if ($rootCategories->count() > 0 && !$request->hasAny(['search', 'min_price', 'max_price', 'sort'])) {
                $isParentCategory = true;
                foreach ($rootCategories as $child) {
                    $childBanner = $allBanners->first(function ($banner) use ($child) {
                        $ids = is_array($banner->category_ids) ? $banner->category_ids : json_decode($banner->category_ids ?? '[]', true);
                        return in_array((string)$child->id, array_map('strval', $ids));
                    });
                    $subcategoriesData[] = [
                        'category' => $child,
                        'banner'   => $childBanner,
                        'products' => $child->products->where('is_active', true)->take(8),
                    ];
                }
            }
        }

        if (!$categoryBanner) {
            $categoryBanner = $allBanners->where('is_global', true)->first() ?? $allBanners->first();
        }

        // Apply Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
            $isParentCategory = false; // Force list view if searching
        }

        // Apply Price Filters
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
            $isParentCategory = false;
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
            $isParentCategory = false;
        }

        // Apply Sorting
        if ($request->filled('sort')) {
            $isParentCategory = false;
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'best_selling':
                    $query->orderBy('rating_cache', 'desc'); // Assuming rating correlates with best selling for now
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = collect();
        if (!$isParentCategory) {
            $products = $query->paginate(16)->appends($request->query());
        }

        return view('client.catalog', compact('categoryBanner', 'slug', 'category', 'isParentCategory', 'subcategoriesData', 'products'));
    }

    private function getAllCategoryIds($category)
    {
        $ids = [$category->id];
        foreach ($category->children as $child) {
            $ids = array_merge($ids, $this->getAllCategoryIds($child));
        }
        return $ids;
    }
}
