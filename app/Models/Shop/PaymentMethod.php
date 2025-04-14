<?php

namespace App\Models\Shop;

//use Illuminate\Database\Eloquent\Model;
use App\Models\UnicodeModel;
use Spatie\Translatable\HasTranslations;

class PaymentMethod extends UnicodeModel
{
    use HasTranslations;

    protected $casts = [
        'config' => 'array',
        'description' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean'
    ];

    public $translatable = [
        'name', 
        'description'
    ];
}
