<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Compatibilité avec certaines installations MySQL Wamp dont les index
        // Unicode ne peuvent pas dépasser 1 000 octets.
        Schema::defaultStringLength(191);
    }
}
