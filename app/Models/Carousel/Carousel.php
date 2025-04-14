<?php

namespace App\Models\Carousel;

//use Illuminate\Database\Eloquent\Model;
use App\Models\UnicodeModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Carousel extends UnicodeModel
{
    use HasTranslations;

    protected $fillable = [
        'key',
        'title',
        'subtitle',
        'content',
        'is_active'
    ];

    public $translatable = [
        'title', 
        'subtitle', 
        'content'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(CarouselItem::class, 'carousel_id');
    }

    /**
     * Get carousel by key
     */
    public static function getByKey($key)
    {
        return Carousel::where('key', $key)->first();
    }
}
