<?php

namespace App\Models\Shop;

//use Illuminate\Database\Eloquent\Model;

use App\Models\CartCustomComposition;
use App\Models\UnicodeModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
//use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductComposition extends UnicodeModel
{
    /**
     * @var string
     */
    protected $table = 'shop_product_composition';


    public function parentProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'shop_parent_product_id');
    }
    
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'shop_product_id');
    }

    public function cartCustomComposition(){
        return $this->belongsTo(CartCustomComposition::class);
    }

    public function variation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class, 'shop_product_variation_id');
    }
}
