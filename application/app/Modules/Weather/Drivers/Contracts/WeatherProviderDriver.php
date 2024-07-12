<?php

namespace App\Modules\Weather\Drivers\Contracts;

use App\Modules\Weather\Exceptions\FailedDataFetchingAttemptException;
use App\Modules\Weather\Models\Broadcast;
use app\Modules\Weather\Models\City;

interface WeatherProviderDriver
{
    /**
     * @param City $city
     * @return Broadcast
     * @throws FailedDataFetchingAttemptException
     */
    public function fetchWeather(City $city): Broadcast;
}
