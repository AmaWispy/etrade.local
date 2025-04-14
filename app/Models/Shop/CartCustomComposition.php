<?php

namespace App\Models;

use App\Models\Shop\Cart;
use App\Models\Shop\CartItem;
use App\Models\Shop\ProductComposition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartCustomComposition extends Model
{
    use HasFactory;
    protected $table = 'shop_cart_custom_composition';

    public function cart(){
        return $this->hasOne(CartItem::class, 'shop_cart_item_id');
    }
    public function managedCart(){
        return $this->belongsTo(CartItem::class);
    }

    public function composition(){
        return $this->hasOne(ProductComposition::class);
    }

    public function managedComposition(){
        return $this->belongsTo(ProductComposition::class);
    }
}
