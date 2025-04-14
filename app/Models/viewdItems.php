<?php

namespace App\Models;

use App\Models\Blog\Post;
use App\Models\Shop\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ViewdItems extends Model 
{
    use HasFactory;
    

    public function users(): HasOne{
        return $this->hasOne(User::class,'id', 'user_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function shopProducts(): HasMany  {
        return $this->hasMany(Product::class , 'id', 'shop_product_id');
    }

    public function blogPosts(): HasMany{
        return $this->hasMany(Post::class , 'id', 'blog_post_id');
        
    }
}
