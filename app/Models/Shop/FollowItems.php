<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FollowItems extends Model
{
    use HasFactory;
    /**
     * @var string
     */
    protected $table = 'follow_items';

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Follow::class, 'follow_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'shop_product_id');
    }

    public function variation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class, 'shop_product_variation_id');
    }

    public function getSubtotalAttribute()
    {
        return round($this->unit_price * $this->qty, 2);
    }
}
