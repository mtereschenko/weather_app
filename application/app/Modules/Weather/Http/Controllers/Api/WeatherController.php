<?php

namespace App\Modules\Weather\Http\Controllers\Api;

use App\Modules\Weather\Http\Controllers\Controller;
use App\Modules\Weather\Http\Resources\BroadcastResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Modules\Weather\Http\Requests\GetWeatherBroadcastRequest;
use App\Modules\Weather\Services\Broadcast\BroadcastService;

class WeatherController extends Controller
{
    public function fetch(
        GetWeatherBroadcastRequest $request,
        BroadcastService           $broadcastService
    ): AnonymousResourceCollection
    {
        $result = $broadcastService->fetchByDate($request);

        return BroadcastResource::collection($result);
    }
}
