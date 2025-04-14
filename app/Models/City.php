<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use App\Models\UnicodeModel;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends UnicodeModel
{
    use HasTranslations;

    public $translatable = [
        'name'
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
