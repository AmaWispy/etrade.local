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
        Schema::create('shop_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_attribute_id')->nullable()->nullOnDelete();
            $table->string('attr_key')->unique();
            $table->string('attr_value');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_attribute_values');
    }
};
