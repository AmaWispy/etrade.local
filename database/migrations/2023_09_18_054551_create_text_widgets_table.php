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
        Schema::create('text_widgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('widget_group_id')->nullable()->nullOnDelete();
            $table->string('key')->unique();
            $table->string('image')->nullable();
            $table->string('title_top')->nullable();
            $table->string('title')->nullable();
            $table->string('title_bottom')->nullable();
            $table->longText('content')->nullable();
            $table->boolean('is_active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('text_widgets');
    }
};
