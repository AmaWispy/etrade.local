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
        Schema::create('shop_variation_attribute', function (Blueprint $table) {
            $table->primary(['shop_variation_id', 'shop_attribute_id', 'shop_attr_value_id']);
            $table->foreignId('shop_variation_id');
            $table->foreignId('shop_attribute_id');
            $table->foreignId('shop_attr_value_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_variation_attribute');
    }
};
