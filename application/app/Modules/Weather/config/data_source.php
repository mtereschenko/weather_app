<?php

use App\Modules\Weather\Drivers\AccuWeather\AccuWeatherDriver;
use App\Modules\Weather\Drivers\FreeOpenWeatherMap\FreeOpenWeatherMapDriver;
use App\Modules\Weather\Drivers\WeatherApi\WeatherApiDriver;

use App\Modules\Weather\Drivers\AccuWeather\Geocoder as AccuWeatherGeocoder;
use App\Modules\Weather\Drivers\FreeOpenWeatherMap\Geocoder as FreeOpenWeatherMapGeocoder;

return [
    /*
    |
    |--------------------------------------------------------------------------
    | Units of measurement.
    |--------------------------------------------------------------------------
    |
    | Available units: metric and imperial.
    | If you do not use the units parameter, standard units will be applied by default.
    */
    'units' => env('OPENWEATHER_MAP_UNITS', 'metric'),
    'failsafe' => env('WEATHER_FAILSAFE_MODE', true),
    'default_weather_provider' => strtolower(env('WEATHER_DEFAULT_DRIVER', 'openweathermap')),
    'drivers' => [
        'openweathermap' => [
            'driver' => FreeOpenWeatherMapDriver::class,
            'geocoder' => FreeOpenWeatherMapGeocoder::class,
            'key' => env('OPENWEATHER_MAP_API_KEY', null),
            'base_url' => 'https://api.openweathermap.org/data/2.5/weather'
        ],
        'accuweather' => [
            'driver' => AccuWeatherDriver::class,
            'geocoder' => AccuWeatherGeocoder::class,
            'key' => env('ACCUWEATHER_API_KEY', null),
            'base_url' => 'https://dataservice.accuweather.com'
        ],
        /*
        | I left this driver not implemented on purpose to give the opportunity to test failsafe mode.
         */
        'weatherapi' => [
            'driver' => WeatherApiDriver::class,
            'base_url' => ''
        ]
    ]
];
