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
        Schema::create('nav_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->nullable()->nullOnDelete();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('label');
            $table->string('entity')->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->string('link')->nullable();
            $table->boolean('is_active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nav_items');
    }
};
