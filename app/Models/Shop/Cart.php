<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\OrderCustom;

class Cart extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'shop_carts';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'total_price',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class, 'shop_cart_id');
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'shop_cart_id');
    }

    public function orderCustom(): HasOne
    {
        return $this->hasOne(OrderCustom::class, 'cart_id');
    }

    public function getTotal($format = true)
    {
        // total_price теперь в долларах, поэтому используем 'usd' для правильной конвертации
        //$total = Currency::exchange($this->total_price, 'usd');
        $total = $this->total_price;
        if($format){
            $total = Currency::format($total);
        }
        return str_replace(['.00', ','], ['', ' '], $total);
    }

    /*public function getItemsArray()
    {
        $items = [];
        foreach($this->items as $item){
            $key = "p_".$item->shop_product_id;
            if(null !== $item->shop_product_variation_id){
                $key .= "_v_".$item->shop_product_variation_id;
            }

            $items[$key] = [
                'product' => $item->shop_product_id,
                'variation' => $item->shop_product_variation_id,
                'quantity' => $item->qty
            ];
        }
        
        return $items;
    }*/
}
