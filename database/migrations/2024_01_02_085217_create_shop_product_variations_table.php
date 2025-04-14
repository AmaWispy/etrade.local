<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shop_product_variations', function (Blueprint $table) {
            $table->id(); // Variation ID
            $table->string('key'); // Variation unique key, ex: color_red_size_xl
            $table->string('name'); // Updated name with attributes
            $table->foreignId('shop_product_id');
            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();

            // Creating a unique constraint on the combination of column1 and column2
            $table->unique(['key', 'shop_product_id'], 'unique_variation_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_product_variations');
    }
};
