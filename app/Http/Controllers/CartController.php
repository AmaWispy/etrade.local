<?php

namespace App\Http\Controllers;

use Livewire\Livewire;
use App\Models\Shop\Cart;
use Illuminate\View\View;
use Illuminate\Support\Str;
use App\Models\Shop\Product;
use Illuminate\Http\Request;
use App\Models\Shop\CartItem;
use App\Models\Shop\Category;
use App\Models\Shop\Order;
use App\Models\OrderCustom;
use Illuminate\Support\Facades\Log;
use App\Models\Shop\ProductVariation;
use App\Models\viewdItems;
use Illuminate\Support\Facades\Cookie;

class CartController extends Controller
{
    public function card(Request $request){
        $cart = $request->input();
        session($cart);

        return redirect()->route('checkout.index');
    }

    public function show(){
        try {
            $cart = $this->getCart();
            if($cart !== null){
                $productsCart = CartItem::where('shop_cart_id', $cart['id'])->get();
                $products = null;
    
                foreach ($productsCart as $product) {
                    if($product->product->type === \App\Models\Shop\Product::VARIABLE){
                        $image = $product->variation->getImage();
                        $name = $product->variation->name;
                    } else {
                        $image = $product->product->getImage();
                        $name = $product->product->name;
                    }
    
                    $products[] = [
                        'id' => $product->id,
                        'product_id' => $product->product->id,
                        'image' => $image ?? null,
                        'name' => $name ?? null ,
                        'price' => $product->getUnitSubtotal(),
                        'qty' => $product->qty ?? null ,
                        'link'=> route('shop.card', ['slug' => $product->product->slug, 'id' => $product->product->id]),
                    ];
                }
                return [
                    'status' => 200,
                    'products_info' => $products ?? null,
                    'products_Cart' => $productsCart ?? null,
                ];
            }
            return [
                'status' => 500,
            ];

        } catch (\Exception $message) {
            return [
                'status' => 500,
            ];
        }
    }

    public function view(): View
    {
        $cart = $this->getCart();
        $oldDataCard = null;
        $category = Category::whereRaw('JSON_SEARCH(slug, "all", ?) IS NOT NULL', ['suplimente'])->first();

        if($category !== null){
            $productsCategory = Product::query()
                ->whereHas('categories', function ($query) use ($category) {
                    $query->where('id', $category->id);
                })->get();
        }

        // if(session()->has('cart')){
        //     $cartData = session()->get('cart');
        //     $cart = Cart::where('code', $cartData['code'])->where('user_id', auth()->user()->id)->first();
        // }

        if(session()->has('card') && session()->get('card.note') !== null && session()->get('card.checkbox') === 'on'){
            $oldDataCard = session()->get('card');
        }
        // dd(session()->all());
        return view('shop.cart',
            compact(
                'cart',
                'productsCategory',
                'oldDataCard',
            )
        );
    }

    public function add(Request $request)
    {
        $post = $request->post();

        if(!isset($post['cartItem']['product']) || !isset($post['cartItem']['quantity'])){
            return [
                'status' => 400,
                'message' => trans('template.required_data_missing')
            ];
        }

        $product = Product::find($post['cartItem']['product']);
        if(!$product){
            return [
                'status' => 400,
                'message' => trans('template.non_existing_product')
            ];
        }
        $variable = $product->type === Product::VARIABLE;
        if($variable){
            $variation = ProductVariation::find($post['cartItem']['variation']);
            if(!$variation){
                return [
                    'status' => 400,
                    'message' => trans('template.non_existing_variation')
                ];
            }
        }

        /**
         * If there is no cart code in session - create new cart
         * GetCart middleware will check cookies and will add cart to session in case it exists
         */
        if(!session()->has('cart')){
            $cart = $this->newCart();
        } else {
            $cartData = session()->get('cart');
            $cart = Cart::where('code', $cartData['code'])->first();
            /**
             * In case the cart was not found by code from session
             * create a new one
             */
            if(!$cart){
                $cart = $this->newCart();
            }
        }
        
        $item = CartItem::where([
            'shop_cart_id' => $cart->id,
            'shop_product_id' => $product->id,
            // 'changed_composition' => $post['composition'] !== null ? json_encode($post['composition']['total_product']) : null,
            'shop_product_variation_id' => $variable ? $post['cartItem']['variation'] : null,
        ])->first();

        if(!$item){
            // if( $variable || $post['composition'] !== null ){
            //     if( $variable && $post['composition'] !== null ){
            //         $price = $variation->getPrice() + (float)$post['composition']['total_mdl'];
            //     } elseif( $variable ){
            //         $price = $variation->getPrice();
            //     } elseif( $post['composition'] !== null ){
            //         $price = (float)$post['composition']['total_mdl'];
            //     }
            // } else {
            //     $price =  $product->getPrice();
            // }

            $price =  $product->price;

            //dd($price);

            $item = CartItem::create([
                'shop_cart_id' => $cart->id,
                'shop_product_id' => $product->id,
                // 'shop_product_variation_id' => $variable ? $post['cartItem']['variation'] : null,
                'shop_product_variation_id' => null,
                // 'changed_composition' => $post['composition'] !== null ? $post['composition']['total_product'] : null,
                'qty' => $post['cartItem']['quantity'],
                'unit_price' => $price
            ]);
            //dd($item);
            
        } else {
            // Just add new amount to existing quantity
            $item->update([
                'qty' => $item->qty + $post['cartItem']['quantity']
            ]);
        }

        $cartData = $this->recalcCart($cart);
        // $html = view('includes.layout.cart.products', ['cart'=> $cart])->render();
            return [
                'status' => 200,
                'data' => $product->id,
                'message' => trans('template.successfully_added'),
                'cart' => $cartData,
                // 'html' => $html,
            ];
        } 

