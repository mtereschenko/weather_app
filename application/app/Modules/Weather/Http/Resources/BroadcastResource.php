<?php

namespace App\Modules\Weather\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BroadcastResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'temp' => $this->temp,
            'units' => $this->units,
            'city' => new CityResource($this->city)
        ];
    }
}
