<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\ProductCacheService;

class ProductObserver
{
    protected $cacheService;

    public function __construct(ProductCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function saved(Product $product): void
    {
        $this->cacheService->clearProductCache();
    }

    public function deleted(Product $product): void
    {
        $this->cacheService->clearProductCache();
    }
}
