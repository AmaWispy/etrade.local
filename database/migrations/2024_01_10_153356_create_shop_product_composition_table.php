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
        Schema::create('shop_product_composition', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique('id'); // Item unique key, ex: complex_1_product_1_variation_2
            $table->foreignId('shop_parent_product_id');
            $table->foreignId('shop_product_id');
            $table->foreignId('shop_product_variation_id')->nullable();
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->integer('qty')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_product_composition');
    }
};
