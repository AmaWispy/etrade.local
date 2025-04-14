<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use App\Models\UnicodeModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Lang;

class Category extends UnicodeModel
{
    use HasFactory;
    use HasTranslations;

    /**
     * @var string
     */
    protected $table = 'shop_categories';

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'is_visible' => 'boolean',
    ];

    public $translatable = [
        'name',
        'slug', 
        'description',
        'seo_title',
        'seo_description'
    ];

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function managedAttrtibute(){
        return $this->belongsToMany(Attribute::class, 'category_attributes','shop_category_id', 'shop_attribute_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'shop_category_product', 'shop_category_id', 'shop_product_id');
    }

    public function discounts(): MorphToMany
    {
        $today = now()->toDateString(); // Get the current date in 'Y-m-d' format
        $now = now()->toTimeString(); // Get the current time in 'H:i:s' format
        return $this->morphToMany(Discount::class, 'discountable')
                    ->where([
                        ['start_date', '<=', $today], // Including start date
                        ['end_date', '>', $today] // Excluding end date
                    ])
                    ->where(function ($query) use ($now) {
                        /**
                         * Verify time in subquery
                         */
                        $query->where(function ($q) use ($now) {
                            $q->whereTime('start_time', '<=', $now)
                              ->whereTime('end_time', '>', $now);
                        });
                    })
                    ->orderBy('created_at', 'desc'); // Get latest first
    }

    public function getImage()
    {
        if(null !== $this->image){
            return url('storage/'.$this->image);
        }
        return url('storage/no-image.png');
    }

    /**
     * Get category url
     */
    public function getLinkAttribute()
    {
        $prefix = $this->prefixRouteWithLocale();
        $link = "/";
        $link .= $prefix ? ($this->getCurrentLocale() . "/") : '';
        $link .= trans('routes.shop') . "/";

        /**
         * Include parent categories in link
         */
        $parents = self::getParents($this);
        foreach($parents as $parent){
            $link .= $parent->slug . "/";
        }

        $link .= $this->slug;

        return $link;
    }

    /**
     * Prepare localized routes for language switcher and hreflang
     */
    public static function getRouteTranslations(Category $category)
    {   
        $routes = [];

        foreach(config('app.locales') as $locale){
            if($locale === config('app.default_locale')){
                // Do not add locale prefix for default language
                $routes[$locale] = Lang::get('routes.shop', [], $locale);
            } else {
                $routes[$locale] = $locale . "/" . Lang::get('routes.shop', [], $locale);
            }
        }

        $parents = self::getParents($category);
        foreach($parents as $parent){
            $slugs = $parent->getTranslations('slug'); 
            foreach($slugs as $locale => $slug){
                $routes[$locale] .= "/" . $slug;
            }
        }

        $slugs = $category->getTranslations('slug'); 
        foreach($slugs as $locale => $slug){
            $routes[$locale] .= "/" . $slug;
        }

        return $routes;
    }

    /**
     * Get categories tree
     * For building menus/megamenus
     */
    public static function getTree($parent = null)
    {
        $categories = self::where('parent_id', $parent)->where('is_visible', true)->get();
        $tree = [];
        foreach($categories as $category){
            $tree[$category->id] = [
                'link' => $category->link,
                'name' => $category->name,
                'icon' => $category->getImage(),
                'children' => self::getTree($category->id)
            ];
        }

        return $tree;
    }

    public static function getParents(Category $category, $parents = [])
    {
        if(!$category->parent){
            return $parents;
        }

        $parents[] = $category->parent;
        self::getParents($category->parent, $parents);
        
        return $parents;
    }

    /**
     * Build options preserving tree structure
     * For select in admin section
     */
    public static function buildOptions($parent = null, $indentation = 0)
    {
        $categories = self::where('parent_id', $parent)->get();
        $options = [];
        foreach($categories as $category){
            $indent = str_repeat("&nbsp;&nbsp;", $indentation);
            $indent .= $indentation > 0 ? "↳&nbsp;" : '';
            $options[$category->id] = $indent . $category->name;
            // Recursively call the function for child categories
            $childOptions = self::buildOptions($category->id, $indentation + 1);
            $options = $options + $childOptions;
        }

        return $options;
    }

    /**
     * Get only top level categories with no children
     */
    public static function getTopLvlCats()
    {
        return self::whereNull('parent_id')->get();
    }
}
