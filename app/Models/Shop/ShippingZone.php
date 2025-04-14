<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ShippingZone extends Model
{
    protected $casts = [
        'is_active' => 'boolean',
        'on_map' => 'boolean',
        'localities' => 'array'
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Country::class, 'shipping_zone_locality', 'shipping_zone_id', 'country_id');
    }

    public function localities(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\City::class, 'shipping_zone_locality', 'shipping_zone_id', 'locality_id');
    }
}
