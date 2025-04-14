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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->json('apply_to')->nullable();
            $table->enum('type', [
                'percent', // Percentage of amount
                'amount', // Amount of amount
                'price', // Set new price
            ])->default('percent');
            $table->enum('rounding', [
                'no_rounding', // Without rounding
                'nearest_five', // Round to nearest five, 17 will be rounded to 15
                'nearest_ten', // Round to nearest ten, 17 will be rounded to 20
            ])->default('nearest_ten');
            $table->decimal('amount', 8, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
        });

        Schema::create('discountables', function (Blueprint $table) {
            $table->foreignId('discount_id');
            $table->morphs('discountable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
        Schema::dropIfExists('discountables');
    }
};
