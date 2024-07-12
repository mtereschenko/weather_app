<?php

namespace App\Modules\Weather\Services\Broadcast;

use Carbon\Carbon;
use App\Modules\Weather\Models\Broadcast;
use Illuminate\Database\Eloquent\Collection;
use app\Modules\Weather\Http\Requests\GetWeatherBroadcastRequest;

class BroadcastService
{
    public function fetchByDate(GetWeatherBroadcastRequest $request): Collection
    {
        $date = Carbon::parse($request->date);
        $dateStart = (clone $date)->startOfDay();
        $dateEnd = (clone $date)->endOfDay();

        return Broadcast::with('city')
            ->whereBetween('created_at', [$dateStart, $dateEnd])
            ->get();
    }
}
