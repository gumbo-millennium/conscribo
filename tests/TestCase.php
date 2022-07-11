<?php

declare(strict_types=1);

namespace Gumbo\Conscribo\Tests;

use Gumbo\Conscribo\ConscriboServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Gumbo\\Conscribo\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            ConscriboServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        Config::set('database.default', 'testing');
    }
}
