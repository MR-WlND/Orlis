<?php

use App\Http\Controllers\Admin\AdminAccountController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AccountController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\RoleLoginController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Client\AppointmentController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\CatalogController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\CustomerController;
use App\Http\Controllers\Client\PostController;
use App\Http\Controllers\Client\ProductController;
use App\Http\Controllers\Client\ReviewController;
use App\Http\Controllers\Client\TrackOrderController;
use App\Http\Controllers\Client\WishlistController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Shipper\ShipperController;
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\Supplier\SupplierController;
use App\Http\Controllers\Warehouse\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/beauty', [HomeController::class, 'beauty'])->name('home.beauty');

// Cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'addItem'])->name('cart.add');
Route::post('/cart/{variantId}', [CartController::class, 'updateItem'])->name('cart.update');
Route::delete('/cart/{variantId}', [CartController::class, 'removeItem'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/coupon/apply', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::post('/cart/coupon/remove', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

// Checkout routes (auth required)
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/confirm', [CheckoutController::class, 'confirm'])->name('checkout.confirm');
});

// VNPay routes
Route::get('/vnpay/return', [CheckoutController::class, 'vnpayReturn'])->name('vnpay.return');
Route::get('/vnpay/ipn', [CheckoutController::class, 'vnpayIpn'])->name('vnpay.ipn');

