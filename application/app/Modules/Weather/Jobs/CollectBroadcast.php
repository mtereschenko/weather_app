<?php

namespace App\Modules\Weather\Jobs;

use App\Modules\Weather\Drivers\Contracts\Geocoder;
use App\Modules\Weather\Drivers\Contracts\WeatherProviderDriver;
use App\Modules\Weather\Exceptions\FailedDataFetchingAttemptException;
use App\Modules\Weather\Exceptions\UnableToFetchDataException;
use App\Modules\Weather\Models\Broadcast;
use App\Modules\Weather\Models\City;
use App\Modules\Weather\Services\Broadcast\WeatherRetrieverService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CollectBroadcast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(WeatherRetrieverService $retrieverService): void
    {
        try {
            $retrieverService->retrieveWeather()->store()->getBroadcast();
        } catch (\Exception $e) {
            self::dispatch()->delay(now()->addMinutes(10));
        }
    }
}
