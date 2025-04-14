<x-app-layout>

    @section('title', __('template.checkout'))

    @php
        $time_ranges = [
            [
                'start' => '07:00',
                'end' => '10:00'
            ],
            [
                'start' => '10:00',
                'end' => '12:00'
            ],
            [
                'start' => '12:00',
                'end' => '15:00'
            ],
            [
                'start' => '15:00',
                'end' => '18:00'
            ],
            [
                'start' => '18:00',
                'end' => '21:00'
            ],
            [
                'start' => '21:00',
                'end' => '23:00'
            ]
        ];
    @endphp

    <style>
        #autocomplete-address{
            width: 100%;
            list-style: none;
            margin: 0;
            padding: 0;
            position: absolute;
            z-index: 1;
            background: #ffffff;
            border: 1px solid #e4e4e4;
            -webkit-box-shadow: 0 2px 28px 0 rgba(0, 0, 0, 0.06);
            box-shadow: 0 2px 28px 0 rgba(0, 0, 0, 0.06);
        }
        #autocomplete-address li{
            display: block;
            width: 100%;
            padding: 6px 12px;
        }
        #autocomplete-address li:hover{
            background: #f9f9f9;
            cursor: pointer;
        }
        .spinner-border{
            position: absolute;
            right: 5px;
            bottom: 12px;
        }
    </style>
    @if(null !== $cart && $cart->items->count() > 0)
        <form class="container flex justify-between gap-5 mt-10 xl:flex-row flex-col" x-data="{different_address:false}">
            <div class="w-full flex flex-col gap-4">
                <h1 class="lg:text-2xl text-xl font-semibold">{{ __('template.billing_details') }}</h1>
                <div class="flex flex-col gap-3">
                    <div class="flex xl:justify-between xl:gap-0 lg:flex-row flex-col gap-3">
                        <label for="first_name" class="relative w-full">
                            <span class="text-neutral-500 absolute bg-white -top-2.5 left-4 text-sm">{{ __('template.first_name') }} <span class="text-red-500">*</span></span>
                            <input type="text" id="first_name" name="first_name" required class="w-full border !border-neutral-300 rounded-md h-14" placeholder="Adam">
                        </label>
                        <label for="last_name" class="relative w-full">
                            <span class="text-neutral-500 absolute bg-white -top-2.5 left-4 text-sm">{{ __('template.last_name') }} <span class="text-red-500">*</span></span>
                            <input type="text" id="last_name" name="last_name" required class="w-full border !border-neutral-300 rounded-md h-14" placeholder="John">
                        </label>
                    </div>
                    <label for="company_name" class="relative w-full">
                        <span class="text-neutral-500 absolute bg-white -top-2.5 left-4 text-sm">{{ __('template.company_name') }}</span>
                        <input type="text" id="company_name" name="company_name" class="w-full border !border-neutral-300 rounded-md h-14">
                    </label>
                    <label for="country_region" class="relative w-full">
                        <span class="text-neutral-500 absolute bg-white -top-2.5 left-4 text-sm">{{ __('template.country_region') }} <span class="text-red-500">*</span></span>
                        <select required name="country_region" class="w-full border !border-neutral-300 rounded-md h-14">
                            <option value="">2</option>
                            <option value="">2</option>
                        </select>
                    </label>
                    <label for="street_address" class="relative w-full">
                        <span class="text-neutral-500 absolute bg-white -top-2.5 left-4 text-sm">{{ __('template.street_address') }} <span class="text-red-500">*</span></span>
                        <input type="text" id="street_address" name="house_number_street_name" required class="w-full border !border-neutral-300 rounded-md h-14" placeholder="{{ __('template.house_street') }}">
                    </label>
                    <label for="appartament_suite_unit" class="relative w-full">
                        <input type="text" id="appartament_suite_unit" name="appartament_suite_unit" required class="w-full border !border-neutral-300 rounded-md h-14" placeholder="{{ __('template.apartment') }}">
                    </label>
                    <label for="town_city" class="relative w-full">
                        <span class="text-neutral-500 absolute bg-white -top-2.5 left-4 text-sm">{{ __('template.town_city') }} <span class="text-red-500">*</span></span>
                        <input type="text" id="town_city" name="town_city" required class="w-full border !border-neutral-300 rounded-md h-14">
                    </label>
                    <label for="country" class="relative w-full">
                        <span class="text-neutral-500 absolute bg-white -top-2.5 left-4 text-sm">{{ __('template.country') }}</span>
                        <input type="text" id="country" name="country" class="w-full border !border-neutral-300 rounded-md h-14">
                    </label>
                    <label for="phone" class="relative w-full">
                        <span class="text-neutral-500 absolute bg-white -top-2.5 left-4 text-sm">{{ __('template.phone') }} <span class="text-red-500">*</span></span>
                        <input type="text" id="phone" name="phone" required class="w-full border !border-neutral-300 rounded-md h-14">
                    </label>
                    <label for="email" class="relative w-full">
                        <span class="text-neutral-500 absolute bg-white -top-2.5 left-4 text-sm">{{ __('template.email') }} <span class="text-red-500">*</span></span>
                        <input type="email" id="email" name="email" required class="w-full border !border-neutral-300 rounded-md h-14">
                    </label>
                </div>

                <!-- Different Address Start -->
                    <div class="flex flex-col gap-3">
                        <label for="different_address" class="flex justify-between items-center py-4 cursor-pointer">
                            <h1 class="lg:text-2xl text-xl font-semibold">{{ __('template.ship_to_different_address') }}</h1>
                            <input class="cursor-pointer" @click="different_address = !different_address" x-bind:checked='different_address' type="checkbox" name="different_address" id="different_address">
                        </label>
                        <div class="flex flex-col gap-3" x-show='different_address'>
                            <label for="different_country_region" class="relative w-full">
                                <span class="text-neutral-500 absolute bg-white -top-2.5 left-4 text-sm">{{ __('template.country_region') }} <span class="text-red-500">*</span></span>
                                <select required name="different_country_region" id="different_country_region" class="w-full border !border-neutral-300 rounded-md h-14">
                                    <option value="">2</option>
                                    <option value="">2</option>
                                </select>
                            </label>
                            <label for="different_street_address" class="relative w-full">
                                <span class="text-neutral-500 absolute bg-white -top-2.5 left-4 text-sm">{{ __('template.street_address') }} <span class="text-red-500">*</span></span>
                                <input type="text" id="different_street_address" name="different_house_number_street_name" required class="w-full border !border-neutral-300 rounded-md h-14" placeholder="{{ __('template.house_street') }}">
                            </label>
                            <label for="different_appartament_suite_unit" class="relative w-full">
                                <input type="text" id="different_appartament_suite_unit" name="different_appartament_suite_unit" required class="w-full border !border-neutral-300 rounded-md h-14" placeholder="{{ __('template.apartment') }}">
                            </label>
                            <label for="different_town_city" class="relative w-full">
                                <span class="text-neutral-500 absolute bg-white -top-2.5 left-4 text-sm">{{ __('template.town_city') }} <span class="text-red-500">*</span></span>
                                <input type="text" id="different_town_city" name="different_town_city" required class="w-full border !border-neutral-300 rounded-md h-14">
                            </label>
                            <label for="different_country" class="relative w-full">
                                <span class="text-neutral-500 absolute bg-white -top-2.5 left-4 text-sm">{{ __('template.country') }}</span>
                                <input type="text" id="different_country" name="different_country" class="w-full border !border-neutral-300 rounded-md h-14">
                            </label>
                            <label for="different_phone" class="relative w-full">
                                <span class="text-neutral-500 absolute bg-white -top-2.5 left-4 text-sm">{{ __('template.phone') }} <span class="text-red-500">*</span></span>
                                <input type="text" id="different_phone" name="different_phone" required class="w-full border !border-neutral-300 rounded-md h-14">
                            </label>
                        </div>
                    </div>
                <!-- Different Address End -->

                <!-- Notes Start -->
                    <label for="different_notes" class="relative w-full">
                        <span class="text-neutral-500 absolute bg-white -top-2.5 left-4 text-sm">{{ __('template.notes') }}</span>
                        <textarea type="text" id="different_notes" name="different_notes" class="w-full border !border-neutral-300 rounded-md h-60 resize-none" placeholder="{{ __('template.order_notes') }}"></textarea>
                    </label>
                <!-- Notes End -->
            </div>

            <!-- Your Order Start -->
                <div class="w-full">
                    <div class="bg-orange-50 lg:p-5 p-3 flex flex-col gap-3 rounded-xl">
                        <h1 class="text-xl font-medium">{{ __('template.your_order') }}</h1>
                        <div class="md:p-4 p-2 bg-white rounded-lg">
                            <!-- Names Product and Subtotal Start-->
                                <ul class="flex justify-between font-medium text-lg py-3 border-b">
                                    <li>
                                        <h1>{{ __('template.product') }}</h1>
                                    </li>
                                    <li>
                                        <h1>{{ __('template.subtotal') }}</h1>
                                    </li>
                                </ul>
                            <!-- Names Product and Subtotal End-->

                            <!-- Products Start -->
                                <div>
                                    @foreach ($cart->items as $item)
                                        <ul class="flex gap-2 py-3 border-b justify-between">
                                            <li class="flex">
                                                <h1 class="sm:w-36 md:w-60 lg:w-96 xl:w-72 2xl:w-96 truncate ">{{ $item->product['name']}}</h1>
                                                <span>{{ ' x' .  $item['qty'] }}</span>
                                            </li>
                                            <li>
                                                <h1>{{ $item->getUnitSubtotal() }}</h1>
                                            </li>
                                        </ul>

                                    @endforeach
                                </div>
                            <!-- Products End -->

                            <!-- Shipping Start -->
                                <div class="flex flex-col gap-2 py-3 border-b">
                                    <ul class="flex justify-between">
                                        <li>
                                            <h1>{{ __('template.shipping') }}</h1>
                                        </li>
                                        <li>
                                            <h1>$35</h1>
                                        </li>
                                    </ul>

                                    <div class="flex flex-col gap-2 text-neutral-500">
                                        <div class="flex items-center gap-2">
                                            <input id="default-radio-1" type="radio" value="" name="shipping" class="w-4 h-4 cursor-pointer">
                                            <label for="default-radio-1" class="text-sm cursor-pointer">{{ __('template.free_shipping') }}</label>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <input id="default-radio-2" type="radio" value="" name="shipping" class="w-4 h-4 cursor-pointer">
                                            <label for="default-radio-2" class="text-sm cursor-pointer">{{ __('template.local') }}: $8.20</label>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <input  id="default-radio-3" type="radio" value="" name="shipping" class="w-4 h-4 cursor-pointer">
                                            <label for="default-radio-3" class="text-sm cursor-pointer">{{ __('template.flat_rate') }}: $12.59</label>
                                        </div>
                                    </div>
                                </div>
                            <!-- Shipping End -->

                            <!-- Total Start-->
                                <ul class="flex justify-between py-3 text-xl font-bold">
                                    <li>
                                        <h1>{{ __('template.total') }}</h1>
                                    </li>
                                    <li>
                                        <h1>{{ null !== $cart->order ? $cart->order->total : $cart->getTotal() }}</h1>
                                    </li>
                                </ul>
                            <!-- Total End-->
                        </div>
                        
                        <!-- Payments Methods Start -->
                            <div>
                                @foreach($paymentMethods as $method)
                                    <div class="border-b py-3 flex flex-col gap-2">
                                        <label class="radio-container flex gap-3 items-center" for="{{$method->code}}">
                                            <input  type="radio" 
                                                    id="{{$method->code}}" 
                                                    @if (isset($oldDataCheckout) && !empty($oldDataCheckout) && $oldDataCheckout['payment_method'] === $method->code) checked @endif
                                                    name="payment_method" 
                                                    value="{{$method->code}}" 
                                                    class="checkout-data cursor-pointer"
                                                    {{null !== $cart->order && $cart->order->paymentMethod->code === $method->code ? 'checked' : ($method->is_default ? 'checked' : '') }}
                                                >
                                            <p class="text-lg font-semibold cursor-pointer">{{$method->name}}</p>
                                        </label>
                                        <div class="!text-neutral-500 pl-8">
                                            {!! $method['description'] !!}
                                        </div>
                                    </div>
                                    @endforeach
                            </div>
                        <!-- Payments Methods End -->
                        <button class="rounded-lg text-lg font-semibold py-3 text-center bg-blue-500 text-white">{{ __('template.proceed_to_checkout') }}</button>
                    </div>
                </div>
            <!-- Your Order End -->
        </form>
        {{-- <div class="xl:container xl:mx-auto  !mt-7" x-data="{ fixedPrice: {{ session()->has('fixed_time.price') ? 'true' : 'false' }}}">
            <form class="flex gap-2 xl:flex-row sm:flex-col" action="{{route('checkout.place-order')}}" method="post">
                <!-- Forms Start-->
                    <div @if (isset($oldDataCheckout['shipping_method']) && $oldDataCheckout['shipping_method'] === 'curier' && !empty($oldDataCheckout) || session()->get('shipping_method', null) === 'curier') x-data='{pickup:false}' @else x-data='{pickup:true}' @endif class="2xl:w-[1000px] xl:w-[650px] sm:w-full flex flex-col gap-3">
                        <!-- Toggle Shipping Methods Switcher Start-->
                            <div class="bg-white flex md:gap-4 md:justify-normal sm:justify-around items-center rounded-lg md:p-4 sm:p-3 ">
                                <!-- Curier Method Start-->
                                    <label class="radio-container" for="{{$curierDeliveryMethods['code']}}">
                                        <input  type="radio" 
                                            class="checkout-data hidden"
                                            id="{{$curierDeliveryMethods['code']}}" 
                                            name="shipping_method" 
                                            {{ old('shipping_method') === 'curier' ? 'checked' : '' }}
                                            value="{{$curierDeliveryMethods['code']}}" 
                                            x-bind:checked='!pickup'
                                        >
                                        <button type="button" class="text-xl font-semibold" x-on:click='pickup = false'  x-bind:class="pickup ? 'right-1' : 'left-1 text-florarColor'">
                                            <span class="hidden lg:inline">{{ __('template.delivery_courier') }}</span>
                                            <span class="inline lg:hidden">{{ __('template.delivery') }}</span>
                                            </button>
                                    </label>
                                <!-- Curier Method End-->
                                
                                <!-- Switcher Start-->
                                    <button type="button" x-on:click='pickup = !pickup' class="border !border-neutral-300 relative w-14 h-[26px] rounded-2xl">
                                        <span x-bind:class="pickup ? 'right-1' : 'left-1'" class="duration-1000 !w-5 !h-5 absolute top-[50%] translate-y-[-50%] bottom-0 py-1 rounded-full bg-florarColor"></span>
                                    </button>
                                <!-- Switcher End-->

                                <!-- Pickup Method Start-->
                                    <label class="radio-container" for="{{$pickupMethods['code']}}">
                                        <input  type="radio" 
                                                class="checkout-data hidden"
                                                id="{{$pickupMethods['code']}}" 
                                                name="shipping_method" 
                                                value="{{$pickupMethods['code']}}" 
                                                x-bind:checked='pickup'
                                                {{ old('shipping_method') === 'pickup' ? 'checked' : '' }}
                                            >
                                            <button type="button" class="text-xl font-semibold" x-on:click='pickup = true' x-bind:class="pickup ? 'right-1 text-florarColor' : 'left-1'">{{$pickupMethods['name']}}</button>
                                    </label>
                                <!-- Pickup Method End-->
                            </div>
                        <!-- Toggle Shipping Methods Switcher End-->

                        <!-- Delivery Methods Start-->
                            <div x-show='!pickup' class='flex flex-col gap-4' x-cloak>
                                @include('includes.layout.checkout.delivery')
                            </div>
                        <!-- Delivery Methods Start-->

                        <!-- Pickup Methods Start-->
                            <div x-show='pickup' class='flex flex-col gap-4' x-cloak>
                                @include('includes.layout.checkout.pickup')
                            </div>
                        <!-- Pickup Methods Start-->
                        
                        <!-- Payments Methods Start-->
                            <div class="bg-white p-4 rounded-lg flex flex-col gap-3">
                                <h1 class='text-xl font-semibold'>{{ __("template.choose_payment_method") }}</h1>
                                <div class="flex flex-col gap-2">
                                    @foreach($paymentMethods as $method)
                                        <label class="radio-container" for="{{$method->code}}">
                                            <input  type="radio" 
                                                    id="{{$method->code}}" 
                                                    @if (isset($oldDataCheckout) && !empty($oldDataCheckout) && $oldDataCheckout['payment_method'] === $method->code) checked @endif
                                                    name="payment_method" 
                                                    value="{{$method->code}}" 
                                                    class="checkout-data"
                                                    {{null !== $cart->order && $cart->order->paymentMethod->code === $method->code ? 'checked' : ($method->is_default ? 'checked' : '') }}
                                                >
                                            {{$method->name}}
                                            <span class="checkmark"></span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        <!-- Payments Methods End-->
                    </div> --}}
                <!-- Forms Start-->

                <!-- Details to Order Start-->
                    {{-- <div x-data="{addCard: @if(isset($oldDataCard) && !empty($oldDataCheckout)) true @else false @endif}">
                        @include('includes.layout.cart.details-to-order')
                    </div> --}}
                <!-- Details to Order end-->
            </form>
        </div>
    @else
        @include('includes.empty-cart')
    @endif  

    @include('includes.scripts.checkout')
</x-app-layout>