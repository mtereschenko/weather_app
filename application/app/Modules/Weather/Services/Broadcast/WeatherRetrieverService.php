<?php

namespace App\Modules\Weather\Services\Broadcast;

use App\Modules\Weather\Drivers\Contracts\WeatherProviderDriver;
use App\Modules\Weather\Exceptions\UnableToFetchDataException;
use App\Modules\Weather\Models\City;
use App\Modules\Weather\Models\Broadcast;

class WeatherRetrieverService
{
    private City $city;
    private Broadcast $broadcast;

    private bool $failsafeMode;

    public function __construct(private readonly WeatherProviderDriver $driver)
    {
        $this->getObservedCity();
        $this->initMode();
    }

    /**
     * @return $this
     * @throws UnableToFetchDataException
     */
    public function retrieveWeather(): static
    {
        try {
            $this->broadcast = $this->driver->fetchWeather($this->city);
        } catch (\Exception $exception) {
            if (!$this->failsafeMode) {
                throw new UnableToFetchDataException();
            }
            $this->retrieveInPanicMode();
        }

        return $this;
    }

    private function retrieveInPanicMode(): void
    {
        $defaultWeatherProvider = config('weather.data_source.default_weather_provider');
        $weatherDrivers = config('weather.data_source.drivers');
        unset($weatherDrivers[$defaultWeatherProvider]);
        foreach ($weatherDrivers as $driverName => $weatherDriver) {
            config(['weather.data_source.default_weather_provider' => $driverName]);
            try {
                $this->broadcast = app()->make($weatherDriver['driver'])->fetchWeather($this->city);
            } catch (\Exception $exception) {

            }

            if ($this->broadcast) {
                break;
            }
        }
    }

    public function store(): static
    {
        $this->broadcast?->save();

        return $this;
    }

    private function getObservedCity(): void
    {
        $conditionArray = [
            'name' => '',
            'state_code' => '',
            'country_code' => '',
        ];
        $cityName = env('WEATHER_CITY_TARGET', 'Kyiv');
        $cityParts = explode(',', $cityName);
        $cityParts = array_map(function (string $part) {
            return trim($part);
        }, $cityParts);

        $i = 0;
        foreach($conditionArray as $key => $value) {
            $conditionArray[$key] = $cityParts[$i] ?? '';
            $i++;
        }

        $this->city = City::firstOrCreate(array_filter($conditionArray));
    }

    private function initMode(): void
    {
        $failsafeMode = config('weather.data_source.failsafe');
        $this->failsafeMode = $failsafeMode;
    }

    public function getCity(): City
    {
        return $this->city;
    }

    public function setCity(City $city): void
    {
        $this->city = $city;
    }

    public function getBroadcast(): Broadcast
    {
        return $this->broadcast;
    }
}
