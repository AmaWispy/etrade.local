<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

/**
 * Abstract model with overwritten function asJson
 * Fix for spatie/laravel-translatable
 * Prevents saving cyrillic chars as html entities
 * Extend from this models, all translatable models
 */
abstract class UnicodeModel extends Model 
{
    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    protected function prefixRouteWithLocale()
    {
        return !App::isLocale(config('app.default_locale'));
    }

    protected function getCurrentLocale()
    {
        return App::currentLocale();
    }
}