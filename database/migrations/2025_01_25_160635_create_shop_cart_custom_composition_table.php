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
        Schema::create('shop_cart_custom_composition', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_cart_item_id')->constrained('shop_cart_item')->cascadeOnDelete();
            $table->foreignId('shop_composition_id')->constrained('shop_product_composition')->cascadeOnDelete();
            $table->integer('qty')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_cart_custom_composition');
    }
};
