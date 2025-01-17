<?php

namespace app\Modules\Weather\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'state_code' => $this->state_code ?? '',
            'country_code' => $this->country_code ?? ''
        ];
    }
}
