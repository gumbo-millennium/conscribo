<?php

namespace Gumbo\ConscriboApi;

use Gumbo\ConscriboApi\Commands\ConscriboApiCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ConscriboApiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('conscribo-api')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_conscribo-api_table')
            ->hasCommand(ConscriboApiCommand::class);
    }
}
