<?php

namespace App\Models\Shop;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use App\Models\UnicodeModel;
use App\Models\viewdItems;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class Product extends UnicodeModel implements HasMedia
{
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;
    
    /**
     * @var string
     */
    protected $table = 'products';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'code',
        'name',
        'name_ru',
        'name_en',
        'name_full',
        'slug',
        'sku',
        'articul',
        'brand',
        'brand_code',
        'category',
        'category_code',
        'additional_cat',
        'additional_cat_code',
        'description',
        'unit_type',
        'stock_quantity',
        'reserved',
        'price',
        'currency',
        'default_price',
        'default_currency',
        'discount',
        'discount_date_start',
        'discount_date_end',
        'is_active'
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'integer',
        'stock_quantity' => 'integer',
        'reserved' => 'integer',
        'price' => 'decimal:2',
        'default_price' => 'decimal:2',
        'discount' => 'float',
        'slug' => 'array',
        'is_active' => 'boolean'
    ];

    public $translatable = [
        'description'
    ];

    /**
     * Types of product
     */
    const SIMPLE = 'simple';
    const VARIABLE = 'variable';
    const COMPLEX = 'complex';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = [
                    'ru' => Str::slug($product->name_ru == "" ? $product->name : $product->name_ru),
                    'ro' => Str::slug($product->name == "" ? $product->name_ru : $product->name),
                    'en' => Str::slug($product->name_en == "" ? $product->name : $product->name_en)
                ];
            }

            // Если описание не является массивом (т.е. пришло из API), устанавливаем его для всех языков
            if (!is_array($product->description) && !empty($product->description)) {
                $product->setDescriptionForAllLanguages($product->description);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty(['name', 'name_ru', 'name_en']) && empty($product->slug)) {
                $product->slug = [
                    'ru' => Str::slug($product->name_ru == "" ? $product->name : $product->name_ru),
                    'ro' => Str::slug($product->name == "" ? $product->name_ru : $product->name),
                    'en' => Str::slug($product->name_en == "" ? $product->name : $product->name_en)
                ];
            }

            // Если описание не является массивом (т.е. пришло из API), устанавливаем его для всех языков
            if ($product->isDirty('description') && !is_array($product->description) && !empty($product->description)) {
                $product->setDescriptionForAllLanguages($product->description);
            }
        });
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(100)
            ->height(100)
            ->sharpen(10);

        $this->addMediaConversion('medium')
            ->width(400)
            ->height(400)
            ->sharpen(10);

        // FIT_MAX prevents of generating oversized media
        $this->addMediaConversion('main')->fit(Manipulations::FIT_MAX, 992, 992);
    }

    /**
     * Get all product images
     */
    public function getImages(): array
    {
        $images = [];
        $media = $this->getMedia('product-images');

        if ($media->isEmpty()) {
            return [
                [
                    'original' => url('storage/no-image_620x620.png'),
                    'thumb' => url('storage/no-image_420x420.png'),
                    'medium' => url('storage/no-image_620x620.png')
                ]
            ];
        }

        foreach ($media as $item) {
            $images[] = [
                'original' => $item->getUrl(),
                'thumb' => $item->getUrl('thumb'),
                'medium' => $item->getUrl('medium')
            ];
        }

        return $images;
    }

    /**
     * Get first product image
     */
    public function getFirstImage(): array
    {
        $media = $this->getFirstMedia('product-images');
        
        if (!$media) {
            return [
                'original' => url('storage/no-image_620x620.png'),
                'thumb' => url('storage/no-image_420x420.png'),
                'medium' => url('storage/no-image_620x620.png')
            ];
        }

        return [
            'original' => $media->getUrl(),
            'thumb' => $media->getUrl('thumb'),
            'medium' => $media->getUrl('medium')
        ];
    }

    /**
     * Get all thumbnails
     */
    public function getThumbs(): array
    {
        $thumbs = [];
        $media = $this->getMedia('product-images');

        if ($media->isEmpty()) {
            return [url('storage/no-image_420x420.png')];
        }

        foreach ($media as $item) {
            $thumbs[] = $item->getUrl('thumb');
        }

        return $thumbs;
    }

    /**
     * Get all medium images
     */
    public function getMediumImages(): array
    {
        $mediums = [];
        $media = $this->getMedia('product-images');

        if ($media->isEmpty()) {
            return [url('storage/no-image_620x620.png')];
        }

        foreach ($media as $item) {
            $mediums[] = $item->getUrl('medium');
        }

        return $mediums;
    }

    /**
     * Get all original images
     */
    public function getOriginalImages(): array
    {
        $originals = [];
        $media = $this->getMedia('product-images');

        if ($media->isEmpty()) {
            return [url('storage/no-image_620x620.png')];
        }

        foreach ($media as $item) {
            $originals[] = $item->getUrl();
        }

        return $originals;
    }

    /**
     * Get first thumbnail
     */
    public function getFirstThumb(): string
    {
        $media = $this->getFirstMedia('product-images');
        return $media ? $media->getUrl('thumb') : url('storage/no-image_420x420.png');
    }

    /**
     * Get first medium image
     */
    public function getFirstMedium(): string
    {
        $media = $this->getFirstMedia('product-images');
        return $media ? $media->getUrl('medium') : url('storage/no-image_620x620.png');
    }

    /**
     * Get first original image
     */
    public function getFirstOriginal(): string
    {
        $media = $this->getFirstMedia('product-images');
        return $media ? $media->getUrl() : url('storage/no-image_620x620.png');
    }

    /**
     * Empty options in case type != variable
     */
    /*protected function options(): Attribute
    {
        /**
         * Strange thing, mutator does not work without condition below
         *
        if($this->attributes['type'] !== 'variable'){
            $this->attributes['options'] = '[]';
        }

        return Attribute::make(
            set: fn (array $value) => $this->attributes['type'] !== 'variable' ? '[]' : $value,
        );
    }*/

    /**
     * Empty composition in case type != complex
     */
    /*protected function composition(): Attribute
    {
        /**
         * Strange thing, mutator does not work without condition below
         *
        if($this->attributes['type'] !== 'complex'){
            $this->attributes['composition'] = '[]';
        }
        
        return Attribute::make(
            set: fn (array $value) => $this->attributes['type'] !== 'complex' ? '[]' : $value,
        );
    }*/

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'shop_brand_id');
    }

    public function viewed(){
        return $this->belongsTo(viewdItems::class, 'shop_product_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'shop_category_product', 'shop_product_id', 'shop_category_id')->withTimestamps();
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Shop\Attribute::class, 'shop_product_variations', 'shop_product_id', 'id');
    }

    public function attributeValues(): HasMany
    {
        return $this->hasMany(AttributeValue::class, 'product_id');
    }

    public function isVariable()
    {
        return $this->type === self::VARIABLE;
    }

    public function discounts(): MorphToMany
    {
        $today = now()->toDateString(); // Get the current date in 'Y-m-d' format
        $now = now()->toTimeString(); // Get the current time in 'H:i:s' format
        return $this->morphToMany(Discount::class, 'discountable')
                    ->where([
                        ['start_date', '<=', $today], // Including start date
                        ['end_date', '>', $today] // Excluding end date
                    ])
                    ->where(function ($query) use ($now) {
                        /**
                         * Verify time in subquery
                         */
                        $query->where(function ($q) use ($now) {
                            $q->whereTime('start_time', '<=', $now)
                                ->whereTime('end_time', '>', $now);
                        });
                    })
                    ->orderBy('created_at', 'desc'); // Get latest first
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'product_id')->where('type_id', 1)->orderBy('created_at', 'desc');
    }

    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class, 'shop_product_id');
    }

    /**
     * Composition
     */
    public function compositionList(): HasMany
    {
        return $this->hasMany(ProductComposition::class, 'shop_parent_product_id');
    }

    /**
     * Get array of attributes
     */
    public function getAttrKeysArray()
    {
        $attributes = [];

        if(null !== $this->variations){
            foreach($this->variations as $variation){
                $varAttrs = $variation->getAttributesWithValues();
                foreach($varAttrs as $varAttr){
                    if(!in_array($varAttr['key'], $attributes)){
                        $attributes[] = $varAttr['key'];
                    }
                }
            }
        }

        return $attributes;
    }

    /**
     * Get products full set of attributes
     * Loop through variations and collect attributes and values
     */
    public function getAttributesWithValues()
    {   
        $attributes = [];

        if(null !== $this->variations){
            foreach($this->variations as $variation){
                $varAttrs = $variation->getAttributesWithValues();
                foreach($varAttrs as $varAttr){
                    if(!isset($attributes[$varAttr['id']])){
                        $attributes[$varAttr['id']] = [
                            'id' => $varAttr['id'],
                            'key' => $varAttr['key'],
                            'name' => $varAttr['name'],
                            'values' => []
                        ];
                    } 
                    $valId = $varAttr['value']['id'];
                    $attributes[$varAttr['id']]['values'][$valId] = $varAttr['value'];
                }
            }
        }

        return $attributes;
    }

    public function getThumb()
    {
        $media = $this->getFirstMedia("product-images"); // Get from product-image collection
        if(null !== $media){
            return $media->getUrl('thumb-md');
        }
        return url('storage/no-image_420x420.png');
    }

    public function getImage()
    {
        $media = $this->getFirstMedia("product-images"); // Get from product-image collection
        if(null !== $media){
            return $media->getUrl('main');
        }
        return url('storage/no-image_620x620.png');
    }

    public function getPriceAttribute($value)
    {
        return number_format($value, 2, '.', '');
    }

    public function getDefaultPriceAttribute($value)
    {
        return number_format($value, 2, '.', '');
    }

    public function getActiveDiscount()
    {
        /**
         * Get first discount (query ordered by created_at desc, so the first will be the latest added)
         */
        if($this->discounts->isNotEmpty()){
            return $this->discounts->first();
        }
        
        /* if($this->mainCategory->discounts->isNotEmpty()){
            return $this->mainCategory->discounts->first();
        } */

        return null;
    }

    /**
     * Check if product is on sale
     */
    public function onSale()
    {
        return null !== $this->getActiveDiscount();
    }

    /**
     * Get badge according to sale badge
     */
    public function getSaleBadge()
    {
        $badge = null;

        if(null !== $discount = $this->getActiveDiscount()){
            $amount = round($discount->amount);
            switch ($discount->type) {
                case DISCOUNT::PERCENT:
                    $badge = "-" . $amount . "%";
                    break;
                
                case DISCOUNT::AMOUNT:
                    $badge = "-" . $amount . "mdl";
                    break;
                
                case DISCOUNT::PRICE:
                    $badge = 'NEW PRICE';
                    break;
            }
        }

        return $badge;
    }

    /**
     * Check if product is new (added less than a month ago)
     */
    public function isNew()
    {
        return false;
        $publishDate = Carbon::parse($this->published_at);
        $currentDate = Carbon::now();
        return $publishDate->diffInDays($currentDate) <= 30;
    }

    /**
     * Get the original price, considering additional costs
     */
    public function getOriginalPrice()
    {
        $price = $this->base_price;
        if($this->additional_costs !== null && $this->additional_costs > 0){
            $price += $this->additional_costs;
        }

        return round($price, 2) ;
    }

    /**
     * Get final price, considering discounts
     */
    public function getPrice()
    {
        $price = $this->getOriginalPrice();

        if(null !== $discount = $this->getActiveDiscount()){
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

    public function getExchangedPriceCustom($basePrice = false, $format = true)
    {
        $price = $basePrice ? $this->price : $this->default_price;
        
        $exchanged = $basePrice ? Currency::exchange($price, 'usd') : Currency::exchange($price);

        if($format){
            $exchanged = Currency::format($exchanged);
        }
        
        return str_replace(['.00', ','], ['', ' '], $exchanged);
    }

    public function getExchangedPriceCustom2($basePrice = false, $format = true, $discount = false)
    {
        $price = $basePrice ? $this->price : $this->default_price;
        if ($discount && $this->discount && $this->discount_date_end && $this->discount_date_end > now() && $this->discount_date_start < now()) {
            $price = $price - ($price * ($this->discount / 100));   
        }
        
        $exchanged = Currency::exchange($price);

        if($format){
            $exchanged = Currency::format($exchanged);
        }
        
        return str_replace(['.00', ','], ['', ' '], $exchanged);
    }

    /**
     * Make excerpt by limiting content length
     */
    public function getExcerptAttribute()
    {
        return $this->makeExcerpt($this->content);
    }

    public function makeExcerpt($text, $length = 150, $end = '...') {
        // Remove any HTML tags and convert entities to their applicable characters
        $text = strip_tags($text);
    
        // Trim the text to the desired length and append the specified ending
        $excerpt = Str::limit($text, $length, $end);
    
        return "$excerpt";
    }

    public function getMainCategoryAttribute()
    {
        /**
         * Get the category with the biggest id
         */
        $category = $this->categories()->orderBy('shop_categories.parent_id', 'DESC')->first();
        return $category;
    }

    /**
     * Get product url
     */
    public function getLinkAttribute()
    {
        $link = $this->mainCategory->link;
        $link .= "/" . $this->slug;
        return $link;
    }

    /**
     * Prepare localized routes for language switcher and hreflang
     */
    public static function getRouteTranslations(Product $product)
    {   
        $routes = Category::getRouteTranslations($product->mainCategory);
        $slugs = $product->getTranslations('slug'); 
        foreach($slugs as $locale => $slug){
            $routes[$locale] .= "/" . $slug;
        }

        return $routes;
    }

    /**
     * Set description for all languages
     */
    public function setDescriptionForAllLanguages($description)
    {
        $this->description = [
            'ru' => $description,
            'ro' => $description,
            'en' => $description
        ];
        return $this;
    }

    public function getTranslatedName()
    {
        return match(app()->getLocale()) {
            'ru' => $this->name_ru,
            'en' => $this->name_en,
            default => $this->name,
        };
    }

}