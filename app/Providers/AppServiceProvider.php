<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Passport\Client;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Passport::ignoreRoutes();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set max cookie lifetime to 1 week
        ini_set('session.cookie_lifetime', 10080);
        Passport::useClientModel(Client::class);
        Passport::tokensExpireIn(now()->addHours(168)); // 1 Week
        Passport::personalAccessTokensExpireIn(now()->addMinutes(10080));
        Passport::refreshTokensExpireIn(now()->addHours(720)); // 30 Days
    }
}
