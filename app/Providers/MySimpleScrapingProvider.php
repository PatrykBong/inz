<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\SimpleScraper;

class MySimpleScrapingProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('simplescraper', function () {
            return new SimpleScraper();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}