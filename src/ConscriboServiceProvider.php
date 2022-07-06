<?php

declare(strict_types=1);

namespace Gumbo\Conscribo;

use Illuminate\Support\ServiceProvider;

class ConscriboServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Merge configuration
        $this->mergeConfigFrom(__DIR__.'/../config/conscribo.php', 'conscribo');
    }

    public function boot()
    {
        // Publish certain assets
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/conscribo.php' => config_path('conscribo.php'),
            ], 'conscribo-config');
        }
    }
}
