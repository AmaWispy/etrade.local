<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\UnicodeModel;
use Spatie\Translatable\HasTranslations;

class AttributeGroup extends UnicodeModel
{
    use HasFactory;
    use HasTranslations;

    /**
     * @var string
     */
    protected $table = 'attribute_groups';
    public $timestamps = false;

    public $translatable = [
        'name',
    ];

    protected $fillable = [
        'name',
        'date',
    ];

    /**
     * Атрибуты, которые должны быть преобразованы в даты.
     *
     * @var array
     */
    protected $dates = [
        'date',
    ];

    /**
     * Связь с атрибутами этой группы
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attributes()
    {
        return $this->hasMany(Attribute::class, 'group_id');
    }
} 