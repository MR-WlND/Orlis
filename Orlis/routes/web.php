<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\RoleLoginController;
use App\Http\Controllers\Auth\SocialAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/beauty', [\App\Http\Controllers\HomeController::class, 'beauty'])->name('home.beauty');

// Cart routes
Route::get('/cart', [\App\Http\Controllers\Client\CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [\App\Http\Controllers\Client\CartController::class, 'addItem'])->name('cart.add');
Route::post('/cart/{variantId}', [\App\Http\Controllers\Client\CartController::class, 'updateItem'])->name('cart.update');
Route::delete('/cart/{variantId}', [\App\Http\Controllers\Client\CartController::class, 'removeItem'])->name('cart.remove');
Route::post('/cart/clear', [\App\Http\Controllers\Client\CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/coupon/apply', [\App\Http\Controllers\Client\CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::post('/cart/coupon/remove', [\App\Http\Controllers\Client\CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

// Checkout routes (auth required)
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [\App\Http\Controllers\Client\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [\App\Http\Controllers\Client\CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/confirm', [\App\Http\Controllers\Client\CheckoutController::class, 'confirm'])->name('checkout.confirm');
});

// VNPay routes
Route::get('/vnpay/return', [\App\Http\Controllers\Client\CheckoutController::class, 'vnpayReturn'])->name('vnpay.return');
Route::get('/vnpay/ipn', [\App\Http\Controllers\Client\CheckoutController::class, 'vnpayIpn'])->name('vnpay.ipn');


// Appointment routes (auth required)
Route::middleware(['auth'])->group(function () {
    Route::get('/appointment/book', [\App\Http\Controllers\Client\AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointment/book', [\App\Http\Controllers\Client\AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments', [\App\Http\Controllers\Client\AppointmentController::class, 'index'])->name('customer.appointments');
    Route::patch('/appointments/{appointment}/cancel', [\App\Http\Controllers\Client\AppointmentController::class, 'cancel'])->name('appointments.cancel');
});

Route::get('/catalog/{slug?}', [\App\Http\Controllers\Client\CatalogController::class, 'index'])->name('catalog');

Route::get('/product/{id}', [\App\Http\Controllers\Client\ProductController::class, 'show'])->name('product');
Route::post('/product/{id}/review', [\App\Http\Controllers\Client\ReviewController::class, 'store'])->name('product.review')->middleware('auth');

Route::get('/perfume', function () {
    return view('client.perfume');
})->name('perfume');

Route::get('/magazine', [\App\Http\Controllers\Client\PostController::class, 'index'])->name('magazine.index');
Route::get('/magazine/{slug}', [\App\Http\Controllers\Client\PostController::class, 'show'])->name('magazine.show');

Route::get('/login/{role}', [RoleLoginController::class, 'showLoginForm'])->name('role.login');
Route::post('/login/{role}', [RoleLoginController::class, 'login'])->name('role.login.post');
Route::post('/logout', [RoleLoginController::class, 'logout'])->name('logout');

Route::get('/register', fn() => redirect('/'))->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

// Password Reset Routes
Route::get('password/reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');
Route::get('/admin', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

Route::resource('admin/users', \App\Http\Controllers\Admin\UserController::class, ['as' => 'admin']);
Route::resource('admin/admins', \App\Http\Controllers\Admin\AdminAccountController::class, ['as' => 'admin']);

// Category routes
Route::resource('/admin/categories', \App\Http\Controllers\Admin\CategoryController::class, ['as' => 'admin']);

// Admin Ticket Routes
Route::get('/admin/tickets', [\App\Http\Controllers\Admin\TicketController::class, 'index'])->name('admin.tickets.index');
Route::get('/admin/tickets/{ticket}', [\App\Http\Controllers\Admin\TicketController::class, 'show'])->name('admin.tickets.show');
Route::post('/admin/tickets/{ticket}/reply', [\App\Http\Controllers\Admin\TicketController::class, 'reply'])->name('admin.tickets.reply');
Route::patch('/admin/tickets/{ticket}/close', [\App\Http\Controllers\Admin\TicketController::class, 'close'])->name('admin.tickets.close');

Route::post('/admin/logout', [\App\Http\Controllers\Auth\RoleLoginController::class, 'logout'])->name('admin.logout');

Route::resource('admin/banners', \App\Http\Controllers\Admin\BannerController::class, ['as' => 'admin']);
Route::resource('admin/products', \App\Http\Controllers\Admin\ProductController::class, ['as' => 'admin']);
Route::resource('admin/posts', \App\Http\Controllers\Admin\PostController::class, ['as' => 'admin']);
Route::resource('admin/products.variants', \App\Http\Controllers\Admin\ProductVariantController::class, ['as' => 'admin']);
Route::resource('admin/coupons', \App\Http\Controllers\Admin\CouponController::class, ['as' => 'admin']);
Route::resource('admin/reviews', \App\Http\Controllers\Admin\ReviewController::class, ['as' => 'admin'])->only(['index', 'destroy']);
Route::patch('admin/reviews/{review}/status', [\App\Http\Controllers\Admin\ReviewController::class, 'updateStatus'])->name('admin.reviews.updateStatus');
Route::resource('admin/orders', \App\Http\Controllers\Admin\OrderController::class, ['as' => 'admin'])->only(['index', 'show', 'destroy']);
Route::patch('admin/orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
Route::resource('admin/appointments', \App\Http\Controllers\Admin\AppointmentController::class, ['as' => 'admin'])->only(['index', 'show']);
Route::patch('admin/appointments/{appointment}/staff', [\App\Http\Controllers\Admin\AppointmentController::class, 'assignStaff'])->name('admin.appointments.assignStaff');
Route::patch('admin/appointments/{appointment}/status', [\App\Http\Controllers\Admin\AppointmentController::class, 'updateStatus'])->name('admin.appointments.updateStatus');
Route::get('admin/inventory', [\App\Http\Controllers\Admin\InventoryController::class, 'index'])->name('admin.inventory.index');
Route::put('admin/inventory', [\App\Http\Controllers\Admin\InventoryController::class, 'upsert'])->name('admin.inventory.upsert');
Route::get('admin/inventory/variant/{variantId}', [\App\Http\Controllers\Admin\InventoryController::class, 'showVariant'])->name('admin.inventory.variant');
Route::post('admin/inventory/transfer', [\App\Http\Controllers\Admin\InventoryController::class, 'transfer'])->name('admin.inventory.transfer');

Route::middleware(['auth', 'role:manager'])->group(function () {
    Route::get('/manager', fn() => 'Quản lý vận hành');
});

Route::middleware(['auth', 'role:staff'])->group(function () {
    Route::get('/staff', [\App\Http\Controllers\Staff\StaffController::class, 'dashboard'])->name('staff.dashboard');
    Route::patch('/staff/orders/{id}/process', [\App\Http\Controllers\Staff\StaffController::class, 'processOrder'])->name('staff.orders.process');
});

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer', [\App\Http\Controllers\Client\CustomerController::class, 'dashboard'])->name('customer.dashboard');
    Route::get('/customer/orders', [\App\Http\Controllers\Client\CustomerController::class, 'orders'])->name('customer.orders');
    Route::get('/customer/orders/{order}', [\App\Http\Controllers\Client\CustomerController::class, 'orderDetail'])->name('customer.order-detail');
    Route::get('/customer/profile', [\App\Http\Controllers\Client\CustomerController::class, 'profile'])->name('customer.profile');
    Route::patch('/customer/profile', [\App\Http\Controllers\Client\CustomerController::class, 'updateProfile'])->name('customer.profile.update');
    Route::get('/customer/addresses', [\App\Http\Controllers\Client\CustomerController::class, 'addresses'])->name('customer.addresses');
    Route::post('/customer/addresses', [\App\Http\Controllers\Client\CustomerController::class, 'storeAddress'])->name('customer.addresses.store');
    Route::delete('/customer/addresses/{address}', [\App\Http\Controllers\Client\CustomerController::class, 'destroyAddress'])->name('customer.addresses.destroy');
    Route::patch('/customer/addresses/{address}/default', [\App\Http\Controllers\Client\CustomerController::class, 'setDefaultAddress'])->name('customer.addresses.default');
    Route::get('/customer/wishlist', [\App\Http\Controllers\Client\CustomerController::class, 'wishlist'])->name('customer.wishlist');
    Route::delete('/account', [\App\Http\Controllers\Auth\AccountController::class, 'deleteAccount'])->name('account.delete');
});

// Wishlist toggle (all auth users)
Route::middleware(['auth'])->post('/wishlist/toggle', [\App\Http\Controllers\Client\WishlistController::class, 'toggle'])->name('wishlist.toggle');

Route::middleware(['auth', 'role:shipper'])->group(function () {
    Route::get('/shipper', [\App\Http\Controllers\Shipper\ShipperController::class, 'dashboard'])->name('shipper.dashboard');
    Route::patch('/shipper/orders/{id}/status', [\App\Http\Controllers\Shipper\ShipperController::class, 'updateStatus'])->name('shipper.orders.updateStatus');
});

Route::middleware(['auth', 'role:warehouse_staff'])->group(function () {
    Route::get('/warehouse', [\App\Http\Controllers\Warehouse\WarehouseController::class, 'dashboard'])->name('warehouse.dashboard');
    Route::patch('/warehouse/orders/{id}/delivering', [\App\Http\Controllers\Warehouse\WarehouseController::class, 'markAsDelivering'])->name('warehouse.orders.delivering');
});

// Ticket routes for Customer
Route::middleware(['auth', 'role:customer'])->prefix('account')->group(function () {
    Route::get('/tickets', [\App\Http\Controllers\Client\TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [\App\Http\Controllers\Client\TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [\App\Http\Controllers\Client\TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [\App\Http\Controllers\Client\TicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', [\App\Http\Controllers\Client\TicketController::class, 'reply'])->name('tickets.reply');
});

Route::get('/track-order', [\App\Http\Controllers\Client\TrackOrderController::class, 'index'])->name('track-order');
Route::post('/track-order', [\App\Http\Controllers\Client\TrackOrderController::class, 'track'])->name('track-order.post');

// Language Route
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'vi'])) {
        session()->put('locale', $locale);
    }
    return back();
})->name('lang.switch');

Route::middleware(['auth', 'role:supplier'])->group(function () {
    Route::get('/supplier', [\App\Http\Controllers\Supplier\SupplierController::class, 'dashboard'])->name('supplier.dashboard');
    Route::patch('/supplier/orders/{purchaseOrder}', [\App\Http\Controllers\Supplier\SupplierController::class, 'updateStatus'])->name('supplier.orders.updateStatus');
});

Route::get('/guest', fn() => 'Khách chưa đăng nhập chỉ xem sản phẩm và tìm kiếm');
