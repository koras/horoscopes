<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Contracts\SmsRequestInterface;
use App\Models\SmsRequest;


class AppModelProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SmsRequestInterface::class, SmsRequest::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
