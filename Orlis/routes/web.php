<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\RoleLoginController;
use App\Http\Controllers\Auth\SocialAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/beauty', [\App\Http\Controllers\HomeController::class, 'beauty'])->name('home.beauty');

Route::get('/cart', function () {
    return view('client.cart');
})->name('cart');

Route::get('/catalog/{slug?}', function ($slug = null) {
    if ($slug && str_contains($slug, 'nuoc-hoa-lam-dep-nuoc-hoa')) {
        return view('client.perfume');
    }

    $categoryBanner = null;
    $category = null;
    $isParentCategory = false;
    $subcategoriesData = [];
    $products = collect();

    if ($slug) {
        $category = \App\Models\Category::with('children')->where('slug', $slug)->first();
        if ($category) {
            $categoryIds = [];
            $current = $category;
            while ($current) {
                $categoryIds[] = (string) $current->id;
                $current = $current->parent;
            }

            $query = \App\Models\Banner::active()->position('category_header')->where(function($q) use ($categoryIds) {
                foreach ($categoryIds as $id) {
                    $q->orWhereJsonContains('category_ids', (string)$id);
                }
            });
            $categoryBanner = $query->orderBy('order')->first();

            // Phân nhánh logic cho Menu cha và Menu con
            if ($category->children->count() > 0) {
                $isParentCategory = true;
                foreach ($category->children as $child) {
                    $childBanner = \App\Models\Banner::active()->position('category_header')->whereJsonContains('category_ids', (string)$child->id)->first();
                    $subcategoriesData[] = [
                        'category' => $child,
                        'banner' => $childBanner,
                        'products' => $child->products()->where('is_active', true)->take(8)->get()
                    ];
                }
            } else {
                $products = $category->products()->where('is_active', true)->paginate(16);
            }
        }
    } else {
        $rootCategories = \App\Models\Category::whereNull('parent_id')->get();
        if ($rootCategories->count() > 0) {
            $isParentCategory = true;
            foreach ($rootCategories as $child) {
                $childBanner = \App\Models\Banner::active()->position('category_header')->whereJsonContains('category_ids', (string)$child->id)->first();
                $subcategoriesData[] = [
                    'category' => $child,
                    'banner' => $childBanner,
                    'products' => $child->products()->where('is_active', true)->take(8)->get()
                ];
            }
        } else {
            $products = \App\Models\Product::where('is_active', true)->paginate(16);
        }
    }

    if (!$categoryBanner) {
        $categoryBanner = \App\Models\Banner::active()->position('category_header')->where('is_global', true)->orderBy('order')->first();
    }
    if (!$categoryBanner) {
        $categoryBanner = \App\Models\Banner::active()->position('category_header')->orderBy('order')->first();
    }

    return view('client.catalog', compact('categoryBanner', 'slug', 'category', 'isParentCategory', 'subcategoriesData', 'products'));
})->name('catalog');

Route::get('/product/{id?}', function () {
    return view('client.product');
})->name('product');

Route::get('/perfume', function () {
    return view('client.perfume');
})->name('perfume');

Route::get('/login/{role}', [RoleLoginController::class, 'showLoginForm'])->name('role.login');
Route::post('/login/{role}', [RoleLoginController::class, 'login'])->name('role.login.post');
Route::post('/logout', [RoleLoginController::class, 'logout'])->name('logout');

Route::get('/register', fn() => redirect('/'))->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');
Route::get('/admin', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::resource('admin/users', \App\Http\Controllers\Admin\UserController::class, ['as' => 'admin']);
Route::resource('admin/admins', \App\Http\Controllers\Admin\AdminAccountController::class, ['as' => 'admin']);
Route::resource('admin/categories', \App\Http\Controllers\Admin\CategoryController::class, ['as' => 'admin']);
Route::resource('admin/banners', \App\Http\Controllers\Admin\BannerController::class, ['as' => 'admin']);
Route::resource('admin/products', \App\Http\Controllers\Admin\ProductController::class, ['as' => 'admin']);
Route::resource('admin/posts', \App\Http\Controllers\Admin\PostController::class, ['as' => 'admin']);
Route::resource('admin/products.variants', \App\Http\Controllers\Admin\ProductVariantController::class, ['as' => 'admin']);
Route::resource('admin/coupons', \App\Http\Controllers\Admin\CouponController::class, ['as' => 'admin']);
Route::resource('admin/reviews', \App\Http\Controllers\Admin\ReviewController::class, ['as' => 'admin'])->only(['index', 'destroy']);
Route::patch('admin/reviews/{review}/status', [\App\Http\Controllers\Admin\ReviewController::class, 'updateStatus'])->name('admin.reviews.updateStatus');
Route::middleware(['auth', 'role:manager'])->group(function () {
    Route::get('/manager', fn() => 'Quản lý vận hành');
});

Route::middleware(['auth', 'role:staff'])->group(function () {
    Route::get('/staff', fn() => 'Xử lý đơn hàng');
});

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer', fn() => 'Mua hàng và theo dõi đơn');
    Route::delete('/account', [\App\Http\Controllers\Auth\AccountController::class, 'deleteAccount'])->name('account.delete');
});

Route::middleware(['auth', 'role:shipper'])->group(function () {
    Route::get('/shipper', fn() => 'Giao hàng');
});

Route::middleware(['auth', 'role:warehouse_staff'])->group(function () {
    Route::get('/warehouse', fn() => 'Quản lý kho');
});

Route::middleware(['auth', 'role:supplier'])->group(function () {
    Route::get('/supplier', fn() => 'Quản lý hàng nhập');
});

Route::get('/guest', fn() => 'Khách chưa đăng nhập chỉ xem sản phẩm và tìm kiếm');
