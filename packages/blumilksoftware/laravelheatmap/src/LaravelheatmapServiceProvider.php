<?php

namespace Blumilk\BlumilkSoftware\LaravelHeatmap;

use Illuminate\Support\ServiceProvider;

class LaravelHeatmapServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Register the package's services here.
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravelheatmap');

        // Publish assets
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/laravelheatmap'),
        ]);
    }
}
