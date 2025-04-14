<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up()
    {
        Schema::create('comment_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('shop_customers')->cascadeOnDelete();
            $table->foreignId('type_id')->constrained('comment_types')->cascadeOnDelete();
            $table->foreignId('blog_id')->nullable()->constrained('blog_posts')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('shop_products')->cascadeOnDelete();
            $table->foreignId('reply_user_id')->nullable()->constrained('users')->cascadeOnDelete();

            // Self-referencing foreign key
            $table->foreignId('reply_id')->nullable()->constrained('comments')->cascadeOnDelete();

            $table->text('content')->nullable();
            $table->integer('rating')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comment_types');
        Schema::dropIfExists('comments');
    }
};
