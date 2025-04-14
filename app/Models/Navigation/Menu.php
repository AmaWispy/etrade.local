<?php

namespace App\Models\Navigation;

//use Illuminate\Database\Eloquent\Model;
use App\Models\UnicodeModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;
use Illuminate\Support\Facades\App;

class Menu extends UnicodeModel
{
    use HasTranslations;

    /**
     * @var string
     */
    protected $table = 'nav_menus';

    public $translatable = [
        'name'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'menu_id');
    }

    /**
     * Get menu by key
     */
    public static function getByKey($key)
    {
        return Menu::where('key', $key)->first();
    }

    /**
     * Get menu links
     */
    public function getLinks(){
        // TODO: Store nav in cache and use from cache first
        $links = [];
        foreach($this->items as $item){
            /**
             * Use static link
             */
            if(null !== $item->link){
                $links[] = [
                    'label' => $item->label,
                    'link' => $item->link,
                    'children' => [] // TODO: Check if there are some child links
                ];
            }

            /**
             * Make a new class
             * And find entity by id
             */
            if(null !== $item->entity){
                $class = $item->entity;
                $model = new $class();
                $entity = $model->find($item->entity_id);
                $links[] = [
                    'label' => $item->label,
                    'link' => $entity->link,
                    'children' => [] // TODO: Check if there are some child links
                ];
            }
        }
        return $links;
    }

    public static function getHomePageLink()
    {
        $prefix = !App::isLocale(config('app.default_locale'));
        return $prefix ? DIRECTORY_SEPARATOR . App::currentLocale() : DIRECTORY_SEPARATOR;
    }
}
