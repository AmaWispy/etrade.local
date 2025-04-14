<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;

class LocaleController extends Controller
{
    /**
     * Update the application's locale.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $locale = $request->input('locale');

        // Validate that the selected locale is supported in application
        if (in_array($locale, config('app.locales'))) {
            // Set the application's locale
            App::setLocale($locale);
            // Store locale in session
            $request->session()->put('locale', $locale);
            // Store locale in cookies for 30 days
            Cookie::queue('locale', $locale, 30 * 24 * 60); 
        }

        if($request->session()->has('localized_routes')){
            $routes = $request->session()->get('localized_routes');
            // return redirect($routes[$locale]);
            return redirect()->back();
        } else {
            // Simply redirect back
            return redirect()->back();
        } 
    }
}
