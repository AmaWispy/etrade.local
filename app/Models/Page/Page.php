<?php

namespace App\Models\Page;

use Illuminate\Database\Eloquent\Casts\Attribute;
//use Illuminate\Database\Eloquent\Model;
use App\Models\UnicodeModel;
use Spatie\Translatable\HasTranslations;


class Page extends UnicodeModel
{
    use HasTranslations;

    protected $fillable = [
        'slug',
        'name',
        'title',
        'content',
        'template',
        'is_active'
    ];

    public $translatable = [
        'slug',
        'name',
        'title', 
        'content'
    ];

    /**
     * Get the template.
     */
    protected function template(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->prepareTemplate($value),
        );
    }

    protected function prepareTemplate($template)
    {
        if(null !== $template){
            return $template;
        }

        return 'default';
    }

    /**
     * Get page url
     */
    public function getLinkAttribute()
    {
        $prefix = $this->prefixRouteWithLocale();
        $link = DIRECTORY_SEPARATOR;
        $link .= $prefix ? ($this->getCurrentLocale() . DIRECTORY_SEPARATOR) : '';
        $link .= $this->slug;
        return $link;
    }
}
