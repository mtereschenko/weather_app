<?php

namespace App\Modules\Weather\Services\GuzzleClient\Middlewares;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\RequestInterface;

final class RequestMiddleware
{
    public function __invoke(RequestInterface $request): RequestInterface
    {
        $filterHeaders = static function (array $headers): array {
            $arrayFilterCallback = static function (string $key): bool {
                return $key !== 'Authorization';
            };

            return array_filter($headers, $arrayFilterCallback, ARRAY_FILTER_USE_KEY) ?? [];
        };

        $filterBody = static function (string $body): array {
            return json_decode($body, true) ?? [];
        };

        $selectedDriver = config('weather.data_source.default_weather_provider');

        $context = [
            'uri'     => $request->getUri()->getHost() . $request->getUri()->getPath(),
            'method'  => $request->getMethod(),
            'headers' => $filterHeaders($request->getHeaders()),
            'body'    => $filterBody($request->getBody()->getContents()),
            'query'   => $request->getUri()->getQuery()
        ];

        Log::channel($selectedDriver)->debug('REQUEST', $context);

        return $request;
    }
}
