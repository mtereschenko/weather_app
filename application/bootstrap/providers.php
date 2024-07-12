<?php

use App\Modules\Weather\Providers\DataSourceServiceProvider;
use App\Modules\Weather\Providers\WeatherServiceProvider;
use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    WeatherServiceProvider::class,
    DataSourceServiceProvider::class,
];
