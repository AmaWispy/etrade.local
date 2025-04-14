<?php

namespace App\Observers;

use App\Models\Shop\Product;
use App\Models\Shop\Attribute;
use App\Models\Shop\AttributeValue;
use App\Models\Shop\ProductVariation;
use App\Models\Shop\ProductComposition;
use Illuminate\Support\Facades\DB;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        /**
         * TODO: Add attributes to variation name
         * or it would be better to do by casting variation name attribute in model
         */
        $options = $product->options;
        if(!empty($options)){
           
            /**
             * Variations prices and images
             */
            $preparedArrays = $this->prepareArrays($options);
            
            /**
             * All possible combinations of provided attributes
             */
            $combinations = $this->cartesian($preparedArrays['cartesian']);
            
            foreach($combinations as $combination){
                /**
                 * Create key (attr_val_attr_val)
                 */
                $data = $this->prepareVariationData($combination, $preparedArrays['prices'], $preparedArrays['images']);

                /**
                 * Save variation
                 */
                $variation = ProductVariation::create([
                    'key' => $data['key'],
                    'name' => $this->buildVariationName($product->getTranslations('name'), $data['relations']),
                    'shop_product_id' => $product->id,
                    'price' =>  !empty($data['price']) ? $data['price'] : $product->base_price
                ]);

                $this->addMedia($data['media'], $variation);

                /**
                 * Save variation relations
                 */
                $relations = $this->prepareRelations($data['relations']);
                foreach($relations as $key => $relation){
                    $relations[$key] = array_merge(['shop_variation_id' => $variation->id], $relation);
                }
                DB::table('shop_variation_attribute')->insert($relations);
            }
            
        }

        /**
         * Save composition items
         */
        $composition = $product->composition;
        if(!empty($composition)){
            $complexPrice = 0;
            foreach($composition as $item){
                $key = "parent_" . $product->id . "_child_" . $item['product'] . '_variation_' . $item['variation'];
                $unitPrice = $this->getUnitPrice($item);
                $complexPrice += $unitPrice * $item['quantity'];
                ProductComposition::create([
                    'key' => $key,
                    'shop_parent_product_id' => $product->id,
                    'shop_product_id' => $item['product'],
                    'shop_product_variation_id' => $item['variation'],
                    'unit_price' => $unitPrice,
                    'qty' => $item['quantity']
                ]);
            }
        }
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {   
        /**
         * TODO: Calc hash of options to prevent updating variations in case 
         * there are not changed attributes or values
         */
        /**
         * Prepare array of existing variations
         */
        $variations = $product->variations()->pluck('id', 'key')->toArray();

        $options = $product->options;
        if(!empty($options)){
           
            /**
             * Variations prices and images
             */
            $preparedArrays = $this->prepareArrays($options);
            
            /**
             * All possible combinations of provided attributes
             */
            $combinations = $this->cartesian($preparedArrays['cartesian']);
            
            foreach($combinations as $combination){
                /**
                 * Create key (attr_val_attr_val)
                 */
                $data = $this->prepareVariationData($combination, $preparedArrays['prices'], $preparedArrays['images']);

                /**
                 * If variation allready exists
                 * just update
                 * else - create a new one
                 */
                if(isset($variations[$data['key']])){
                    $variation = ProductVariation::find($variations[$data['key']]);
                    $variation->name = $this->buildVariationName($product->getTranslations('name'), $data['relations']);
                    $variation->price = !empty($data['price']) ? $data['price'] : $product->base_price;
                    $variation->update();

                    /**
                     * Clear existing relations for variation
                     */
                    DB::table('shop_variation_attribute')
                        ->where('shop_variation_id', $variation->id)
                        ->delete();
                    /**
                     * Delete variation media
                     */
                    $media = $variation->getMedia('*');
                    foreach($media as $item){
                        $item->delete();
                    }

                } else {
                    /**
                     * Create variation
                     */
                    $variation = ProductVariation::create([
                        'key' => $data['key'],
                        'name' => $this->buildVariationName($product->getTranslations('name'), $data['relations']),
                        'shop_product_id' => $product->id,
                        'price' =>  !empty($data['price']) ? $data['price'] : $product->base_price
                    ]);
                }

                $this->addMedia($data['media'], $variation);

                /**
                 * Save variation relations
                 */
                $relations = $this->prepareRelations($data['relations']);
                foreach($relations as $key => $relation){
                    $relations[$key] = array_merge(['shop_variation_id' => $variation->id], $relation);
                }
                DB::table('shop_variation_attribute')->insert($relations);

                /**
                 * Unset record
                 */
                unset($variations[$data['key']]);
            }
        }

        /**
         * Clear variations remained in case attributes were changed
         */
        $this->removeVariations(array_values($variations));

        /**
         * Save composition items
         */
        $composition = $product->compositionList()->pluck('id', 'key')->toArray();
        $updatedComposition = $product->composition;
        if(!empty($updatedComposition)){
            $complexPrice = 0;
            foreach($updatedComposition as $item){
                $key = "parent_" . $product->id . "_child_" . $item['product'] . '_variation_' . $item['variation'];
                $unitPrice = $this->getUnitPrice($item);
                $complexPrice += $unitPrice * $item['quantity'];
                if(isset($composition[$key])){
                    $record = ProductComposition::find($composition[$key]);
                    $record->unit_price = $unitPrice;
                    $record->qty = $item['quantity'];
                    $record->update();
                } else {
                    ProductComposition::create([
                        'key' => $key,
                        'shop_parent_product_id' => $product->id,
                        'shop_product_id' => $item['product'],
                        'shop_product_variation_id' => $item['variation'],
                        'unit_price' => $unitPrice,
                        'qty' => $item['quantity']
                    ]);
                }

                unset($composition[$key]);
            }

            $this->clearComposition(array_values($composition));
        } else {
            /**
             * Remove all composition items
             */
            DB::table('shop_product_composition')
                ->where('shop_parent_product_id', $product->id)
                ->delete();
        }

        /**
         * Check if product is in composition of other products
         * Just for simple products, for variable products variation will be used
         * Variations are tracked in VariationObserver
         * Complex products are not supposed to be used in other complex products
         * At least for now
         */
        if($product->type === Product::SIMPLE){
            DB::table('shop_product_composition')
                ->where('shop_product_id', $product->id)
                ->update(['unit_price' => $product->base_price]); // Base price without additional costs
        }
    }

    /**
     * Get price of complex product item
     */
    protected function getUnitPrice($item)
    {
        $price = 0;
        if(null !== $item['variation']){
            $variation = ProductVariation::find($item['variation']);
            $price = $variation->getOriginalPrice();
        } else {
            $product = Product::find($item['product']);
            $price = $product->getOriginalPrice();
        }

        return $price;
    }

    /**
     * Prepare variation price, media, relations
     */
    protected function prepareVariationData($combination, $prices, $images)
    {
        $key = "";
        $price = null;
        $media = [];
        $relations = [];

        foreach($combination as $attr_key => $val_key){
            /**
             * Generate variation key by concatenation keys
             * of attributes and values 
             */
            $key .= $attr_key . '_' . $val_key;
            if($attr_key !== array_key_last($combination)){
                $key .= '_';
            }
            /**
             * Check if there are prices or images for variations
             */
            $price_key = $val_key . '_price';
            if(isset($prices[$price_key]) && null !== $prices[$price_key]){
                $price = $prices[$price_key];
            }
            $media_key = $val_key . '_images';
            if(isset($images[$media_key]) && !empty($images[$media_key])){
                $media = $images[$media_key];
            }
            /**
             * Prepare variation => attribute relation
             */
            $relations[] = [
                'shop_attribute' => $attr_key,
                'shop_attr_value' => $val_key
            ];
        }

        return [
            'key' => $key,
            'price' => $price,
            'media' => $media,
            'relations' => $relations
        ];
    }

    /**
     * Prepare prices and images for variations
     */
    protected function prepareArrays($options)
    {
        /**
         * Prepared array to generate combinations (cartesian)
         * [attr => [values]]
         */
        $cartesian = [];  // For cartesian
        $prices = []; // Prices for variations
        $images = []; // Images for variations
        foreach($options as $option){
            $cartesian[$option['attribute']] = $option['values'];
            /**
             * Collect prices and images for variations
             */
            foreach($option as $key => $value) {
                if(str_contains($key, '_price')){
                    $prices[$key] = $value; 
                }
                if(str_contains($key, '_images')){
                    $images[$key] = $value;
                }
            }
        }

        return [
            'cartesian' => $cartesian,
            'prices' => $prices,
            'images' => $images
        ];
    }

    /**
     * Replace keys with ids
     */
    protected function prepareRelations($relations)
    {
        $attributes = Attribute::query()->pluck('id', 'key');
        $values = AttributeValue::query()->pluck('id', 'attr_key');

        foreach($relations as $key => $relation)
        {   
            $relations[$key]['shop_attribute_id'] = $attributes[$relation['shop_attribute']];
            $relations[$key]['shop_attr_value_id'] = $values[$relation['shop_attr_value']];
            unset($relations[$key]['shop_attribute']);
            unset($relations[$key]['shop_attr_value']);
        }

        return $relations;
    }

    protected function buildVariationName($translations, $relations)
    {
        /**
         * TODO: Not perfect solution
         *       Would be better to not query attr values in loop
         */
        $res = AttributeValue::query()->get();
        $values = [];
        foreach($res as $value){
            $values[$value->attr_key] = $value->getTranslations('attr_value');
        }
        $first_key = array_key_first($relations);
        $last_key = array_key_last($relations);
        $names = [];
        foreach($translations as $lang => $name){
            $chunks = "";
            foreach($relations as $key => $relation){
                if($key === $first_key){
                    $chunks .= "(";
                }
                $chunks .= $values[$relation['shop_attr_value']][$lang];
                if($key !== $last_key){
                    $chunks .= ", ";
                } else {
                    $chunks .= ")";
                }
            }
            $names[$lang] = $name . ' ' . $chunks;
        }

        return $names;
    }

    /**
     * Attach media to variation
     */
    protected function addMedia($media, $variation)
    {
        if(!empty($media)){
            foreach($media as $img){
                $path = storage_path('app' . DIRECTORY_SEPARATOR . 'public') . DIRECTORY_SEPARATOR . $img;
                $variation
                    ->addMedia($path)
                    ->preservingOriginal()
                    ->toMediaCollection('variation-images');
            }
        }
    }

    /**
     * Remove variations, its relations and media
     */
    protected function removeVariations($ids)
    {
        if(!empty($ids)){
            $variations = ProductVariation::find($ids);
            foreach($variations as $variation){
                /**
                 * Check if variation is used in composition
                 * Remove the variation from composition
                 */
                DB::table('shop_product_composition')
                    ->where('shop_product_variation_id', $variation->id)
                    ->delete();
                /**
                 * Clear existing relations for variation
                 */
                DB::table('shop_variation_attribute')
                    ->where('shop_variation_id', $variation->id)
                    ->delete();
                /**
                 * Delete variation itself
                 */
                $variation->delete();
            }
        }
    }

    /**
     * Remove composition which remained unhandled after parent product update
     */
    protected function clearComposition($ids)
    {
        if(!empty($ids)){
            $composition = ProductComposition::find($ids);
            foreach($composition as $item){
                $item->delete();
            }
        }
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        // TODO: delete all connected variations, relations and media
        //       also delete from composition
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }

    /**
     * Cartesian product of arrays
     * Generate all possible combinations of provided attributes
     * Each combination contains attribute => value pair
     */
    protected function cartesian($arrays) {
        $result = [[]];
        foreach ($arrays as $attr => $values) {
            $temp = [];
            foreach ($result as $resultItem) {
                foreach ($values as $value) {
                    $pair = [];
                    $pair[$attr] = $value;
                    $temp[] = array_replace($resultItem, $pair);
                }
            }
            $result = $temp;
        }
        return $result;
    }
}
