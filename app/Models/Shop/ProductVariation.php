<?php

namespace App\Models\Shop;

//use Illuminate\Database\Eloquent\Model;
use App\Models\UnicodeModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;

class ProductVariation extends UnicodeModel implements HasMedia
{
    use HasTranslations;
    use InteractsWithMedia;

    public function productComposition(){
        return $this->hasMany(ProductComposition::class, 'shop_product_variation_id', 'id');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(420)
            ->height(420);

        $this->addMediaConversion('main')
            ->width(620)
            ->height(620);
    }
    protected $fillable = [
        'name',
        'key',
        'shop_product_id',
        'price',
        'is_visible',
    ];
    /**
     * @var string
     */
    protected $table = 'shop_product_variations';

    /**
     * @var array<string, string>
     */
    protected $casts = [  
        'is_visible' => 'boolean'
    ];

    public $translatable = [
        'name',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'shop_product_id');
    }

    public function attributes(): HasManyThrough
    {
        return $this->hasManyThrough(
            Attribute::class,           // The model to access to
            VariationAttribute::class,  // The intermediate table
            'shop_variation_id',         
            'id',                       
            'id',
            'shop_attribute_id'
        );
    }

    public function values(): HasManyThrough
    {
        return $this->hasManyThrough(
            AttributeValue::class,      // The model to access to
            VariationAttribute::class,  // The intermediate table
            'shop_variation_id',         
            'id',                       
            'id',
            'shop_attr_value_id'
        );
    }

    /**
     * Prepare attr => value array
     * for each variation
     */
    public function getAttributesWithValues()
    {
        $attributes = [];
        $values = $this->values()->with('attribute')->get();
        foreach($values as $value){
            $attributes[] = [
                'id' => $value->attribute->id,
                'key' => $value->attribute->key,
                'name' => $value->attribute->name,
                'value' => [
                    'id' => $value->id,
                    'attr_key' => $value->attr_key,
                    'attr_value' => $value->attr_value
                ]
            ];
        }
        return $attributes;
    }

    /**
     * Get the original price, considering additional costs
     */
    public function getOriginalPrice()
    {
        $price = $this->price;
        if($this->product->additional_costs !== null && $this->product->additional_costs > 0){
            $price += $this->product->additional_costs;
        }

        return round($price, 2);
    }

    /**
     * Get final price, considering additional costs and discounts
     */
    public function getPrice() 
    {
        $price = $this->getOriginalPrice();
        
        if(null !== $discount = $this->product->getActiveDiscount()){
            /**
             * Calculate new price, according to discount type
             */
            switch ($discount->type) {
                case DISCOUNT::PERCENT:
                    $discountAmount = $price / 100 * $discount->amount;
                    $price -= $discountAmount;
                    break;
                
                case DISCOUNT::AMOUNT:
                    $price -= $discount->amount;
                    break;
                
                case DISCOUNT::PRICE:
                    $price = $discount->amount;
                    break;
            }

            /**
             * Round discounted price according to selected rounding type
             */
            switch ($discount->rounding) {
                case DISCOUNT::NO_ROUNDING:
                    // Do nothing
                    break;

                case DISCOUNT::NEAREST_FIVE:
                    $price = round($price / 5) * 5; // 17 will be rounded to 15
                    break;
                
                case DISCOUNT::NEAREST_TEN:
                    $price = round($price / 10) * 10; // 17 will be rounded to 20
                    break;
            }
        }

        return str_replace(['.00', ','], ['', ' '], $price);
    }

    /**
     * Get exchanged price
     */
    public function getExchangedPrice($original = false, $format = true)
    {
        $price = $original ? $this->getOriginalPrice() : $this->getPrice();
        
        $exchanged = Currency::exchange($price);

        if($format){
            $exchanged = Currency::format($exchanged);
        }
        
        return str_replace(['.00', ','], ['', ' '], $exchanged);
    }

    public function getImage()
    {
        $media = $this->getFirstMedia("variation-images"); // Get from product-image collection
        if(null !== $media){
            return $media->getUrl();
        }
        return url('storage/no-image.png');
    }
}
