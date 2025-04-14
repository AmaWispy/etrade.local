<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use App\Models\UnicodeModel;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends UnicodeModel 
{
    //use HasFactory;
    use HasTranslations;

    public $translatable = [
        'name'
    ];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'coutry_id');
    }
}
