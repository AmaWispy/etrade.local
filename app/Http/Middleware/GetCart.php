<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cookie;
use App\Models\Shop\Cart;
use App\Models\Shop\Order;

class GetCart
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Cookie::has('cart') && auth()->check()) {
            $cart = Cookie::get('cart');
            $cart = Cart::where('code', $cart)->where('user_id', \Auth::guard('client')->user()->id)->first();
            /**
             * In case the order is processed, 
             * remove cart from cookies and session
             */
            if( $cart === null || (null !== $cart->order && $cart->orderCustom->status === Order::PROCESSING) ){
                Cookie::forget('cart');
                session()->forget('shipping');
                session()->forget('cart');

                return $next($request);
            }

            /**
             * If the order is not processed,
             * restore it in session
             */
            if(!session()->has('cart')){
                session()->put('cart', [
                    'code' => $cart->code,
                    'totalItems' => $cart->total_items,
                    'totalPrice' => $cart->total_price
                ]);
            }
        }

        return $next($request);
    }
}