    public function update(Request $request)
    {
        $post = $request->post();

        if(!isset($post['item']) || !isset($post['quantity'])){
            return [
                'status' => 400,
                'message' => trans('template.required_data_missing')
            ];
        }

        $item = CartItem::find($post['item']);
        if(!$item){
            return [
                'status' => 400,
                'message' => trans('template.non_existing_item')
            ];
        }

        $item->update([
            'qty' => $post['quantity']
        ]);

        $cartData = $this->recalcCart($item->cart);

        $itemData = [
            'id' => $item->id,
            'subtotal' => $item->getUnitSubtotal()
        ];

        return [
            'status' => 200,
            'message' => trans('template.successfully_updated'),
            'cart' => $cartData,
            'item' => $itemData
        ];
    }

    public function remove(Request $request)
    {
        $post = $request->post();

        if(!isset($post['item'])){
            return [
                'status' => 400,
                'message' => trans('template.required_data_missing')
            ];
        }

        $item = CartItem::find($post['item']);
        if(!$item){
            return [
                'status' => 400,
                'message' => trans('template.non_existing_item')
            ];
        }
        $cart = $item->cart;

        $itemData = [
            'id' => $item->id
        ];

        $item->delete();

        $cartData = $this->recalcCart($cart);

        return [
            'status' => 200,
            'message' => trans('template.successfully_deleted'),
            'cart' => $cartData,
            'item' => $itemData,
            'empty' => $cartData['totalItems'] === 0 ? view('includes.empty-cart')->render() : ''
        ];
    }

    protected function newCart()
    {
        $missingCarts = $this->getCart();

        if(isset($missingCarts)){
            $cart = $missingCarts;
        } else {
            // TODO: Verify if code isn`t allready exists
            $code = Str::random(32);
    
            $cart = Cart::create([
                'code' => $code,
                'user_id' => \Auth::guard('client')->user()->id,//auth()->user()->id,
            ]);

        }

        if($cart){
            Cookie::queue('cart', $cart->code, 30 * 24 * 60); 
            session()->put('cart', [
                'code' => $cart->code,
                'totalItems' => $cart->total_items,
                'totalPrice' => $cart->total_price,
                'currency' => session('currency')['iso_alpha'] ?? null,
            ]);
        }

        return $cart;
    }

    /**
     * Update cart data in session with refreshed data
     */
    protected function recalcCart($cart)
    {
        $cart = $cart->refresh();

        $totalPrice = 0;
        $totalItems = 0;

        foreach($cart->items as $item){
            $totalPrice += $item->unit_price * $item->qty;
            $totalItems += $item->qty;
        }

        $cart->update([
            'total_price' => round($totalPrice, 2),
            'total_items' => $totalItems
        ]);

        $cartData = [
            'code' => $cart->code,
            'totalItems' => $cart->total_items,
            'totalPrice' => $cart->getTotal(),
        ];
        session()->put('cart', $cartData);

        return $cartData;
    }

    private function getCart():?Cart{
        $carts = Cart::where('user_id', \Auth::guard('client')->user()->id)->get()->pluck('id')->toArray();
        
        $orders = OrderCustom::whereIn('cart_id', $carts)->get()->pluck('cart_id')->toArray();

        $missingCartIds = array_diff($carts, $orders);
        $cart = Cart::whereIn('id', $missingCartIds)->first();
        
        return $cart;
    }

}