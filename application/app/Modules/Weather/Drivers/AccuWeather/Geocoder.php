<?php

namespace app\Modules\Weather\Drivers\AccuWeather;

use App\Modules\Weather\Drivers\Contracts\Geocoder as GeocoderContract;
use App\Modules\Weather\Models\City;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;

class Geocoder implements GeocoderContract
{

    private string $apiKey = '';

    public function __construct(private readonly ClientInterface $client)
    {
        $this->apiKey = config('weather.data_source.drivers.accuweather.key');
    }

    public function findCityCoord(City $city): array
    {
        $preparedValues = array_filter([$city->name, $city->state_code, $city->country_code]);
        $query = implode(',', $preparedValues);

        $response = $this->client->get('/locations/v1/cities/search', [
            'query' => [
                'q' => $query,
                'apikey' => $this->apiKey
            ]
        ]);

        $content = $this->getContent($response)[0];

        return [
            'key' => $content['Key']
        ];
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
