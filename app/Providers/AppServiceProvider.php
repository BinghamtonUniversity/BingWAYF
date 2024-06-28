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
        // Set max cookie lifetime to 30 minutes
        ini_set('session.cookie_lifetime', 1800);
        Passport::useClientModel(Client::class);
        Passport::tokensExpireIn(now()->addMinutes(5));
        Passport::personalAccessTokensExpireIn(now()->addMinutes(5));
        Passport::refreshTokensExpireIn(now());    
    }
}
