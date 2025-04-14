<?php

namespace App\Observers;

use App\Models\Shop\ProductVariation;
use App\Models\Shop\ProductComposition;
use Illuminate\Support\Facades\DB;

class VariationObserver
{
    /**
     * Handle the ProductVariation "created" event.
     */
    public function created(ProductVariation $productVariation): void
    {
        //
    }

    /**
     * Handle the ProductVariation "updated" event.
     */
    public function updated(ProductVariation $productVariation): void
    {
        /**
         * Check if variation is included in composition of other products
         */
        DB::table('shop_product_composition')
            ->where('shop_product_variation_id', $productVariation->id)
            ->update(['unit_price' => $productVariation->price]);

    }

    /**
     * Handle the ProductVariation "deleted" event.
     */
    public function deleted(ProductVariation $productVariation): void
    {
        //
    }

    /**
     * Handle the ProductVariation "restored" event.
     */
    public function restored(ProductVariation $productVariation): void
    {
        //
    }

    /**
     * Handle the ProductVariation "force deleted" event.
     */
    public function forceDeleted(ProductVariation $productVariation): void
    {
        //
    }
}
