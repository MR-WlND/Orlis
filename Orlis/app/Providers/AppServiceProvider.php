<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Observers\OrderObserver;
use App\Observers\ProductObserver;
use App\Services\CartService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
        Order::observe(OrderObserver::class);
        Product::observe(ProductObserver::class);

        \Illuminate\Pagination\Paginator::useBootstrapFive();

        View::composer('*', function ($view) {
            $globalCategories = \Illuminate\Support\Facades\Cache::remember('global_categories_nested', 600, function () {
                return Category::whereNull('parent_id')
                    ->with(['children' => function ($q) {
                        $q->with('children');
                    }])
                    ->get();
            });

            $view->with('globalCategories', $globalCategories);

            $footerLinks = \Illuminate\Support\Facades\Cache::remember('global_footer_links', 600, function () {
                return \App\Models\FooterLink::where('is_active', true)
                    ->orderBy('order')
                    ->get()
                    ->groupBy('group_name');
            });
            $view->with('footerLinks', $footerLinks);

            // Cart item count for header badge
            $cartCount = 0;
            try {
                $userId = auth()->id();
                $sessionId = session()->getId();
                $cartCount = app(CartService::class)->countItems($userId, $sessionId);
            } catch (\Throwable $e) {
                // Fail silently — cart badge not critical
            }
            $view->with('cartCount', $cartCount);
        });
    }
}
