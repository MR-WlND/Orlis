<?php

namespace App\Providers;

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
        \App\Models\Order::observe(\App\Observers\OrderObserver::class);
        \App\Models\Product::observe(\App\Observers\ProductObserver::class);

        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $globalCategories = \App\Models\Category::whereNull('parent_id')
                ->with(['children' => function($q) {
                    $q->with('children');
                }])
                ->get();
            $view->with('globalCategories', $globalCategories);
        });
    }
}
