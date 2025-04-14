<?php

namespace App\Models\Page;

use App\Models\UnicodeModel;
use Spatie\Tags\HasTags;
use Spatie\Translatable\HasTranslations;
use Illuminate\Support\Str;

class Project extends UnicodeModel
{
    use HasTags;
    use HasTranslations;

    public $translatable = [
        'title',
        'slug', 
        'content',
        'seo_title',
        'seo_description'
    ];

    /**
     * Get project url
     */
    public function getLinkAttribute()
    {
        $page = Page::where('template', 'portfolio')->first();

        $prefix = $this->prefixRouteWithLocale();
        $link = DIRECTORY_SEPARATOR;
        $link .= $prefix ? ($this->getCurrentLocale() . DIRECTORY_SEPARATOR) : '';
        $link .= $page->slug . DIRECTORY_SEPARATOR;
        $link .= $this->slug;
        return $link;
    }

    /**
     * Make excerpt by limiting content length
     */
    public function getExcerptAttribute()
    {
        return $this->makeExcerpt($this->content);
    }

    protected function makeExcerpt($text, $length = 150, $end = '...') {
        // Remove any HTML tags and convert entities to their applicable characters
        $text = strip_tags($text);
    
        // Trim the text to the desired length and append the specified ending
        $excerpt = Str::limit($text, $length, $end);
    
        return "$excerpt";
    }
}
