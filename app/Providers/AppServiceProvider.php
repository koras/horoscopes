<?php

namespace App\Providers;

use App\Models\Contracts\SmsRequestInterface;
use App\Models\SmsRequest;
use Illuminate\Support\ServiceProvider;


use App\Services\Auth\RegisterService;
use App\Services\Auth\Contracts\RegisterServiceInterface;

use App\Services\Auth\LoginService;
use App\Services\Auth\Contracts\LoginServiceInterface;
use App\Services\SmsService;
use App\Services\External\Telegram;

use App\Services\Auth\LogoutService;
use App\Services\Auth\Contracts\LogoutServiceInterface;
use App\Services\Contracts\SmsServiceInterface;
use App\Services\Contracts\TelegramServiceInterface;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->bind(RegisterServiceInterface::class, RegisterService::class);
        $this->app->bind(LoginServiceInterface::class, LoginService::class);
        $this->app->bind(LogoutServiceInterface::class, LogoutService::class);
        $this->app->bind(SmsServiceInterface::class, SmsService::class);
        $this->app->bind(TelegramServiceInterface::class, Telegram::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
