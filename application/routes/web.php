<?php

use app\Http\Controllers\Api\WeatherController;
use Illuminate\Support\Facades\Route;

Route::get('/', function() {
    return redirect(route('weather.web.welcome'));
});
