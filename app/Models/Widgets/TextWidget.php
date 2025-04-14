<?php

namespace App\Models\Widgets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use App\Models\UnicodeModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;
use Illuminate\Support\Facades\Cache;

class TextWidget extends UnicodeModel
{
    use HasTranslations;
    use HasFactory;

    protected $fillable = [
        'key',
        'image',
        'title',
        'content',
        'is_active'
    ];

    public $translatable = [
        'title_top',
        'title', 
        'title_bottom',
        'content'
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(WidgetGroup::class, 'widget_group_id');
    }

    public static function getTitle(string $key): string
    {
        $widget = TextWidget::query()->where('key', $key)->first();

        if (!$widget) {
            return '';
        }

        return $widget->title;
    }

    public static function getContent(string $key): string
    {
        $widget = Cache::get('text-widget-' . $key, function () use ($key) {
            return TextWidget::query()->where('key', $key)->first();
        });

        if (!$widget) {
            return '';
        }

        return $widget->content;
    }
}
