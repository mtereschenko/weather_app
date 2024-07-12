<?php

use App\Modules\Weather\Services\Broadcast\WeatherRetrieverService;
use Illuminate\Support\Facades\Artisan;

Artisan::command('weather:retrieve', function (WeatherRetrieverService $retrieverService) {
    $selectedDriver = config('weather.data_source.default_weather_provider');
    $this->info('Scheduling a new Job to retrieve weather with the ' . $selectedDriver . ' weather provider');
    $retrieverService->retrieveWeather()->store()->getBroadcast();
});
