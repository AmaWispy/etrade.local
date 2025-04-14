<?php

use App\Models\Shop\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up()
    {
        Schema::create('shop_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Order::class);
            $table->string('reference'); // Transaction id assigned by provider
            $table->string('provider');
            $table->decimal('amount');
            $table->string('currency');
            $table->enum('status', [
                'registered', // obtained notification, verification required
                'succeed', // verified, succeed
                'declined', // something gone wrong, explore response (info) to find what exactly
                'failed' // payment failed
            ])->default('registered');
            $table->json('info')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shop_payments');
    }
};
