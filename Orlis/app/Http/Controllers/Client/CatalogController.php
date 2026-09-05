<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

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

        // Filter by category
        if ($slug) {
            $category = Category::with('children')->where('slug', $slug)->first();
            if ($category) {
                $categoryIds = [];
                $current = $category;
                while ($current) {
                    $categoryIds[] = (string) $current->id;
                    $current = $current->parent;
                }

                $bannerQuery = Banner::active()->position('category_header')->where(function($q) use ($categoryIds) {
                    foreach ($categoryIds as $id) {
                        $q->orWhereJsonContains('category_ids', (string)$id);
                    }
                });
                $categoryBanner = $bannerQuery->orderBy('order')->first();

                if ($category->children->count() > 0) {
                    $isParentCategory = true;
                    // If parent category and NO search/filter, show subcategory blocks
                    if (!$request->hasAny(['search', 'min_price', 'max_price', 'sort'])) {
                        foreach ($category->children as $child) {
                            $childBanner = Banner::active()->position('category_header')->whereJsonContains('category_ids', (string)$child->id)->first();
                            $subcategoriesData[] = [
                                'category' => $child,
                                'banner' => $childBanner,
                                'products' => $child->products()->where('is_active', true)->take(8)->get()
                            ];
                        }
                    }
                }
                
                // Get all descendant category IDs for product filtering
                $allCategoryIds = $this->getAllCategoryIds($category);
                $query->whereIn('category_id', $allCategoryIds);
            }
        } else {
            // No category selected
            $rootCategories = Category::whereNull('parent_id')->get();
            if ($rootCategories->count() > 0 && !$request->hasAny(['search', 'min_price', 'max_price', 'sort'])) {
                $isParentCategory = true;
                foreach ($rootCategories as $child) {
                    $childBanner = Banner::active()->position('category_header')->whereJsonContains('category_ids', (string)$child->id)->first();
                    $subcategoriesData[] = [
                        'category' => $child,
                        'banner' => $childBanner,
                        'products' => $child->products()->where('is_active', true)->take(8)->get()
                    ];
                }
            }
        }

        if (!$categoryBanner) {
            $categoryBanner = Banner::active()->position('category_header')->where('is_global', true)->orderBy('order')->first();
        }
        if (!$categoryBanner) {
            $categoryBanner = Banner::active()->position('category_header')->orderBy('order')->first();
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
