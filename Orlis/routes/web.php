<?php

use App\Http\Controllers\Auth\RoleLoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/cart', function () {
    return view('cart');
})->name('cart');

Route::get('/login/{role}', [RoleLoginController::class, 'showLoginForm'])->name('role.login');
Route::post('/login/{role}', [RoleLoginController::class, 'login'])->name('role.login.post');
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', fn() => 'Quản trị hệ thống');
});

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
