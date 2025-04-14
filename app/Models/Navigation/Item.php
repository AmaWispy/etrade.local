<?php

namespace App\Models\Navigation;

//use Illuminate\Database\Eloquent\Model;
use App\Models\UnicodeModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Item extends UnicodeModel
{
    use HasTranslations;

    /**
     * @var string
     */
    protected $table = 'nav_items';

    public $translatable = [
        'label'
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