// Appointment routes (auth required)
Route::middleware(['auth'])->group(function () {
    Route::get('/appointment/book', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointment/book', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('customer.appointments');
    Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
});

Route::get('/catalog/{slug?}', [CatalogController::class, 'index'])->name('catalog');

Route::get('/product/{id}', [ProductController::class, 'show'])->name('product');
Route::post('/product/{id}/review', [ReviewController::class, 'store'])->name('product.review')->middleware('auth');

Route::get('/perfume', function () {
    return view('client.perfume');
})->name('perfume');

Route::get('/magazine', [PostController::class, 'index'])->name('magazine.index');
Route::get('/magazine/{slug}', [PostController::class, 'show'])->name('magazine.show');

Route::get('/login/{role}', [RoleLoginController::class, 'showLoginForm'])->name('role.login');
Route::post('/login/{role}', [RoleLoginController::class, 'login'])->name('role.login.post');
Route::get('/login', fn () => redirect('/'))->name('login');
Route::post('/logout', [RoleLoginController::class, 'logout'])->name('logout');

Route::get('/register', fn () => redirect('/'))->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

// Password Reset Routes
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');
Route::middleware(['auth:admin'])->group(function () {
    Route::post('/admin/logout', [RoleLoginController::class, 'logout'])->name('admin.logout');
});

Route::middleware(['auth:admin', 'role:admin'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('admin/users', UserController::class, ['as' => 'admin']);
    Route::resource('admin/admins', AdminAccountController::class, ['as' => 'admin']);
    Route::resource('/admin/categories', CategoryController::class, ['as' => 'admin']);

    Route::get('/admin/tickets', [TicketController::class, 'index'])->name('admin.tickets.index');
    Route::get('/admin/tickets/{ticket}', [TicketController::class, 'show'])->name('admin.tickets.show');
    Route::post('/admin/tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('admin.tickets.reply');
    Route::patch('/admin/tickets/{ticket}/close', [TicketController::class, 'close'])->name('admin.tickets.close');

    Route::resource('admin/banners', BannerController::class, ['as' => 'admin']);
    Route::resource('admin/products', App\Http\Controllers\Admin\ProductController::class, ['as' => 'admin']);
    Route::resource('admin/posts', App\Http\Controllers\Admin\PostController::class, ['as' => 'admin']);
    Route::resource('admin/products.variants', ProductVariantController::class, ['as' => 'admin']);
    Route::resource('admin/coupons', CouponController::class, ['as' => 'admin']);
    Route::resource('admin/shipping-methods', App\Http\Controllers\Admin\ShippingMethodController::class, ['as' => 'admin']);
    Route::resource('admin/reviews', App\Http\Controllers\Admin\ReviewController::class, ['as' => 'admin'])->only(['index', 'destroy']);
    Route::patch('admin/reviews/{review}/status', [App\Http\Controllers\Admin\ReviewController::class, 'updateStatus'])->name('admin.reviews.updateStatus');
    Route::resource('admin/orders', OrderController::class, ['as' => 'admin'])->only(['index', 'show', 'destroy']);
    Route::patch('admin/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    Route::resource('admin/appointments', App\Http\Controllers\Admin\AppointmentController::class, ['as' => 'admin'])->only(['index', 'show']);
    Route::patch('admin/appointments/{appointment}/staff', [App\Http\Controllers\Admin\AppointmentController::class, 'assignStaff'])->name('admin.appointments.assignStaff');
    Route::patch('admin/appointments/{appointment}/status', [App\Http\Controllers\Admin\AppointmentController::class, 'updateStatus'])->name('admin.appointments.updateStatus');
    Route::get('admin/inventory', [InventoryController::class, 'index'])->name('admin.inventory.index');
    Route::put('admin/inventory', [InventoryController::class, 'upsert'])->name('admin.inventory.upsert');
    Route::get('admin/inventory/variant/{variantId}', [InventoryController::class, 'showVariant'])->name('admin.inventory.variant');
    Route::post('admin/inventory/transfer', [InventoryController::class, 'transfer'])->name('admin.inventory.transfer');
});

Route::middleware(['auth:admin', 'role:manager'])->group(function () {
    Route::get('/manager', fn () => 'Quản lý vận hành');
});

Route::middleware(['auth:admin', 'role:staff'])->group(function () {
    Route::get('/staff', [StaffController::class, 'dashboard'])->name('staff.dashboard');
    Route::patch('/staff/orders/{id}/process', [StaffController::class, 'processOrder'])->name('staff.orders.process');
});

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer', [CustomerController::class, 'dashboard'])->name('customer.dashboard');
    Route::get('/customer/orders', [CustomerController::class, 'orders'])->name('customer.orders');
    Route::get('/customer/orders/{order}', [CustomerController::class, 'orderDetail'])->name('customer.order-detail');
    Route::get('/customer/profile', [CustomerController::class, 'profile'])->name('customer.profile');
    Route::patch('/customer/profile', [CustomerController::class, 'updateProfile'])->name('customer.profile.update');
    Route::get('/customer/addresses', [CustomerController::class, 'addresses'])->name('customer.addresses');
    Route::post('/customer/addresses', [CustomerController::class, 'storeAddress'])->name('customer.addresses.store');
    Route::delete('/customer/addresses/{address}', [CustomerController::class, 'destroyAddress'])->name('customer.addresses.destroy');
    Route::patch('/customer/addresses/{address}/default', [CustomerController::class, 'setDefaultAddress'])->name('customer.addresses.default');
    Route::get('/customer/wishlist', [CustomerController::class, 'wishlist'])->name('customer.wishlist');
    Route::delete('/account', [AccountController::class, 'deleteAccount'])->name('account.delete');
});

// Wishlist toggle (all auth users)
Route::middleware(['auth'])->post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

Route::middleware(['auth:admin', 'role:shipper'])->group(function () {
    Route::get('/shipper', [ShipperController::class, 'dashboard'])->name('shipper.dashboard');
    Route::patch('/shipper/orders/{id}/status', [ShipperController::class, 'updateStatus'])->name('shipper.orders.updateStatus');
});

Route::middleware(['auth:admin', 'role:warehouse_staff'])->group(function () {
    Route::get('/warehouse', [WarehouseController::class, 'dashboard'])->name('warehouse.dashboard');
    Route::patch('/warehouse/orders/{id}/delivering', [WarehouseController::class, 'markAsDelivering'])->name('warehouse.orders.delivering');
});

// Ticket routes for Customer
Route::middleware(['auth', 'role:customer'])->prefix('account')->group(function () {
    Route::get('/tickets', [App\Http\Controllers\Client\TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [App\Http\Controllers\Client\TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [App\Http\Controllers\Client\TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [App\Http\Controllers\Client\TicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', [App\Http\Controllers\Client\TicketController::class, 'reply'])->name('tickets.reply');
});

Route::get('/track-order', [TrackOrderController::class, 'index'])->name('track-order');
Route::post('/track-order', [TrackOrderController::class, 'track'])->name('track-order.post');

// Language Route
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'vi', 'fr', 'ja'])) {
        session()->put('locale', $locale);
        session()->save();
    }

    return back();
})->name('lang.switch');

Route::middleware(['auth:admin', 'role:supplier'])->group(function () {
    Route::get('/supplier', [SupplierController::class, 'dashboard'])->name('supplier.dashboard');
    Route::patch('/supplier/orders/{purchaseOrder}', [SupplierController::class, 'updateStatus'])->name('supplier.orders.updateStatus');
});

Route::get('/guest', fn () => 'Khách chưa đăng nhập chỉ xem sản phẩm và tìm kiếm');

Route::get('/test-session-set', function () {
    session(['test_key' => 'Hello World']);

    return 'Session set. <a href="/test-session-get">Get Session</a>';
});

Route::get('/test-session-get', function () {
    return 'Session value: '.session('test_key');
});
