<?php

namespace App\Models\Carousel;

//use Illuminate\Database\Eloquent\Model;
use App\Models\UnicodeModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class CarouselItem extends UnicodeModel
{
    use HasTranslations;

    protected $fillable = [
        'image',
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

    public function carousel(): BelongsTo
    {
        return $this->belongsTo(Carousel::class, 'carousel_id');
    }
}
