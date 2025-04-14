<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    /**
     * Discount type
     */
    const PERCENT = 'percent'; // Percentage
    const AMOUNT = 'amount'; // Subtract amount
    const PRICE = 'price'; // Set new price

    /**
     * Rounding type
     */
    const NO_ROUNDING = 'no_rounding'; // No rounding
    const NEAREST_FIVE = 'nearest_five'; // Round to nearest five
    const NEAREST_TEN = 'nearest_ten'; // Round to nearest ten

    protected $casts = [
        'apply_to' => 'array',
        'availability' => 'array'
    ];

    public function categories()
    {
        return $this->morphedByMany(Category::class, 'discountable');
    }

    public function products()
    {
        return $this->morphedByMany(Product::class, 'discountable');
    }
}
