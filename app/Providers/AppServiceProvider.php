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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar el observer para los modelos
        \App\Models\User::observe(\App\Observers\ModelObserver::class);
        \App\Models\Farm::observe(\App\Observers\ModelObserver::class);
        \App\Models\Component::observe(\App\Observers\ModelObserver::class);
    }
}
