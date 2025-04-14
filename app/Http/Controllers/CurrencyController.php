<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use App\Models\Shop\Currency;

class CurrencyController extends Controller
{
    /**
     * Update selected currency.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $currency = $request->input('currency');
        // dd($currency);

        $currencies = Currency::where('is_active', true)->get();
        $available = $currencies->pluck(null, 'iso_alpha')->keyBy('iso_alpha');

        // Validate that the selected locale is supported in application
        if (isset($available[$currency])) {
            // Store currency in session
            $request->session()->put('currency', [
                'name' => $available[$currency]->name,
                'iso_alpha' => $available[$currency]->iso_alpha,
                'iso_numeric' => $available[$currency]->iso_numeric,
                'sign' => $available[$currency]->sign,
                'exchange_rate' => $available[$currency]->exchange_rate,
                'default' => $available[$currency]->is_default
            ]);
            // Store currency in cookies for 30 days
            Cookie::queue('locale', $currency, 30 * 24 * 60); 
        }

        // Rredirect back
        return redirect()->back();
    }
}
