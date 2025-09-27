<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\NewsProviders\{GuardianService, NewsApiService, NytService, ProviderInterface};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProviderInterface::class, function($app){
            // not used directly; specific services will be resolved separately
        });

        // If you prefer you can singleton bind the service classes:
        $this->app->singleton(NewsApiService::class);
        $this->app->singleton(GuardianService::class);
        $this->app->singleton(NytService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
