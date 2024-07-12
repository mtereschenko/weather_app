<?php

namespace app\Modules\Weather\Drivers\FreeOpenWeatherMap;

use App\Modules\Weather\Drivers\Contracts\Geocoder as GeocoderContract;
use app\Modules\Weather\Models\City;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use JetBrains\PhpStorm\ArrayShape;

class Geocoder implements GeocoderContract
{

    private string $apiKey = '';

    public function __construct(private readonly ClientInterface $client)
    {
        $this->apiKey = config('weather.data_source.drivers.openweathermap.key');
    }
    #[ArrayShape(['lat' => "string", 'lon' => "string"])] public function findCityCoord(City $city): array
    {
        $preparedValues = array_filter([$city->name, $city->state_code, $city->country_code]);
        $query = implode(',', $preparedValues);

        $response = $this->client->get('', [
            'query' => [
                'q' => $query,
                'appid' => $this->apiKey
            ]
        ]);

        $content = $this->getContent($response);

        return [
            'lat' => $content['coord']['lat'],
            'lon' => $content['coord']['lon'],
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
