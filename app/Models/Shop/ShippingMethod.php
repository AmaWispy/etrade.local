<?php

namespace App\Models\Shop;

//use Illuminate\Database\Eloquent\Model;
use App\Models\UnicodeModel;
use Spatie\Translatable\HasTranslations;

class ShippingMethod extends UnicodeModel
{
    use HasTranslations;

    protected $casts = [
        'by_distance' => 'boolean', // Allow cost calculation depending on distance
        'conditions' => 'array',
        'is_default' => 'boolean',
        'is_active' => 'boolean'
    ];

    public $translatable = [
        'name', 
        'description'
    ];
}
