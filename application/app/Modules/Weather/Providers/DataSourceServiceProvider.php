<?php

namespace App\Modules\Weather\Providers;

use App\Modules\Weather\Drivers\Contracts\WeatherProviderDriver;
use App\Modules\Weather\Services\GuzzleClient\Middlewares\RequestMiddleware;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Middleware;
use App\Modules\Weather\Drivers\Contracts\Geocoder as GeocoderContract;
use App\Modules\Weather\Jobs\CollectBroadcast;
use GuzzleHttp\Client as GuzzleHttpClient;
use Closure;
use GuzzleHttp\HandlerStack;
use Illuminate\Support\ServiceProvider;

class DataSourceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerConfig();
        $this->registerLogChannels();

        $selectedDriver = config('weather.data_source.default_weather_provider');
        $driverConfig = config('weather.data_source.drivers.' . $selectedDriver);

        $this->bindClientInterface();

        if (isset($driverConfig['geocoder'])) {
            $this->app->bind(GeocoderContract::class, $driverConfig['geocoder']);
        }

//        $this->app->bind(CollectBroadcast::class)
//            ->needs(WeatherProviderDriver::class)
//            ->give($this->setupWeatherDriver());

        $this->app->bind(WeatherProviderDriver::class, $driverConfig['driver']);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {


    }

    private function registerLogChannels(): void
    {
        $selectedDriver = config('weather.data_source.default_weather_provider');
        $date = now()->format('Y-m-d');
        $this->app->make('config')->set("logging.channels.{$selectedDriver}", [
            'driver' => 'single',
            'path' => storage_path("logs/{$selectedDriver}-{$date}.log"),
        ]);
    }

    private function bindClientInterface(): void
    {
        $this->app->bind(ClientInterface::class, function (): ClientInterface {
            $handlerStack = HandlerStack::create();
            $handlerStack->push(Middleware::mapRequest((new RequestMiddleware())), 'request_middleware');

            $selectedDriver = config('weather.data_source.default_weather_provider');
            $driverConfig = config("weather.data_source.drivers.{$selectedDriver}");

            $config = [
                'handler' => $handlerStack,
                'verify' => false,
                'base_uri' => $driverConfig['base_url']
            ];

            return new GuzzleHttpClient($config);
        });
    }

    private function registerConfig(): void
    {
        $config = require __DIR__ . '/../config/data_source.php';

        config()->offsetSet('weather.data_source', $config);
    }

    private function setupWeatherDriver(): Closure
    {
        return function (): WeatherProviderDriver {
            $selectedDriver = config('weather.data_source.default_weather_provider');
            $driverConfig = config('weather.data_source.drivers.' . $selectedDriver);
            return app()->make($driverConfig['driver']);
        };
    }
}
