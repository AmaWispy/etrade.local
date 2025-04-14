<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_brand_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->longText('description')->nullable();
            $table->string('seo_title', 60)->nullable();
            $table->string('seo_description', 160)->nullable();
            
            $table->decimal('base_price', 10, 2)->nullable();
            $table->decimal('additional_costs', 10, 2)->nullable();

            $table->boolean('manage_stock')->default(false);
            $table->string('sku')->unique()->nullable();
            $table->unsignedBigInteger('qty')->default(0);
            $table->unsignedBigInteger('security_stock')->default(0);
            
            $table->enum('type', [
                'simple', // for simple products
                'variable', // for variable products
                'complex' // for sets
            ])->default('simple');
            $table->json('options')->nullable();
            $table->json('composition')->nullable();

            $table->boolean('is_visible')->default(false);
            $table->date('published_at')->nullable();
            $table->boolean('featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_products');
    }
};
