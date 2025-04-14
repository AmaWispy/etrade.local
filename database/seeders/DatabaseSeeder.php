<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // $json = '';
        // $products = json_decode($json, true);

        // $faker = Faker::create();

        // $category = \App\Models\Shop\Category::find(1);
        
        // $ids = [];
        // foreach($products as $product){
        //     $model = \App\Models\Shop\Product::create([
        //         'is_visible' => true,
        //         'sku' => !empty($product['sku']) ? $product['sku'] : $faker->unique()->numerify('ART-#####'),
        //         'name' => $product['name'],
        //         'description' => $product['content'], 
        //         'slug' => ['ru' => Str::slug($product['name']['ru'], '-'), 'ro' => Str::slug($product['name']['ro'], '-')],
        //         'base_price' => $product['price'],
        //         'published_at' => date('Y-m-d')
        //     ]);
        //     $ids[] = $model->id;

        //     foreach($product['images'] as $image){
        //         $model
        //             ->addMedia(storage_path("/app/public/import/$image"))
        //             ->preservingOriginal()
        //             ->toMediaCollection('product-images');
        //     }
            
        // }

        // $category->products()->sync($ids);
    }
}
