<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\RoleLoginController;
use App\Http\Controllers\Auth\SocialAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('client.home');
});

Route::get('/cart', function () {
    return view('client.cart');
})->name('cart');

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
Route::resource('admin/products', \App\Http\Controllers\Admin\ProductController::class, ['as' => 'admin']);

Route::middleware(['auth', 'role:manager'])->group(function () {
    Route::get('/manager', fn() => 'Quản lý vận hành');
});

Route::middleware(['auth', 'role:staff'])->group(function () {
    Route::get('/staff', fn() => 'Xử lý đơn hàng');
});

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer', fn() => 'Mua hàng và theo dõi đơn');
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
