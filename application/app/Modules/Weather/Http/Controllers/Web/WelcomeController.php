<?php

namespace App\Modules\Weather\Http\Controllers\Web;


use App\Modules\Weather\Http\Controllers\Controller;
use App\Modules\Weather\Models\City;
use Illuminate\Contracts\View\View;

class WelcomeController extends Controller
{

    public function welcome(): View
    {
        $cityName = env('WEATHER_CITY_TARGET', 'Kyiv');
        $city = City::firstOrCreate(['name' => $cityName]);

        return view("{$this->moduleAlias}::welcome")->with([
            'city' => $city
        ]);
    }
}
