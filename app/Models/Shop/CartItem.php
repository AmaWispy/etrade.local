<?php

namespace App\Models\Shop;

use App\Models\CartCustomComposition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    /**
     * @var string
     */
    protected $table = 'shop_cart_items';

    protected $casts = [
        'changed_composition' => 'array'
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class, 'shop_cart_id');
    }

    public function cartCustomComposition(){
        return $this->belongsTo(CartCustomComposition::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'shop_product_id');
    }

    public function variation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class, 'shop_product_variation_id');
    }

    public function getUnitPrice($format = true)
    {   
        // unit_price уже в долларах, поэтому используем 'usd' для правильной конвертации
        //$price = Currency::exchange($this->unit_price, 'usd');
        $price = $this->unit_price;
        if($format){
            $price = Currency::format($price);
        }
        return str_replace(['.00', ','], ['', ' '], $price);
    }

    public function getUnitSubtotal($format = true)
    {
        $price = $this->getUnitPrice(false);
        $subtotal = $price * $this->qty;
        if($format){
            $subtotal = Currency::format($subtotal);
        }
        return str_replace(['.00'], [''], $subtotal);
        return $subtotal;
    }

    public function getSubtotalAttribute()
    {
        return round($this->unit_price * $this->qty, 2);
    }

    
}
