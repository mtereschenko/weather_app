<?php

namespace App\Modules\Weather\Drivers\WeatherApi;

use App\Modules\Weather\Drivers\Contracts\WeatherProviderDriver;
use App\Modules\Weather\Models\Broadcast;
use app\Modules\Weather\Models\City;
use Nette\NotImplementedException;

class WeatherApiDriver implements WeatherProviderDriver {

    public function fetchWeather(City $city): Broadcast
    {
        throw new NotImplementedException();
    }
}
