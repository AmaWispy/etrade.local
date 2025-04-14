<?php

namespace App\Models\Shop;

//use Illuminate\Database\Eloquent\Model;
use App\Models\UnicodeModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VariationAttribute extends UnicodeModel
{
    /**
     * @var string
     */
    protected $table = 'shop_variation_attribute';

    public function variation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class, 'shop_variation_id');
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class, 'shop_attribute_id');
    }

    public function attributeValue(): BelongsTo
    {
        return $this->belongsTo(AttributeValue::class, 'shop_attr_value_id', 'id');
    }
    
}
