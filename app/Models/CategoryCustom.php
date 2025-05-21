<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryCustom extends Model
{
    protected $table = 'categories';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'code',
        'name',
        'name_ru',
        'name_en',
        'some_order',
        'parent_code',
        'parent'
    ];

    protected $casts = [
        'code' => 'integer',
        'some_order' => 'integer',
        'parent_code' => 'integer'
    ];

    // Отношение к родительской категории
    public function parentCategory()
    {
        return $this->belongsTo(CategoryCustom::class, 'parent_code', 'code');
    }

    // Отношение к дочерним категориям
    public function childCategories()
    {
        return $this->hasMany(CategoryCustom::class, 'parent_code', 'code');
    }
} 