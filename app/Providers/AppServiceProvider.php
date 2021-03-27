<?php

namespace App\Providers;

use App\Contracts\ArrayCombinationsServiceInterface;
use App\Contracts\BasketServiceInterface;
use App\Contracts\BasketFormatterServiceInterface;
use App\Contracts\ProductServiceInterface;
use App\Contracts\ProductDealsServiceInterface;
use App\Services\ArrayCombinationsService;
use App\Services\BasketService;
use App\Services\BasketApiFormatterService;
use App\Services\ProductService;
use App\Services\ProductDealsService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BasketServiceInterface::class, BasketService::class);
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
        $this->app->bind(ProductDealsServiceInterface::class, ProductDealsService::class);
        $this->app->bind(BasketFormatterServiceInterface::class, BasketApiFormatterService::class);
        $this->app->bind(ArrayCombinationsServiceInterface::class, ArrayCombinationsService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
