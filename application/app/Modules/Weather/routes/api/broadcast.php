<?php

use App\Modules\Weather\Http\Controllers\Api\WeatherController;
use Illuminate\Support\Facades\Route;

$namePrefix = 'weather';

Route::get('/{date}', [WeatherController::class, 'fetch'])->name("{$namePrefix}.api.fetch");
