<?php

namespace Ahmadwaleed\Blanket\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Ahmadwaleed\Blanket\BlanketServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Ahmadwaleed\\Blanket\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
        $this->withoutExceptionHandling();
    }

    protected function getPackageProviders($app)
    {
        return [
            BlanketServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'blanket');
        $app['config']->set('database.connections.blanket', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    public function route(string $uri): string
    {
        return sprintf("%s/%s", config('blanket.path'), $uri);
    }
}
