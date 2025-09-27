<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\NewsProviders\{GuardianService, NewsApiService, NytService, ProviderInterface};
use App\Services\NewsProviders\ProviderFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ProviderFactory::class, function ($app) {
            return new ProviderFactory();
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
