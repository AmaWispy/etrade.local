<?php

namespace App\Models\Widgets;

//use Illuminate\Database\Eloquent\Model;
use App\Models\UnicodeModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class WidgetGroup extends UnicodeModel
{
    use HasTranslations;

    protected $fillable = [
        'key',
        'title',
        'content',
        'is_active'
    ];

    public $translatable = [
        'title', 
        'content'
    ];

    public function widgets(): HasMany
    {
        return $this->hasMany(TextWidget::class, 'widget_group_id');
    }
}
