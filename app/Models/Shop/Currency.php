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
        return round($exchangeRate, 6);
    }

    public static function exchange($amount, $curr = 'mdl')
    {
        if ($curr == 'usd') {
            $usdCurrency = self::where('iso_alpha', 'USD')->first();
            if ($usdCurrency) {
                $amount = $amount * $usdCurrency->rate;
            }
        }
        
        $currency = session('currency');
        /* \Illuminate\Support\Facades\Log::info('Currency debug', [
            'currency' => $currency,
            'type' => gettype($currency),
            'is_array' => is_array($currency),
            'amount' => $amount
        ]); */
        if ($currency['iso_alpha'] == 'MDL') {
            return $amount;
        }
        if(is_array($currency) && isset($currency['exchange_rate'])) {
            $amount = $amount * $currency['exchange_rate'];
        }
        return $amount;
    }

    public static function exchangeUSD($amount)
    {
        $usdCurrency = self::where('iso_alpha', 'USD')->first();
        if ($usdCurrency) {
            $amount = $amount * $usdCurrency->rate;
        }

        return $amount;
    }

    public static function format($amount)
    {
        $currency = session('currency');

        $amount = round($amount * 100) / 100;

        return number_format($amount, 2)." ".$currency['sign'];
    }

    public static function formatCustom($amount, $currency)
    {
        // If currency is a JSON string, decode it
        if (is_string($currency)) {
            $currency = json_decode($currency, true);
        }

        if (!is_array($currency) || !isset($currency['exchange_rate']) || !isset($currency['sign'])) {
            return number_format($amount, 2);
        }

        //dd($amount);

        // Убираем промежуточное округление
        $amount = $amount * $currency['exchange_rate'];

        return number_format($amount, 2)." ".$currency['sign'];
    }

    public static function exchangeToMdl($amount){
        $mdl = Currency::where('is_default', 1)->first();
        $currency = session('currency');

        $amount = $amount /  $currency['exchange_rate'];

        return $amount;
    }
    
}
