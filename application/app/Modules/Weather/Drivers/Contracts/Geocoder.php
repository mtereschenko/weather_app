<?php

namespace App\Modules\Weather\Drivers\Contracts;

use app\Modules\Weather\Models\City;

interface Geocoder
{
    public function findCityCoord(City $city): array;
}
