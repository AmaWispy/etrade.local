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
        Schema::create('shop_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_cart_id');
            $table->foreignId('shop_shipping_method_id');
            $table->foreignId('shop_customer_id');
            $table->foreignId('shop_customer_address_id');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('shipping', 12, 2);
            $table->foreignId('shipping_details_id')->nullable();
            $table->decimal('total', 12, 2);
            $table->foreignId('shop_payment_method_id');
            $table->enum('status', [
                'new', // new order
                'pending', // awaiting response from payment gateway
                'verification', // transaction verification
                'processing' // paid order (or cash on delivery selected)
            ])->default('new');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_orders');
    }
};
