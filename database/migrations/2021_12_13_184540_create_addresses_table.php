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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            //$table->morphs('addressable');
            $table->string('hash', 32)->unique();
            $table->string('country', 3)->nullable();
            // $table->string('other_country')->nullable();
            $table->string('locality', 3)->nullable();
            // $table->string('other_locality')->nullable();
            // $table->string('address')->nullable();
            // $table->string('post_code')->nullable();
            $table->string('district')->nullable();
            $table->string('district_city')->nullable();
            $table->string('street')->nullable();
            $table->string('house_number')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('entrance')->nullable();
            $table->string('floor')->nullable();
            $table->string('intercom')->nullable();
            $table->string('coordinates')->nullable();
            $table->decimal('distance', 8, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('addressables', function (Blueprint $table) {
            $table->foreignId('address_id');
            $table->morphs('addressable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('addressables');
    }
};
