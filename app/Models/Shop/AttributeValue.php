<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\UnicodeModel;
use Spatie\Translatable\HasTranslations;

class AttributeValue extends UnicodeModel
{
    use HasFactory;
    use HasTranslations;

    /**
     * @var string
     */
    protected $table = 'attribute_value';
    public $timestamps = false;

    public $translatable = [
        'value',
    ];

    protected $fillable = [
        'attribute_id',
        'product_id',
        'value',
        'date',
    ];

    /**
     * Атрибуты, которые должны быть преобразованы в даты.
     *
     * @var array
     */
    protected $dates = [
        'date',
    ];

    /**
     * Связь с атрибутом
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }

    /**
     * Связь с продуктом
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
} 