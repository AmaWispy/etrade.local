<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop\Currency;
use App\Models\Shop\PaymentMethod;
use App\Models\Shop\ShippingMethod;
use App\Models\Shop\ShippingDetails;
use App\Models\Shop\ShippingZone;
use App\Models\Shop\Cart;
use App\Models\Shop\Customer;
use App\Models\Country;
use App\Models\City;
use App\Models\Address;
use App\Models\Shop\Order;
use Illuminate\Support\Facades\Cookie;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; 
use App\Services\Order as OrderUtils;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    protected $utils;

    public function __construct(OrderUtils $utils)
    {
        $this->utils = $utils;
    }

    public function save(Request $request){
        $checkout = $request->all();
        session()->put('checkout', $checkout);

        return [
            'success' => true,
            'data' => $checkout,
        ];
    }

    public function index(): View
    {
        $cart = null;
        $oldDataCard = null;
        $oldDataCheckout = null;

        // dd(session()->all());

        if(session()->has('cart')){
            $cartData = session()->get('cart');
            $cart = Cart::where('code', $cartData['code'])->first();
        }

        if(session()->has('card') && session()->get('card.note') !== null && session()->get('card.checkbox') === 'on'){
            $oldDataCard = session()->get('card');
        }

        if(session()->has('checkout')){
            $oldDataCheckout = session()->get('checkout');
        }
        // dd($oldDataCheckout);

        $countries = Country::get();
        $cities = City::get();

        $curierDeliveryMethods = ShippingMethod::where('is_active', true)->where('code', 'curier')->first();
        $shippingMethods = ShippingMethod::where('is_active', true)->get();
        $pickupMethods = ShippingMethod::where('is_active', true)->where('code','pickup')->first();
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        
        return view('shop.checkout',
            compact(
                'cart',
                'countries',
                'cities',
                'curierDeliveryMethods',
                'pickupMethods',
                'paymentMethods',
                'oldDataCard',
                'oldDataCheckout',
                'shippingMethods',
            )
        );
    }

    public function detectShippingZone(Request $request)
    {
        $post = $request->post();

        $country = (isset($post['country']) && null !== $post['country']) ? $post['country'] : 'ANY';
        $locality = (isset($post['locality']) && null !== $post['locality']) ? $post['locality'] : 'ANY';
        $coordinates = isset($post['coordinates']) && null !== $post['coordinates'] ? $post['coordinates'] : [];
        $raion = isset($post['raion']) && null !== $post['raion'] ? $post['raion'] : [];

        if(!empty($coordinates)){
            $zones = ShippingZone::select('code', 'on_map', 'area')
                ->where('is_active', true)
                ->where('on_map', true)
                ->whereNotNull('area')
                ->get();

        } elseif (!empty($cityFull)) {
            $zones = ShippingZone::select('code', 'on_map', 'area')
            ->whereNotNull('area')
            ->where('is_active', true)
            // ->whereRaw('JSON_SEARCH(localities, "all", ?) IS NOT NULL', [$raion])
            ->where('code', $cityFull)
            ->get();
        } elseif(!empty($locality) && $locality !== 'ANY') {
            /*$zones = ShippingZone::select('code', 'on_map', 'area')
                                    ->where('is_active', true)
                                    ->where('country', $country)
                                    ->where(function ($query) use ($locality) {
                                        $query->where('locality', $locality)
                                            ->orWhere('locality', 'ANY');
                                    })
                                    ->get();*/
            
            $zones = ShippingZone::select('code', 'on_map', 'area')
                                    ->where('is_active', true)
                                    ->whereRaw('JSON_SEARCH(localities, "all", ?) IS NOT NULL', [$locality])
                                    ->get();
        } elseif(!empty($raion)){
            $zones = ShippingZone::select('code', 'on_map', 'area')
            ->where('is_active', true)
            ->where('code', $raion)
            // ->whereRaw('JSON_SEARCH(area, "all", ?) IS NOT NULL', [$raion])
            ->get();
        }
        
        if ($zones->isNotEmpty()) {
            foreach ($zones as $zone) {
                $zone->area = !empty($zone->area) ? json_decode($zone->area, true) : null;
            }
        }

        if($zones){
            return [
                'status' => 200,
                'zones' => $zones,
                'locality' => $locality,
                'raion' => $raion,
                'post' => $post
            ];
        }
        
        /**
         * Available shipping zones not found
         */
        return [
            'status' => 404
        ];
    }

    public function calculateFixedTime(Request $request){
        $post = $request->post();
        $currentTotal = str_replace(',', '', $post['currentTotal']);
        $priceFixedTime = 210;
        
        if(!empty($post['fixedTime'])){
            $orderTotal = $currentTotal + Currency::exchange($priceFixedTime);
            session()->put('fixed_time', [
                'price' => Currency::exchange($priceFixedTime),
                'priceOriginal' => $priceFixedTime
            ]);
        } else {
            $orderTotal = $currentTotal - Currency::exchange($priceFixedTime);
            session()->put('fixed_time', [
                'price' => null,
            ]);
        }

        return [
            'status' => 200,
            'orderTotal' => str_replace(['.00', ','], ['', ' '],Currency::format($orderTotal))
        ];
    }
    public function calculateShipping(Request $request)
    {
        $post = $request->post();

        $shippingMethod = ShippingMethod::where('code', $post['method'])->first();
        $zones = isset($post['zones']) ? $post['zones'] : [];
        $distance = isset($post['distance']) ? $post['distance'] : 0;

        $shipping = $this->checkConditions($shippingMethod, $zones, $distance);
        $shippingPrice = Currency::exchange($shipping['price']);

        /**
         * Store selected shipping in session
         */
        session()->put('shipping', [
            'available' => $shipping['available'],
            'id' => $shippingMethod->id,
            'name' => $shippingMethod->name,
            'price' => str_replace('.00', '',Currency::format($shippingPrice)),
            'priceOriginal' => str_replace('.00', '',$shippingPrice)
        ]);

        $fixedTimePrice = session()->get('fixed_time.priceOriginal', null);

        $cartData = session()->get('cart');
        if(session()->has('cart')){
            $cartData = session()->get('cart');
            $cart = Cart::where('code', $cartData['code'])->first();
        }
        $cartTotalPrice = Currency::exchange($cart['total_price']);

        if(session()->has('fixed_time') && $fixedTimePrice !== null){
            $orderTotal = $cartTotalPrice + $shippingPrice + Currency::exchange($fixedTimePrice);
        } else {
            $orderTotal = $cartTotalPrice + $shippingPrice;
        }

        return [
            'status' => 200,
            'shipping' => $shipping,
            'orderTotal' => str_replace(['.00', ','], ['', ' '],Currency::format($orderTotal))
        ];
    }
    public function calculateShippingDelivery(Request $request)
    {
        $post = $request->post();

        $shippingMethod = ShippingMethod::where('code', 'curier')->first();
        $zones = isset($post['zones']) ? $post['zones'] : [];
        $distance = isset($post['distance']) ? (float)$post['distance'] : 0;

        $shipping = $this->checkConditions($shippingMethod, $zones, $distance);
        $shippingPrice = Currency::exchange($shipping['price']);

        return [
            'status' => 200,
            'shipping' => $shipping,
            'distance' => $distance,
            'method' => $shippingMethod,
            'shippingPrice' => $shippingPrice,
        ];
    }

    protected function checkConditions($method, $zones, $distance = 0, $extraСhecks = false)
    {
        // TODO: Apply extra checks if necessary

        foreach($method->conditions as $condition){
            $price = $condition['price'];
            
            if(in_array($condition['zone'], $zones)){
                return [
                    'available' => true,
                    'price' => $price,
                    'price_formatted' => str_replace('.00','',Currency::format(Currency::exchange($price)))
                ];
            }

            // Add method if can be applied to any shipping zone
            if($condition['zone'] === 'ANY'){
                return [
                    'available' => true,
                    'price' => $price,
                    'price_formatted' => str_replace('.00','',Currency::format(Currency::exchange($price)))
                ];
            }
        }

        if($method->by_distance){
            $price = $distance * $method->per_km;
            return [
                'available' => true,
                'price' => $price,
                'price_formatted' => str_replace('.00','',Currency::format(Currency::exchange($price)))
                //'price' => round($price, -1) // -1 - round to the nearest 10
            ];
        }

        return [
            'available' => false,
            'price' => 0,
            'price_formatted' => Currency::format(0)
        ];
    }

    public function placeOrder(Request $request)
    {
        $personData = [
            'customer.name' => 'string|required|min:4|max:100',
            'customer.phone' => 'required',
            'customer.email' => 'email|required',
        ];
        $customerData = [
            'details.contact_person' => 'string|required|min:4|max:100',
            'details.phone' => 'required',
        ];
        $addressData = [
            'address.street' => 'string|required|min:4|max:100',
            'address.appartament_number' => 'numeric|required',
            'address.entrance' => 'numeric|required',
            'address.floor' => 'numeric|required',
        ];
        $localityData = [
            'address.locality' => 'string|required',
        ];

        
        $post = $request->post();
        session()->put('shipping_method', $post['shipping_method']);
        session()->put('whom_deliver', $post['whom_deliver'] ?? null);


        if($post['shipping_method'] === 'pickup') {
            $rules = array_merge($personData);
        } elseif ($post['shipping_method'] === 'curier'){
            if(isset($post['whom_deliver'])){
                if(isset($post['address']['dont_know_address'])){
                    $rules = array_merge($personData, $localityData);
                } else{
                    $rules = array_merge($personData, $localityData, $addressData);
                }
            } elseif(isset($post['address']['dont_know_address'])){
                $rules = array_merge($personData, $customerData, $localityData);
            } else{
                $rules = array_merge($personData, $customerData, $localityData, $addressData);
            }
        }
        $post = $request->validate($rules);

        if(!$post){
            return redirect()->back();
        } else{
            $post = $request->post();
        }

        if(isset($post['details']['fixed_time'])){
            $post['details']['fixed_time'] = 1;
        }

        if(isset($post['address']['dont_know_address'])){
            $post['address']['dont_know_address'] = 1;
        } 
        // TODO: check request data before processing
        if(session()->has('cart')){
            $cartData = session()->get('cart');
            $cart = Cart::where('code', $cartData['code'])->first();
            if($cart){
                if($post['shipping_method'] === 'pickup'){
                    $shipping = ShippingMethod::where('code', 'pickup')->first();
                } elseif(session()->has('shipping')){
                    $shipping = session()->get('shipping');
                }

                /**
                 * Prevent client duplication
                 */
                $customer = Customer::where('email', $post['customer']['email'])->first();
                if(null === $customer){
                    $customer = Customer::create($post['customer']);
                }

                /**
                 * Save shipping details
                 */
                if(null !== $cart->order){
                    $cart->order->shippingDetails->update($post['details']);
                } else {
                    $shippingDetails = ShippingDetails::create($post['details']);
                }
                
                /**
                 * Prevent address duplication
                 */
                if(isset($post['address'])){

                    $hash = $this->calcAddressHash($post['address']);
                    $address = Address::where('hash', $hash)->first();
                    if(null === $address){
                        $data = $post['address'];
                        $data['hash'] = $hash;
                        $address = Address::create($data);
                        $customer->addresses()->attach($address->id);
                    }
                }
                $paymentMethod = PaymentMethod::where('code', $post['payment_method'])->first();

                $fixedTime = isset($post['details']['fixed_time'])  ? session()->get('fixed_time.priceOriginal',0) : 0;
                if(null !== $cart->order){
                    /**
                     * Prevent order doubling for same cart
                     */
                    $order = $cart->order;
                } else {
                    $order = Order::create([
                        'shop_cart_id' => $cart->id,
                        'shop_shipping_method_id' => $shipping['id'],
                        'shop_customer_id' => $customer->id,
                        'shop_customer_address_id' => $address->id ?? null,
                        'subtotal' => $cart->total_price,
                        'shipping' => $shipping['priceOriginal'] ?? null,
                        'fixed_time' => $fixedTime ?? null,
                        'shipping_details_id' => $shippingDetails->id,
                        'total' => round($cart->total_price + ($shipping['priceOriginal'] ?? 0) + $fixedTime , 2),
                        'shop_payment_method_id' => $paymentMethod->id,
                        'notes' => session()->get('card.note', null) !== null ? session()->get('card.note') : null ,
                    ]);
                }

                $sessionDelete = ['checkout', 'card', 'shipping_method'];

                foreach($sessionDelete as  $delete){
                    session()->remove($delete);
                }

                return $this->callAction(Str::camel($paymentMethod->code), [
                    $request, 
                    $order, 
                    $paymentMethod
                ]);
            }
        }
    }

    protected function calcAddressHash($address)
    {
        $string = "";
        foreach($address as $part){
            $string .= trim($part);
        }

        return md5($string);
    }

    public function result(Request $request, $cartCode)
    {
        $cart = Cart::where('code', $cartCode)->first();
        return view('shop.checkout-result',
            compact(
                'cart'
            )
        );
    }

    /**
     * Cash on delivery payment method
     */
    public function cashOnDelivery(Request $request, $order, $paymentMethod){
        if($order->update(['status' => Order::PROCESSING])){
            $notification = $this->utils->buildTgNotification($order);
            $this->utils->sendTgNotification($notification); 
            /**
             * Change order status and clear cart at once after order placed
             * This logic is just for Cash On Delivery
             */
            $emptyCart = Cookie::forget('cart');
            session()->forget('shipping');
            session()->forget('cart');
            return redirect('/checkout/result/' . $order->cart->code)->cookie($emptyCart);
        }       
    }

    /**
     * Card on delivery payment method
     */
    public function cardOnDelivery(Request $request, $order, $paymentMethod){
        if($order->update(['status' => Order::PROCESSING])){
            $notification = $this->utils->buildTgNotification($order);
            $this->utils->sendTgNotification($notification); 
            /**
             * Change order status and clear cart at once after order placed
             * This logic is just for Cash On Delivery
             */
            $emptyCart = Cookie::forget('cart');
            session()->forget('shipping');
            session()->forget('cart');
            return redirect('/checkout/result/' . $order->cart->code)->cookie($emptyCart);
        }       
    }

    /**
     * Paynet payment method
     */
    public function paynet(Request $request, $order, $paymentMethod){
        /**
         * Prepare data to be transmitted to the gate
         */
        $data = [];
        $config = $paymentMethod->config;
        
        if(filter_var($config['test_mode'], FILTER_VALIDATE_BOOLEAN)){
            $gatewayUrl = 'https://test.paynet.md/Acquiring/SetEcom';
            /**
             * In test mode, add random prefix to the order id 
             * because it is possible that the order with the id allready was registered for test merchant
             */
            $data['ExternalID'] = rand(1000, 9999) . $order->id;
        } else {
            $gatewayUrl = 'https://paynet.md/Acquiring/SetEcom';
            $data['ExternalID'] = $order->id;
        }

        $data['Merchant'] = $config['merchant_code'];
        $data['Currency'] = 498;

        $data['Services[0][Name]'] = config('app.name');
        $data['Services[0][Description]'] = 'Payment for order ' . $order->id;
        $data['Services[0][Amount]'] = $order->total * 100;

        /**
         * Add order items to transmitted data
         */
        foreach($order->cart->items as $key => $item){
            $data['Services[0][Products]['.$key.'][Amount]'] = $item->unit_price * $item->qty * 100;
            $data['Services[0][Products]['.$key.'][Barcode]'] = $item->shop_product_id;
            $data['Services[0][Products]['.$key.'][Code]'] = $item->shop_product_id;
            $data['Services[0][Products]['.$key.'][Description]'] = $this->sanitizeString($item->product->name);
            $data['Services[0][Products]['.$key.'][GroupId]'] = $item->product->mainCategory->id;
            $data['Services[0][Products]['.$key.'][GroupName]'] = $this->sanitizeString($item->product->mainCategory->name);
            $data['Services[0][Products]['.$key.'][LineNo]'] = $key;
            $data['Services[0][Products]['.$key.'][Name]'] = $this->sanitizeString($item->product->name);
            $data['Services[0][Products]['.$key.'][Quantity]'] = $item->qty;
            $data['Services[0][Products]['.$key.'][UnitPrice]'] = $item->unit_price * 100;
            $data['Services[0][Products]['.$key.'][UnitProduct]'] = 'units';
        }

        /**
         * Add shipping to transmitted data in case shipping cost is greater than 0
         */
        if($order->shipping > 0){
            $key = $order->cart->total_items;
            $data['Services[0][Products]['.$key.'][Amount]'] = (float)$order->shipping * 100;
            $data['Services[0][Products]['.$key.'][Barcode]'] = $order->shippingMethod->id;
            $data['Services[0][Products]['.$key.'][Code]'] = $order->shippingMethod->id;
            $data['Services[0][Products]['.$key.'][Description]'] = $this->sanitizeString($order->shippingMethod->description);
            $data['Services[0][Products]['.$key.'][GroupId]'] = $order->shippingMethod->id;
            $data['Services[0][Products]['.$key.'][GroupName]'] = trans('template.shipping');
            $data['Services[0][Products]['.$key.'][LineNo]'] = $key;
            $data['Services[0][Products]['.$key.'][Name]'] = $this->sanitizeString($order->shippingMethod->name);
            $data['Services[0][Products]['.$key.'][Quantity]'] = 1;
            $data['Services[0][Products]['.$key.'][UnitPrice]'] = (float)$order->shipping * 100;
            $data['Services[0][Products]['.$key.'][UnitProduct]'] = 'units';
        }

        $data['Customer.Code'] = $order->customer->id;
        $data['Customer.NameFirst'] = $order->customer->fname;
        $data['Customer.NameLast'] = $order->customer->lname;
        $data['Customer.PhoneNumber'] = $order->customer->phone;
        $data['Customer.email'] = $order->customer->email;
        $data['Customer.Country'] = $order->address->country;
        $data['Customer.City'] = $order->address->locality;
        $data['Customer.Address'] = $order->address->address;
        
        $data['ExternalDate'] = Carbon::now()->timezone('Europe/Chisinau')->format('Y-m-d\TH:i:s');
        $data['ExpiryDate'] = Carbon::now()->timezone('Europe/Chisinau')->addHours(2)->format('Y-m-d\TH:i:s');
        $data['LinkUrlSuccess'] = config('app.url').'/checkout/result/'.$order->cart->code;
        $data['LinkUrlCancel'] = config('app.url').'/checkout';

        $data['Lang'] = 'en';
        $data['SignVersion'] = 'v05';
        $data['MoneyType'] = null;
        $items = $order->shipping > 0 ? $order->cart->total_items + 1 : $order->cart->total_items;
        $data['Signature'] = $this->signData($data, $items, $config['secret_key']);

        Log::channel('paynet')->info([
            'desc' => 'Prepared data',
            'post' => $data
        ]);

        /**
         * Update order status to PENDING
         */
        $order->update(['status' => Order::PENDING]);
        /**
         * Render redirect form
         */
        return view('shop.payments.redirect',
            [
                'url' => $gatewayUrl,
                'fields' => $data
            ]
        );
    }

    /**
     * Generate paynet signature
     */
    protected function signData($data, $items, $secret){
        $string = $data['Currency']
                .$data['Customer.Address']
                .$data['Customer.City']
                .$data['Customer.Code']
                .$data['Customer.Country']
                .$data['Customer.email']
                .$data['Customer.NameFirst']
                .$data['Customer.NameLast']
                .$data['Customer.PhoneNumber']
                .$data['ExpiryDate']
                .$data['ExternalID']
                .$data['Merchant']
                .$data['MoneyType']
                .$data['Services[0][Amount]']
                .$data['Services[0][Description]']
                .$data['Services[0][Name]'];

        $keys = [
            'Amount',
            'Barcode',
            'Code',
            'Description',
            'GroupId',
            'GroupName',
            'LineNo',
            'Name',
            'UnitPrice',
            'UnitProduct'
        ];

        for($i=0; $i < $items; $i++){
            foreach($keys as $key){
                $string .= $data['Services[0][Products]['.$i.']['.$key.']'];
            }
        }
                
        $string .= $secret; 

        return base64_encode(md5($string, true));
    }

    /**
     * Remove from string characters which can broke request
     */
    protected function sanitizeString($string) {
        // Remove HTML tags
        $string = strip_tags($string);
    
        // Convert special characters to HTML entities
        $string = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    
        return $string;
    }

    public function placeOrderCustom(Request $request){
        $cartCode = session('cart.code');
        if (!$cartCode) {
            return redirect('/');
        }

        $cart = \App\Models\Shop\Cart::where('code', $cartCode)->first();
        if (!$cart) {
            return redirect('/');
        }

        $order = new \App\Models\OrderCustom();
        $order->client_id = \Auth::guard('client')->check() ? \Auth::guard('client')->id() : null;
        $order->cart_id = $cart->id;
        $order->status = 'processing';
        $order->comments = null;
        $order->total = $cart->total_price;
        $order->save();

        $sessionDelete = ['checkout', 'card', 'shipping_method', 'cart'];

        foreach($sessionDelete as  $delete){
            session()->forget($delete);
        }

        $emptyCart = Cookie::forget('cart');

        return response()->view('shop.checkout-result-congrat',
            compact(
                'cart'
            )
        )->cookie($emptyCart);
    }
}