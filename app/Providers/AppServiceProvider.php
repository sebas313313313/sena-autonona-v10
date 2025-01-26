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
        // Observar modelos para registro de actividades
        \App\Models\User::observe(\App\Observers\ModelObserver::class);
        \App\Models\Farm::observe(\App\Observers\ModelObserver::class);
        \App\Models\Component::observe(\App\Observers\ModelObserver::class);
        \App\Models\Farm_Component::observe(\App\Observers\ModelObserver::class);
        \App\Models\Sensor::observe(\App\Observers\ModelObserver::class);
        \App\Models\Sensor_Component::observe(\App\Observers\ModelObserver::class);
        \App\Models\Sample::observe(\App\Observers\ModelObserver::class);
        \App\Models\Component_Task::observe(\App\Observers\ModelObserver::class);
    }
}
