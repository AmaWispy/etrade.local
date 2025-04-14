<?php

namespace App\Observers;

use App\Models\Shop\Discount;
use Illuminate\Support\Facades\DB;

class DiscountObserver
{
    /**
     * Handle the Discount "created" event.
     */
    public function created(Discount $discount): void
    {   
        $records = [];
        foreach($discount->apply_to as $apply_to){
            $entity = [
                'discount_id' => $discount->id,
                'discountable_type' => $apply_to['entity']
            ];

            foreach($apply_to['items'] as $id){
                $records[] = array_merge($entity, ['discountable_id' => $id]);
            }
        }

        DB::table('discountables')->insert($records);
    }

    /**
     * Handle the Discount "updated" event.
     */
    public function updated(Discount $discount): void
    {
        /**
         * Clear discountables
         */
        DB::table('discountables')->where('discount_id', $discount->id)->delete();

        /**
         * Save updated data
         */
        $records = [];
        foreach($discount->apply_to as $apply_to){
            $entity = [
                'discount_id' => $discount->id,
                'discountable_type' => $apply_to['entity']
            ];

            foreach($apply_to['items'] as $id){
                $records[] = array_merge($entity, ['discountable_id' => $id]);
            }
        }

        DB::table('discountables')->insert($records);
    }

    /**
     * Handle the Discount "deleted" event.
     */
    public function deleted(Discount $discount): void
    {
        /**
         * Clear discountables
         */
        DB::table('discountables')->where('discount_id', $discount->id)->delete();
    }

    /**
     * Handle the Discount "restored" event.
     */
    public function restored(Discount $discount): void
    {
        //
    }

    /**
     * Handle the Discount "force deleted" event.
     */
    public function forceDeleted(Discount $discount): void
    {
        //
    }
}
