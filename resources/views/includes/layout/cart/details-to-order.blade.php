@php
    use App\Models\Shop\Currency;
    $priceTime = session()->get('fixed_time.priceOriginal', 210);
    $formatedPriceTime = str_replace('.00','',Currency::format(Currency::exchange($priceTime)));
@endphp
<div class="bg-white rounded-lg lg:px-8 sm:px-3 pt-14 pb-5 xl:w-full 2xl:w-[500px] @if (request()->path() === 'checkout') h-auto @else h-[410px] @endif  sticky flex flex-col justify-between gap-7 top-20">
    <!-- Info from product Start-->
        <div class="flex flex-col justify-between h-full">
            <h1 class="text-xl font-semibold">{{ __('template.order_data') }}</h1>

            <!-- Total Prouct from Checkout Start-->
                @if ($cart->items !== null && request()->path() === 'checkout')
                    <div class="border-b py-3">
                        @foreach ($cart->items as $item )
                            @include('includes.products.item.checkout',[
                                'item'=> $item
                            ])
                        @endforeach
                    </div>
                @endif
            <!-- Total Prouct from Checkout Start-->
            <!-- Info From Cart Start-->
                <div class="my-4">
                    <ul class="text-black" x-cloak>
                        <li class="flex justify-between">
                            <div class="flex gap-1">
                                <span data-counter="cart-total-items">{{ session()->get('cart')['totalItems'] }}</span>
                                <span>{{ session()->get('cart')['totalItems'] === 1 ? __('template.product') : __('template.products')  }}</span>
                            </div>
                            <span class="cart-total-price">{{ $cart->getTotal() }}</span>
                        </li>
                        @if (request()->path() !== 'checkout')
                            <li class="flex justify-between" x-show='addCard'>
                                <span>{{ __('template.card') }}</span>
                                <span>{{ __('template.free') }}</span>
                            </li>
                        @elseif(request()->path() === 'checkout' && !empty(session()->has('card.note')))
                            <li class="flex justify-between">
                                <span>{{ __('template.card') }}</span>
                                <span>{{ __('template.free') }}</span>
                            </li>
                        @endif
                        <li class="justify-between @if(session()->get('checkout.details.fixed_time', null) !== null) flex @else hidden @endif" id="dTime">
                            <span>{{ __('template.fixed_delivery_time') }}</span>
                            <span>{{ $formatedPriceTime }}</span>
                        </li>
                        <li class="flex justify-between" >
                            <span>{{  __('template.delivery') }}</span>
                            @if (session()->has('shipping.price'))
                                <span class="shipping-cost">
                                    {{ session()->get('shipping.price', '--') }}
                                </span>
                            @else
                                <span class="shipping-cost">
                                    {{null !== $cart->order ? $cart->order->getShippingPrice() : '--'}} 
                                </span>
                            @endif
                        </li>
                    </ul>
                </div>
            <!-- Info From Cart End-->

            <!-- Total Cart Start-->
                <div class="border-t pt-3 text-lg">
                    <ul class="flex justify-between w-full text-black font-semibold">
                        <li>
                            <h1>{{ __('template.amount') }}</h1>
                        </li>
                        <li>
                            <h1 class="cart-total-price" id="order-total"> 
                                @if (session()->has('checkout.totalCost'))
                                {{ str_replace([',', '.00'], [' ', ''], Currency::format(session()->get('checkout.totalCost'))) }}
                                @else
                                    {{null !== $cart->order ? $cart->order->total : $cart->getTotal() }} 
                                @endif
                            </h1>
                        </li>
                    </ul>
                </div>
            <!-- Total Cart End-->
        </div>
    <!-- Info From Product End-->

    <!-- Btn Go To Checkout or Finish Start-->
        @if ( request()->path() !== 'checkout' )
            <button type="submit" class=" text-white w-full bg-black xl:hover:bg-florarColor text-center py-2.5 rounded-lg">{{ __('template.go_to_checkout') }}</button>
        @else
            <button class="text-white w-full bg-black xl:hover:bg-florarColor text-center py-2.5 rounded-lg" type="submit" data-action="place-order">
                {{__('template.place_order')}}
            </button>
        @endif
    <!-- Btn Go To Checkout End-->

    <!-- Payments icons Start-->
        <div class="flex justify-center">
            <div class="h-5">
                @include('includes.links.payments-accept')
            </div>
        </div>
    <!-- Payments icons Start-->
</div>