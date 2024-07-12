<?php

namespace App\Modules\Weather\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class WeatherServiceProvider extends ServiceProvider
{

    protected string $weatherNamespace = 'App\Http\Controllers\Weather';
    protected string $weatherApiNamespace = 'App\Http\Controllers\Weather\Api';

    private function registerCommands(): void
    {
        $this->commands([]);
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerConfig();
        $this->registerApiRoutes();
        $this->registerWebRoutes();
        $this->registerViews();
        $this->registerCommands();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerSchedulerRoutes();
        $this->registerConsoleRoutes();
    }

    private function registerConfig(): void
    {
        $config = require __DIR__ . '/../config/module.php';

        config()->offsetSet('weather.module', $config);
    }

    private function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'weather');
    }

    private function registerApiRoutes(): void
    {
        Route::group([
            'namespace' => $this->weatherApiNamespace,
            'middleware' => ['api', 'auth'],
            'prefix' => 'api/weather',
        ], function () {
            foreach (glob(__DIR__ . '/../routes/api/*.php') as $routeFile) {
                Route::group([
                    'prefix' => basename($routeFile, '.php'),
                ], $routeFile);
            }
        });
    }

    private function registerSchedulerRoutes(): void
    {
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            foreach (glob(__DIR__ . '/../routes/scheduler/*.php') as $routeFile) {
                require_once $routeFile;
            }
        });
    }

    private function registerConsoleRoutes(): void
    {
        foreach (glob(__DIR__ . '/../routes/console/*.php') as $routeFile) {
            require_once $routeFile;
        }
    }

    private function registerWebRoutes(): void
    {
        Route::group([
            'namespace' => $this->weatherNamespace,
            'middleware' => 'web',
            'prefix' => 'weather',
        ], function () {
            foreach (glob(__DIR__ . '/../routes/web/*.php') as $routeFile) {
                Route::group([
                    'prefix' => basename($routeFile, '.php'),
                ], $routeFile);
            }
        });
    }
}
