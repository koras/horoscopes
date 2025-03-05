<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Models\ProductInterface;
use App\Contracts\Models\Moon\MarkersInterface;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\MarkersRepositoryInterface;
use App\Repositories\ProductRepository;
use App\Repositories\MarkersRepository;

class AppRepositoryProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MarkersRepositoryInterface::class, function ($app) {
            return new MarkersRepository($app->make(MarkersInterface::class));
        });
        $this->app->bind(ProductRepositoryInterface::class, function ($app) {
            return new ProductRepository($app->make(ProductInterface::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
