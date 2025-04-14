<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cookie;
use App\Models\Shop\Follow;

class GetFollow
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Cookie::has('follow')) {
            $followCode = Cookie::get('follow');
            $follow = Follow::where('code', $followCode)->first();

            if($follow === null){
                Cookie::forget('follow');
                session()->forget('follow');

                return $next($request);
            }

            if ($follow && !session()->has('follow')) {
                session()->put('follow', [
                    'code' => $follow->code,
                    'totalItems' => $follow->total_items,
                ]);
            }
        }
        return $next($request);
    }
}
