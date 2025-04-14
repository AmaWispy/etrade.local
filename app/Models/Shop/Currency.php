<?php

namespace App\Models\Shop;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use App\Models\UnicodeModel;
use Spatie\Translatable\HasTranslations;

class Currency extends UnicodeModel
{
    //use HasFactory;
    use HasTranslations;

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public $translatable = [
        'name'
    ];

    public function getExchangeRateAttribute()
    {
        /**
         * Default currency rate should be allways 1
         * Exchange rate will be calculated by formula
         * default currency rate / currency rate
         */

        $default = self::where('is_default', true)->first();
        $exchangeRate = $default->rate / $this->rate;
        return round($exchangeRate, 4);
    }

    public static function exchange($amount)
    {   
        $currency = session('currency');
        if(!$currency['default'] ){
            $amount = $amount *  $currency['exchange_rate'];
            //$amount = ceil($amount * 100) / 100;
        }
        
        return $amount;
    }

    public static function format($amount)
    {
        $currency = session('currency');

        $amount = round($amount * 100) / 100;

        return number_format($amount, 2)." ".$currency['sign'];
    }

    public static function exchangeToMdl($amount){
        $mdl = Currency::where('is_default', 1)->first();
        $currency = session('currency');

        $amount = $amount /  $currency['exchange_rate'];

        return $amount;
    }
    
}
