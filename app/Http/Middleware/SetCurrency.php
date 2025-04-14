<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
//use Illuminate\Support\Facades\Route;
//use Illuminate\Support\Facades\Redirect;
use App\Models\Shop\Currency;

class SetCurrency
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        $session = $request->session();
            
        if(!$session->has('currency')){
            if(Cookie::has('currency')) {
                // Check cookies for currency
                $currency = Currency::where('iso_alpha', Cookie::get('currency'))->first();
            } else {
                // Default currency
                $currency = Currency::where('is_default', true)->first();
            }

            // Add currency to session
            $request->session()->put('currency', [
                'name' => $currency->name,
                'iso_alpha' => $currency->iso_alpha,
                'iso_numeric' => $currency->iso_numeric,
                'sign' => $currency->sign,
                'exchange_rate' => $currency->exchange_rate,
                'default' => $currency->is_default
            ]);
            // Renew cookie lifetime
            Cookie::queue('currency', $currency->iso_alpha, 30 * 24 * 60); 
        } 

        return $next($request);
    }
}
