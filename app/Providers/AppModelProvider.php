<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Contracts\SmsRequestInterface;
use App\Models\SmsRequest;
use App\Models\Moon\Markers;
use App\Models\Moon\MarkersTest;
use App\Contracts\Models\Moon\MarkersTestInterface;


use App\Models\Product;
use App\Contracts\Models\ProductInterface;
use App\Contracts\Models\Moon\MarkersInterface;


class AppModelProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SmsRequestInterface::class, SmsRequest::class);
        $this->app->bind(ProductInterface::class, Product::class);
        $this->app->bind(MarkersInterface::class, Markers::class);
        $this->app->bind(MarkersTestInterface::class, MarkersTest::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
