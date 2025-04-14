<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SessionReset
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Список допустимых маршрутов
        $allowedRoutesAccount = ['account.index']; // примеры

        if (!in_array($request->route()->getName(), $allowedRoutesAccount) && session()->has('AccountDetailsPage')) {
            session()->remove('AccountDetailsPage'); // Очистка всей сессии
        }

        return $next($request);
    }
}
