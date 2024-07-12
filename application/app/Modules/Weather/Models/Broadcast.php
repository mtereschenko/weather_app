<?php

namespace App\Modules\Weather\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Broadcast extends Model
{
    protected $fillable = [
        'temp',
        'units',
        'city_id',
        'provider'
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Broadcast $model) {
            $model->provider = config('weather.data_source.default_weather_provider');
        });
    }
}
