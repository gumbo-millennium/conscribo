<?php

declare(strict_types=1);

namespace Gumbo\Conscribo;

use Gumbo\Conscribo\Objects\ConscriboEntityDescription;
use Illuminate\Support\ServiceProvider;

class ConscriboServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Merge configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/conscribo.php', 'conscribo');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            // Publish certain assets
            $this->publishes([
                __DIR__.'/../config/conscribo.php' => config_path('conscribo.php'),
            ], 'conscribo-config');

            // Register commands
            $this->commands([
                Commands\ListEntitiesCommand::class,
                Commands\ListEntityFieldsCommand::class,
            ]);
        }

        // Update config to use proper models, register version and user-agent
        $this->app->configurationIsCached() or $this->updateConfiguration();
    }

    private function updateConfiguration(): void
    {
        // Determine version and build a user agent
        $version = \Composer\InstalledVersions::getVersion('gumbo-millennium/conscribo');
        $userAgent = sprintf(
            'Conscribo API Client/%s (+https://github.com/gumbo-millennium/conscribo); php/%s; curl/%s',
            $version,
            phpversion(),
            curl_version()['version']
        );

        // Map all objects to a ConscriboEntityDescription
        $objects = array_map(
            fn ($definition) => new ConscriboEntityDescription($definition),
            $this->app->config->get('conscribo.objects', [])
        );

        // Update config
        $this->app->config->set([
                'conscribo.objects' => $objects,
            'conscribo.version' => $version,
            'conscribo.user_agent' => $userAgent,
        ]);
    }
}
