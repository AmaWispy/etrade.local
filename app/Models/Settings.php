<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use App\Models\UnicodeModel;
use Spatie\Translatable\HasTranslations;

class Settings extends UnicodeModel
{
    use HasTranslations;

    protected $fillable = [
        'key',
        'content',
        'image',
    ];

    protected $casts = [
        'content' => 'array'
    ];
    public $translatable = [
        'content'
    ];
}
