<?php

namespace App\Models;

use App\Models\Shop\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class viewdItems extends Model 
{
    use HasFactory;
    
    public function users(){
        return $this->hasOne(User::class,'id', 'user_id');
    }

    public function shopProducts(){
        return $this->hasMany(Product::class , 'id', 'shop_product_id');
    }
}
