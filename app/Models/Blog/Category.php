<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use App\Models\UnicodeModel;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends UnicodeModel
{
    use HasFactory;
    use HasTranslations;

    public $translatable = [
        'name',
        'slug',
        'description', 
        'seo_title',
        'seo_description'
    ];

    /**
     * @var string
     */
    protected $table = 'blog_categories';

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'is_visible' => 'boolean',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'blog_category_id');
    }

    /**
     * Get category url
     */
    public function getLinkAttribute()
    {
        $prefix = $this->prefixRouteWithLocale();
        $category = $this->slug;
        $link = DIRECTORY_SEPARATOR;
        $link .= $prefix ? ($this->getCurrentLocale() . DIRECTORY_SEPARATOR) : '';
        $link .= 'blog' . DIRECTORY_SEPARATOR;
        $link .= $category . DIRECTORY_SEPARATOR;
        return $link;
    }
}
