<?php

namespace Ahmadwaleed\Blanket;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Ahmadwaleed\Blanket\Listeners\LogClientRequest;
use Illuminate\Http\Client\Events\ResponseReceived;

class BlanketServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/blanket.php' => base_path('config/blanket.php'),
            ], 'blanket-config');
            $this->publishes([
                __DIR__ . '/../public' => base_path('public/vendor/blanket'),
            ], 'blanket-assets');

            $this->publishes([
                __DIR__ . '/../database/migrations' => base_path('database/migrations'),
            ], 'blanket-migrations');

            $this->commands([
                Console\InstallCommand::class,
            ]);
        }

        /** @var Router $router */
        $router = $this->app['router'];

        $router->group($this->routeAttributes(), function () use (&$router) {
            return include __DIR__ . "/../routes/api.php";
        });

        $this->app['events']->listen(ResponseReceived::class, LogClientRequest::class);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/blanket.php', 'blanket');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'blanket');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    private function routeAttributes(): array
    {
        return [
            'prefix' => $this->app['config']->get('blanket.path'),
            'middleware' => $this->app['config']->get('middlewares'),
        ];
    }
}
