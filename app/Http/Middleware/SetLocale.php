<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /**
         * If there is no locale passed in the route
         * add default locale to route params
         */
        $locale = $request->route('locale');
        
        if(null === $locale || !in_array($locale, config('app.locales'))){
            $session = $request->session();
            
            if($session->has('locale')){
                // Check session for locale first
                $locale = $session->get('locale');
            } else if(Cookie::has('locale')) {
                // Check cookies for locale
                $locale = Cookie::get('locale');
            } else {
                // Default locale from config
                $locale = config('app.default_locale');
            }
            
            // Access the current route instance
            $route = Route::current();
            // Add default locale to route params
            $route->setParameter('locale', $locale);
        }
        
        // Add locale in session
        $request->session()->put('locale', $locale);
        // Renew cookie lifetime
        Cookie::queue('locale', $locale, 30 * 24 * 60); 

        App::setLocale($locale);

        return $next($request);
    }
}
