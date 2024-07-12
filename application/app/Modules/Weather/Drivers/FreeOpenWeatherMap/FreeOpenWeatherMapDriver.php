<?php

namespace App\Modules\Weather\Drivers\FreeOpenWeatherMap;

use App\Modules\Weather\Drivers\Contracts\WeatherProviderDriver;
use App\Modules\Weather\Exceptions\FailedDataFetchingAttemptException;
use App\Modules\Weather\Models\Broadcast;
use app\Modules\Weather\Models\City;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Response;

class FreeOpenWeatherMapDriver implements WeatherProviderDriver
{

    private string $apiKey = '';
    private string $units = '';
    private Geocoder $geocoder;

    public function __construct(private readonly ClientInterface $client)
    {
        $this->initGeocoder();
        $this->apiKey = config('weather.data_source.drivers.openweathermap.key');
        $this->units = config('weather.data_source.units');
    }

    /**
     * @param City $city
     * @return Broadcast
     * @throws FailedDataFetchingAttemptException
     */
    public function fetchWeather(City $city): Broadcast
    {
        try{
            $coords = $this->geocoder->findCityCoord($city);
            $response = $this->client->get('', [
                'query' => [
                    'lat' => $coords['lat'],
                    'lon' => $coords['lon'],
                    'appid' => $this->apiKey,
                    'units' => $this->units
                ]
            ]);

            $responseContent = $this->getContent($response);
        } catch (\JsonException|GuzzleException|ServerException $exception) {
            throw new FailedDataFetchingAttemptException($exception->getMessage());
        }

        $broadcast = new Broadcast;
        $broadcast->fill([
            'temp' => $responseContent['main']['temp'],
            'units' => strtolower($this->units),
            'city_id' => $city->id
        ]);

        return $broadcast;
    }

    private function initGeocoder(): void
    {
        $this->geocoder = app()->make(Geocoder::class);
    }

    /**
     * @param Response $response
     * @return array
     * @throws \JsonException
     */
    private function getContent(Response $response): array
    {
        $content = $response->getBody()->getContents();
        return json_decode($content, true, 512, JSON_THROW_ON_ERROR | JSON_OBJECT_AS_ARRAY);
    }
}
