<?php

namespace App\Modules\Weather\Exceptions;

use Illuminate\Support\Facades\Log;

class UnableToFetchDataException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        $selectedDriver = config('weather.data_source.default_weather_provider');
        Log::channel($selectedDriver)->error($message);
        Log::error('Failed data fetching attempts for ' . $selectedDriver . ' weather provider');

        parent::__construct($message, $code, $previous);
    }
}
