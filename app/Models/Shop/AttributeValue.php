<?php

namespace App\Models\Shop;

use App\Models\UnicodeModel;
//use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttributeValue extends UnicodeModel
{
    use HasFactory;
    use HasTranslations;

    /**
     * @var string
     */
    protected $table = 'shop_attribute_values';

    public $translatable = [
        'attr_value',
    ];

    protected $fillable  = [
        'shop_attribute_id',
        'attr_value',
        'attr_key',
        'is_active',
    ];
    
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class, 'shop_attribute_id');
    }

    public function productsVariations(){
        return $this->hasMany(VariationAttribute::class,  'shop_attr_value_id', 'id');
    }
}
