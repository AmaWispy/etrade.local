<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use App\Models\UnicodeModel;
use Spatie\Translatable\HasTranslations;
//use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Attribute extends UnicodeModel
{
    use HasFactory;
    use HasTranslations;

    /**
     * @var string
     */
    protected $table = 'shop_attributes';

    public $translatable = [
        'name',
        'description',
    ];

    protected $fillable = [
        'name',
        'key',
        'description',
        'is_acrtive',
    ];

    public function managedAttributeValues(){
        return $this->hasMany(AttributeValue::class, 'shop_attribute_id', );
    }

    public function managedCategory(){
        return $this->belongsToMany(Category::class,'shop_attribute_id', 'shop_category_id');
    }
}
