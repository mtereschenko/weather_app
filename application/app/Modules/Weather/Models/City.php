<?php

namespace App\Modules\Weather\Models;

use App\Models\Token;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer $id
 */
class City extends Model
{
    protected $fillable = [
        'name',
        'state_code',
        'country_code'
    ];

    public function broadcasts(): HasMany
    {
        return $this->hasMany(Broadcast::class);
    }
}
