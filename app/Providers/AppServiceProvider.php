<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Services\NewsProviders\ProviderInterface::class, function($app){
            // not used directly; specific services will be resolved separately
        });

        // If you prefer you can singleton bind the service classes:
        $this->app->singleton(\App\Services\NewsProviders\NewsApiService::class);
        $this->app->singleton(\App\Services\NewsProviders\GuardianService::class);
        $this->app->singleton(\App\Services\NewsProviders\NytService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
