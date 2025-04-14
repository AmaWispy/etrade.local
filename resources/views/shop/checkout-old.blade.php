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

    <!-- BREADCRUMB AREA START -->
    <div class="container lg:my-4 sm:my-2">
        <nav class='inline-flex items-center text-sm'>
            <ul class="inline-flex items-center gap-2 ">
                <li>
                    <h1 class="font-semibold">{{ __('template.you_are_here') }}</h1>
                </li>
                <li>
                    <a class="hover:text-black hover:font-medium" href="{{ \App\Models\Navigation\Menu::getHomePageLink() }}">{{ __('template.home') }}</a>
                </li>
                <li>
                    <h1 class="text-neutral-500">/</h1>
                </li>
                <li>
                    <a class="hover:text-black hover:font-medium" href="{{ route('shop.home') }}">{{__('template.shop')}}</a>
                </li>
                <li>
                    <h1 class="text-neutral-500">/</h1>
                </li>
                <li>
                    <a class="hover:text-black hover:font-medium" href="{{ route('cart.view') }}">{{__('template.cart')}}</a>
                </li>
                <li>
                    <h1 class="text-neutral-500">/</h1>
                </li>
                <li>
                    <h1 class="hover:text-black">{{__('template.checkout')}}</h1>
                </li>
            </ul>
        </nav>
    </div>
<!-- BREADCRUMB AREA END -->

@if(null !== $cart && $cart->items->count() > 0)
<div class="xl:container xl:mx-auto !mt-14">
    <!-- Back to Cart and title Start-->
        <div>
            <a href="{{ route('cart.view') }}" class="bg-white text-black hover:text-florarColor py-2.5 px-5 rounded-3xl"><i class="bi bi-arrow-left mt-1"></i>{{ __('template.return_to_cart') }}</a>
            <h1 class="text-2xl font-semibold my-4">{{ __("template.order_checkout") }}</h1>
        </div>
    <!-- Back to Cart and title Start-->

    <form class="flex gap-2 xl:flex-row sm:flex-col">
        <!-- Forms Start-->
            <div x-data='{pickup:false}' class="2xl:w-[1000px] xl:w-[700px] sm:w-full flex flex-col gap-3">
                <!-- Toggle Shipping Methods Switcher Start-->
                    <div class="bg-white flex gap-4 items-center rounded-lg p-4 ">
                        <!-- Curier Method Start-->
                            <label class="radio-container" for="{{$curierDeliveryMethods['code']}}">
                                <input  type="radio" 
                                        class="hidden"
                                        id="{{$curierDeliveryMethods['code']}}" 
                                        name="shipping_method" 
                                        value="{{$curierDeliveryMethods['code']}}" 
                                        x-bind:checked='!pickup'
                                    >
                                    <button type="button" class="text-xl font-semibold" x-on:click='pickup = false'  x-bind:class="pickup ? 'right-1' : 'left-1 text-florarColor'">{{$curierDeliveryMethods['name']}}</button>
                            </label>
                        <!-- Curier Method End-->
                        
                        <!-- Switcher Start-->
                            <button type="button" x-on:click='pickup = !pickup' class="border !border-neutral-300 relative w-14 h-[26px] rounded-2xl">
                                <span x-bind:class="pickup ? 'right-1' : 'left-1'" class="duration-1000 w-5 h-5 absolute top-[50%] translate-y-[-50%] bottom-0 py-1 rounded-full bg-florarColor"></span>
                            </button>
                        <!-- Switcher End-->

                        <!-- Pickup Method Start-->
                            <label class="radio-container" for="{{$pickupMethods['code']}}">
                                <input  type="radio" 
                                        class="hidden"
                                        id="{{$pickupMethods['code']}}" 
                                        name="shipping_method" 
                                        value="{{$pickupMethods['code']}}" 
                                        x-bind:checked='pickup'
                                    >
                                    <button type="button" class="text-xl font-semibold" x-on:click='pickup = true' x-bind:class="pickup ? 'right-1 text-florarColor' : 'left-1'">{{$pickupMethods['name']}}</button>
                            </label>
                        <!-- Pickup Method End-->
                    </div>
                <!-- Toggle Shipping Methods Switcher End-->
                
                <!-- Whom to Deliver Box Start-->
                    <div x-data="{whomDeliver:false}" class="bg-white rounded-lg p-4 flex flex-col gap-6">
                        <!-- Whom to Deliver checkBox Start-->
                            <div class="flex flex-col gap-3 border-b pb-3">
                                <h1 class="text-xl font-semibold">{{ __("template.delivery_to_whom") }}</h1>
                                <input type="checkbox" class="hidden" x-bind:checked='whomDeliver' name="details[myself]">
                                <ul class=" flex flex-col">
                                    <li x-on:click="whomDeliver = true" class="cursor-pointer mt-2 inline-flex gap-3">
                                        <i x-bind:class="whomDeliver ? 'bi-circle-fill text-florarColor' : 'bi-circle'" class="bi"></i>
                                        <span class="font-medium text-black">{{ __('template.i_will_receive_order') }}</span>
                                    </li>
                                    <li x-on:click="whomDeliver = false" class="cursor-pointer mt-2 inline-flex gap-3">
                                        <i x-bind:class="!whomDeliver ? 'bi-circle-fill text-florarColor' : 'bi-circle'" class="bi"></i>
                                        <span class="font-medium text-black">{{ __('template.other_person') }}</span>
                                    </li>
                                </ul>
                            </div>
                        <!-- Whom to Deliver checkBox End-->

                        <!-- My Contacts Data Start-->
                            <div>
                                <h1 class="font-semibold">{{ __('template.your_contact_details') }}</h1>
                                <div class="flex flex-col gap-2 mt-2">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    <input type="text" 
                                        class="w-full py-2 px-3 !m-0 !rounded-lg border " 
                                        placeholder="{{ __('template.name') }}"
                                        name="customer[fname]"
                                        value="{{null !== $cart->order ? $cart->order->customer->fname : ''}}" 
                                        required >
                                    <div class="flex gap-2">
                                        <input type="text" 
                                            class="w-full py-2 px-3 !m-0 !rounded-lg border " 
                                            placeholder="{{ __('template.phone') }}"
                                            name="customer[phone]" 
                                            value="{{null !== $cart->order ? $cart->order->customer->phone : ''}}" 
                                            required >
                                        <input type="email" 
                                            class="w-full py-2 px-3 !m-0 !rounded-lg border " 
                                            placeholder="{{ __('template.email') }}"
                                            name="customer[email]" 
                                            value="{{null !== $cart->order ? $cart->order->customer->email : ''}}" 
                                            required >
                                    </div>
                                </div>
                            </div>
                        <!-- My Contacts Data End-->
                        <!-- Contacts Recipient Start-->
                            <div x-show='!whomDeliver'>
                                <h1 class="font-semibold">{{ __('template.recipient_contact_details') }}</h1>
                                <div class="flex gap-2 mt-2">
                                    <input type="text" 
                                        class="w-full py-2 px-3 !m-0 !rounded-lg border " 
                                        placeholder="{{ __('template.name') }}"
                                        name="details[contact_person]" 
                                        value="{{null !== $cart->order && $cart->order->details ? $cart->order->details->contact_person : ''}}" 
                                        required>
                                    <input type="text" 
                                        class="w-full py-2 px-3 !m-0 !rounded-lg border " 
                                        placeholder="{{ __('template.phone') }}"
                                        name="details[phone]" 
                                        value="{{null !== $cart->order && $cart->order->details ? $cart->order->details->phone : ''}}" 
                                        required>
                                </div>
                            </div>
                        <!-- Contacts Recipient End-->
                    </div>
                <!-- Whom to Deliver Box End-->

                <!-- Delivery Address Start-->
                    <div class="bg-white p-4 flex-col flex gap-2 rounded-lg" x-data="{idka:false}"> <!-- idka-> i don't know address -->
                        <h1 class="text-xl font-semibold">{{ __('template.delivery_address') }}</h1>
                        <!-- Region, City and Region City Start-->
                            <div class="flex flex-col gap-2">
                                <div class="flex gap-2">
                                    <select name="" id="" class="nice-select !m-0 bg-neutral-100 !rounded-lg border w-full">
                                        <option value="NONE">{{ __("template.district") }}</option>
                                    </select>
                                    <div class="w-full">
                                        <select name="address[locality]" class="nice-select w-full bg-neutral-100 !m-0" required >
                                            <option value="NONE">{{__('template.city')}}</option>
                                            @foreach($cities as $city)
                                                <option 
                                                    value="{{$city->code}}"
                                                    {{null !== $cart->order && $cart->order->address->locality === $city->code ? 'selected' : ''}}
                                                >
                                                    {{$city->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <select name="" id="" class="nice-select !m-0 bg-neutral-100 !rounded-lg border w-full">
                                    <option value="NONE">{{ __("template.city_district") }}</option>
                                </select>
                                
                                <ul>
                                    <li class="inline-flex gap-2 cursor-pointer" x-on:click="idka = !idka">
                                        <i x-bind:class="idka ? 'bi-check-square-fill' : 'bi-square' " class="bi text-black"></i>
                                        <span>{{ __('template.unknown_address') }}</span>
                                    </li>
                                </ul>
                            </div>
                        <!-- Region, City and Region City End-->

                        <!-- Exact Address Start-->
                            <div class="flex flex-col gap-2 mt-3" x-sho='idka'>
                                <input type="text" class="w-full py-2 px-3 !m-0 !rounded-lg border" placeholder="{{ __('street') }}">
                                <div class="flex gap-2">
                                    <input type="text" class="w-full py-2 px-3 !m-0 !rounded-lg border" placeholder="{{ __('template.house_number') }}">
                                    <input type="text" class="w-full py-2 px-3 !m-0 !rounded-lg border" placeholder="{{ __('template.phone_number') }}">
                                </div>
                                <div class="flex gap-2">
                                    <input type="text" class="w-full py-2 px-3 !m-0 !rounded-lg border" placeholder="{{ __('template.entrance') }}">
                                    <input type="text" class="w-full py-2 px-3 !m-0 !rounded-lg border" placeholder="{{ __('template.floor') }}">
                                    <input type="text" class="w-full py-2 px-3 !m-0 !rounded-lg border" placeholder="{{ __('template.intercom') }}">
                                </div>
                                <textarea name="" 
                                    id="" 
                                    cols="30" 
                                    rows="10" 
                                    placeholder="{{ __('template.delivery_comment') }}" maxlength="500" 
                                    class="h-40 border rounded-lg bg-neutral-100 resize-none    "></textarea>
                            </div>
                        <!-- Exact Address End-->
                    </div>
                <!-- Delivery Address End-->
            </div>
        <!-- Forms Start-->

        <!-- Details to Order Start-->
            <div>
                @include('includes.layout.cart.details-to-order')
            </div>
        <!-- Details to Order end-->
    </form>
</div>
@else
@include('includes.empty-cart')
@endif

    <!-- CHECKOUT AREA START -->
    <div class="ltn__checkout-area mb-100">
        
                @if(null !== $cart && $cart->items->count() > 0)
                    <form action="{{route('checkout.place-order')}}" method="post">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="ltn__checkout-inner">
                                        <div class="ltn__checkout-single-content mt-50">
                                            <h4 class="title-2">{{__('template.shipping_details')}}</h4>
                                            <div class="ltn__checkout-single-content-info">
                                                
                                                    {{-- Do not remove input with csrf token --}}
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                                    <h6>{{__('template.personal_info')}}</h6>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="input-item input-item-name ltn__custom-icon">
                                                                <input  type="text" 
                                                                        placeholder="{{__('template.first_name')}}"
                                                                        name="customer[fname]" 
                                                                        class="form-control" 
                                                                        value="{{null !== $cart->order ? $cart->order->customer->fname : ''}}" 
                                                                        required 
                                                                    />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="input-item input-item-name ltn__custom-icon">
                                                                <input  type="text" 
                                                                        placeholder="{{__('template.last_name')}}"
                                                                        name="customer[lname]" 
                                                                        class="form-control" 
                                                                        value="{{null !== $cart->order ? $cart->order->customer->lname : ''}}" 
                                                                        required 
                                                                    />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="input-item input-item-phone ltn__custom-icon">
                                                                <input  type="text" 
                                                                        placeholder="{{__('template.phone')}}"
                                                                        name="customer[phone]" 
                                                                        class="form-control" 
                                                                        value="{{null !== $cart->order ? $cart->order->customer->phone : ''}}" 
                                                                        required 
                                                                    />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="input-item input-item-email ltn__custom-icon">
                                                                <input  type="email" 
                                                                        placeholder="{{__('template.email')}}"
                                                                        name="customer[email]" 
                                                                        class="form-control" 
                                                                        value="{{null !== $cart->order ? $cart->order->customer->email : ''}}" 
                                                                        required 
                                                                    />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p><label class="input-info-save mb-0"><input type="checkbox" name="details[myself]"> {{__('template.receive_order_myself')}}</label></p>
                                                    <div id="contact-person">
                                                        <h6>{{__('template.contact_person')}}</h6>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="input-item input-item-name ltn__custom-icon">
                                                                    <input  type="text" 
                                                                            placeholder="{{__('template.name')}}"
                                                                            name="details[contact_person]" 
                                                                            class="form-control" 
                                                                            value="{{null !== $cart->order ? $cart->order->details->contact_person : ''}}" 
                                                                            required 
                                                                        />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="input-item input-item-phone ltn__custom-icon">
                                                                    <input  type="text" 
                                                                            placeholder="{{__('template.phone')}}"
                                                                            name="details[phone]" 
                                                                            class="form-control" 
                                                                            value="{{null !== $cart->order ? $cart->order->details->phone : ''}}" 
                                                                            required 
                                                                        />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h6>{{__('template.shipping_date')}}</h6>
                                                            <div class="input-item input-item-date ltn__custom-icon">
                                                                <input  id="datepicker"
                                                                        type="text" 
                                                                        name="details[shipping_date]" 
                                                                        class="form-control" 
                                                                        value="{{null !== $cart->order ? $cart->order->details->shipping_date : date('Y-m-d')}}" 
                                                                        required 
                                                                    />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h6>{{__('template.shipping_time')}}</h6>
                                                            <div class="input-item">
                                                                <select name="details[shipping_range]" class="nice-select" required >
                                                                    <option value="{{__('template.any_time')}}">{{__('template.any_time')}}</option>
                                                                    @foreach($time_ranges as $range)
                                                                        @if(date('H:i') < $range['end'])
                                                                        <option 
                                                                            value="{{$range['start']}} - {{$range['end']}}"
                                                                            {{null !== $cart->order && $cart->order->details->shipping_range === ($range['start'] . '-' . $range['end']) ? 'selected' : ''}}
                                                                        >
                                                                            {{$range['start']}} - {{$range['end']}}
                                                                        </option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        {{-- Shipping limited just for MDA  --}}
                                                        <input type="hidden" name="address[country]" value="MDA" required />
                                                        
                                                            <div class="col-md-6">
                                                                <h6>{{__('template.country')}}</h6>
                                                                <div class="input-item">
                                                                    <select name="address[country]" class="nice-select" required >
                                                                        <option value="NONE">{{__('template.select')}}</option>
                                                                        @foreach($countries as $country)
                                                                            <option 
                                                                                value="{{$country->iso3}}"
                                                                                {{null !== $cart->order && $cart->order->address->country === $country->iso3 ? 'selected' : ''}}
                                                                            >
                                                                                {{$country->name}}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                       
                                                        <div class="col-md-12">
                                                            <h6>{{__('template.locality')}}</h6>
                                                            <div class="input-item">
                                                                <select name="address[locality]" class="nice-select" required >
                                                                    <option value="NONE">{{__('template.select')}}</option>
                                                                    @foreach($cities as $city)
                                                                        <option 
                                                                            value="{{$city->code}}"
                                                                            {{null !== $cart->order && $cart->order->address->locality === $city->code ? 'selected' : ''}}
                                                                        >
                                                                            {{$city->name}}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        {{-- Not necessary, because shipping is limited for MDA --}}
                                                        
                                                            <div class="col-md-6 d-none">
                                                                <h6>{{__('template.other_country')}}</h6>
                                                                <div class="input-item">
                                                                    <input  type="text" 
                                                                            name="address[other_country]" 
                                                                            class="form-control" 
                                                                            value="{{null !== $cart->order && !empty($cart->order->address->other_country) ? $cart->order->address->other_country : ''}}" 
                                                                        />
                                                                </div>
                                                            </div>
                                                        <div class="col-md-6 d-none">
                                                            <h6>{{__('template.other_locality')}}</h6>
                                                            <div class="input-item">
                                                                <input  type="text" 
                                                                        name="address[other_locality]" 
                                                                        {{-- Autocomplete disabled, uncomment line below to enable --}}
                                                                        data-action="autocomplete-address"
                                                                        class="form-control" 
                                                                        value="{{null !== $cart->order && !empty($cart->order->address->other_locality) ? $cart->order->address->other_locality : ''}}" 
                                                                    />
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-12">
                                                            <h6>{{__('template.address')}}</h6>
                                                            <div class="input-item">
                                                                <input  type="text" 
                                                                        name="address[address]" 
                                                                        {{-- Autocomplete disabled, uncomment line below to enable --}}
                                                                        data-action="autocomplete-address"
                                                                        class="form-control" 
                                                                        value="{{null !== $cart->order ? $cart->order->address->address : ''}}" 
                                                                        required 
                                                                    />
                                                            </div>
                                                        </div>

                                                        {{-- Disabled, because there is no necessity in post code for courier delivery --}}
                                                        
                                                        <div class="col-md-4">
                                                            <h6>{{__('template.post_code')}}</h6>
                                                            <div class="input-item">
                                                                <input  type="text" 
                                                                        name="address[post_code]" 
                                                                        class="form-control" 
                                                                        value="{{null !== $cart->order ? $cart->order->address->post_code : ''}}" 
                                                                        required 
                                                                    />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <h6>{{__('template.notes')}} (optional)</h6>
                                                    <div class="input-item input-item-textarea ltn__custom-icon">
                                                        <textarea 
                                                            name="order[notes]" 
                                                            rows="12"
                                                        >
                                                            {{null !== $cart->order ? $cart->order->notes : ''}}
                                                        </textarea>
                                                    </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mt-50 mb-50">
                                        <h4 class="title-2">{{__('template.order_details')}}</h4>
                                        <table class="table">
                                            <tbody>
                                                @foreach($cart->items as $item)
                                                    <tr>
                                                        <td>
                                                            @if($item->product->type === \App\Models\Shop\Product::VARIABLE)
                                                                {{$item->variation->name}}
                                                            @else
                                                                {{$item->product->name}}
                                                            @endif
                                                            <strong>× {{$item->qty}}</strong>
                                                        </td>
                                                        <td>{{$item->getUnitSubtotal()}}</td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td>{{__('template.shipping')}}</td>
                                                    <td id="shipping-cost">
                                                        {{null !== $cart->order ? $cart->order->getShippingPrice() : '--'}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{__('template.order_total')}}</strong></td>
                                                    <td>
                                                        <strong id="order-total">
                                                            {{null !== $cart->order ? $cart->order->total : $cart->getTotal()}}
                                                        </strong>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="ltn__checkout-payment-method mt-50">
                                        <h4 class="title-2">{{__('template.shipping_method')}}</h4>
                                        <div class="mb-5">
                                            @foreach($shippingMethods as $method)
                                                <!-- card -->
                                                <div class="card">
                                                    <label class="radio-container" for="{{$method->code}}">
                                                        <input  type="radio" 
                                                                id="{{$method->code}}" 
                                                                name="shipping_method" 
                                                                value="{{$method->code}}" 
                                                                {{null !== $cart->order && $cart->order->shippingMethod->code === $method->code ? 'checked' : ($method->is_default ? 'checked' : '') }}
                                                            >
                                                        {{$method->name}}
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <div class="card-body d-none">
                                                        {{strip_tags($method->description)}}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <h4 class="title-2">{{__('template.payment_method')}}</h4>
                                        <div class="mb-5">
                                            @foreach($paymentMethods as $method)
                                                <!-- card -->
                                                <div class="card">
                                                    <label class="radio-container" for="{{$method->code}}">
                                                        <input  type="radio" 
                                                                id="{{$method->code}}" 
                                                                name="payment_method" 
                                                                value="{{$method->code}}" 
                                                                {{null !== $cart->order && $cart->order->paymentMethod->code === $method->code ? 'checked' : ($method->is_default ? 'checked' : '') }}
                                                            >
                                                        {{$method->name}}
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <div class="card-body d-none">
                                                        {{strip_tags($method->description)}}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="text-right">
                                            <button class="btn theme-btn-1 btn-effect-1 text-uppercase" type="submit" data-action="place-order">
                                                {{__('template.place_order')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                @else
                    @include('includes.empty-cart')
                @endif
            </div>
        </div>
    </div>
    <!-- CHECKOUT AREA END -->
    
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdn.rawgit.com/hayeswise/Leaflet.PointInPolygon/v1.0.0/wise-leaflet-pip.js"></script>
    
    <script type="module">

        /**
         * Copy personal data, in case the customer will receive order by himself
         */
        $('input[name="details[myself]"]').on('change', function(){
            var $section = $('#contact-person'),
                $inputContactPerson = $('input[name="details[contact_person]"]'),
                $inputPhone = $('input[name="details[phone]"]');

            if($(this).is(':checked')){
                $section.hide();
                var fname = $('input[name="customer[fname]"]').val(),
                    lname = $('input[name="customer[lname]"]').val(),
                    phone = $('input[name="customer[phone]"]').val();

                $inputContactPerson.val(fname + ' ' + lname);
                $inputPhone.val(phone);
            } else {
                $section.show();
                $inputContactPerson.val("");
                $inputPhone.val("");
            }
        });

        /**
         * Copy personal data
         */
        $('input[name="customer[fname]"], input[name="customer[lname]"]').on('change', function(){
            if($('input[name="details[myself]"]').is(':checked')){
                var fname = $('input[name="customer[fname]"]').val(),
                    lname = $('input[name="customer[lname]"]').val();
                $('input[name="details[contact_person]"]').val(fname + ' ' + lname);
            }
        });
        $('input[name="customer[phone]"]').on('change', function(){
            if($('input[name="details[myself]"]').is(':checked')){
                var phone = $('input[name="customer[phone]"]').val();
                $('input[name="details[phone]"]').val(phone);
            }
        });

        /**
         * Datepicker
         */
        $('#datepicker').datetimepicker({
            timepicker:false,
            format:'Y-m-d',
            minDate: @js(date('Y-m-d')),
            lang: @js(app()->getLocale())
        });

        function getCountry(){
            var country = $('select[name="address[country]"]').val();
            if(country === 'NONE'){
                console.log('You should specify your country!');
                return null;
            }
            return country;
        }

        function getLocality(){
            var locality = $('select[name="address[locality]"]').val();
            if(locality === 'NONE'){
                console.log('You should specify your locality!');
                return null;
            }
            return locality;
        }

        /**
         * Get address coordinates if exists
         * Just address[coordinates] matter, because only coordinates affects the shipping
         * Using address[address] we can not calculate the shipping
         */
        function getCoordinates(){
            var $addressCoordinates = $('input[name="address[coordinates]"]');
            if($addressCoordinates.length){
                return JSON.parse($addressCoordinates.val());
            }
            return null;
        }

        function parseAddress(address){
            var result = address.city;
            if(address.road){
                result += `, ${address.road}`;
            }
            if(address.house_number){
                result += `, ${address.house_number}`;
            }

            return result;
        }

        /**
         * Calculate distance between two points (addresses) in km
         */
        function calculateDistance(finish){
            /**
             * Initial point
             * The distance will be calculated starting from this point
             * The point coordinates are hardcoded for the moment
             * But can be used from some settings from backend if needed
             */
            const start = {lat: 47.02643, lng: 28.83251}; // Chisinau flowers market

            const params = `${start.lat},${start.lng};${finish.lat},${finish.lng}`;

            const url = `https://router.project-osrm.org/route/v1/driving/${params}?overview=false`;
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    var $input = $('input[autocomplete="true"]');
                    // Access the distance in meters from the API response
                    const distanceM = data.routes[0].distance;
                    
                    // Convert distance to kilometers
                    const distanceKM = (distanceM / 1000).toFixed(2);

                    var $addressDistance = $('input[name="address[distance]"]');
                    if($addressDistance.length){
                        $addressDistance.val(distanceKM);
                    } else {
                        $addressDistance = $("<input>").attr('type', 'hidden').attr('name', 'address[distance]').val(distanceKM);
                        $input.next('ul').after($addressDistance);
                    }

                    calculateShipping([], distanceKM);

                    console.log('Distance between points:', distanceKM + ' km');
                })
                .catch(error => console.error('Error:', error));
        }

        function detectShippingZone(params){
            $.ajax({
                url: '/checkout/detect-shipping-zone',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                data: params,
                success: function(response) {
                    console.log('Zones: ', response);
                    if(response.status === 200){
                        // Collect zones codes
                        let zones = new Array()
                        response.zones.map((zone) => {
                            if(zone.on_map && null !== zone.area){
                                const point = L.latLng(params.coordinates.lat, params.coordinates.lng)
                                if(L.polygon(zone.area).contains(point)){
                                    console.log('Contains: ', zone.code);
                                    zones.push(zone.code)
                                }
                            } else {
                                zones.push(zone.code)
                            }   
                        })
                        /**
                         * If we found the zone, chosen address belong to, 
                         * calculate the shipping for detected zone
                         */
                        if(zones.length){
                            calculateShipping(zones);
                        } else {
                            /**
                             * Try to calculate distance to the point (address coordinates)
                             */
                            if(params.coordinates){
                                calculateDistance(params.coordinates)
                            }
                            
                        }
                        
                    } else {
                        $('#shipping-cost').html(@js(__('template.not_available')));
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', xhr, status, error);
                    // Handle error response
                }
            });
        }

        function calculateShipping(zones, distance = 0){
            /**
             * Method can be or can not to be provided
             * Depends of how many shipping methods will be supported
             * In case there is only one shipping method, pass just conditions
             */
            var method = $('input[name="shipping_method"]:checked').val();
            // Perform the AJAX POST request to calculate shipping
            $.ajax({
                url: '/checkout/calculate-shipping',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                data: {
                    method,
                    zones, 
                    distance
                },
                success: function(response) {
                    if(response.status === 200){
                        if(response.shipping.available){
                            $('#shipping-cost').html(response.shipping.price_formatted);
                            $('button[type="submit"]').prop('disabled', false);
                        } else {
                            $('#shipping-cost').html(@js(__('template.not_available')));
                            /**
                             * Disable button to prevent submitting the form while selected unavailable shipping
                             */
                            $('button[type="submit"]').prop('disabled', true);
                        }
                            
                        $('#order-total').html(response.orderTotal);
                    }   
                },
                error: function(xhr, status, error) {
                    console.error('Error:', xhr, status, error);
                    // Handle error response
                }
            }); 
        }

        $('select[name="address[country]"]').on('change', function(){
            var country = $(this).val(),
                locality = getLocality(),
                $dependentSelect = $('select[name="address[locality]"]'),
                $dependentInputs = $('input[name="address[other_country]"], input[name="address[other_locality]"]');

            detectShippingZone({
                country, 
                locality
            });

            /**
             * If other country is selected, 
             * give the possibility to input the country and locallity manually
             */
            if(country === 'OTH'){
                $dependentSelect.closest('div[class*="col"]').addClass('d-none');
                $dependentInputs.prop('required', true).closest('div[class*="col"]').removeClass('d-none');
            } else {
                $dependentSelect.closest('div[class*="col"]').removeClass('d-none');
                $dependentInputs.prop('required', false).closest('div[class*="col"]').addClass('d-none');
                $dependentInputs.val('');
            }
        });

        $('select[name="address[locality]"]').on('change', function(){
            var country = getCountry(),
                $select = $(this),
                locality = $select.val(),
                $dependentInput = $('input[name="address[other_locality]"]');
            
            detectShippingZone({
                country, 
                locality
            });

            /**
             * If other locality is selected, 
             * give the possibility to input the locallity manually
             */
            if(locality === 'OTH'){
                $select.closest('div[class*="col"]').removeClass('col-md-12').addClass('col-md-6');
                $dependentInput.prop('required', true).closest('div[class*="col"]').removeClass('d-none');
            } else {
                $select.closest('div[class*="col"]').removeClass('col-md-6').addClass('col-md-12');
                $dependentInput.prop('required', false).closest('div[class*="col"]').addClass('d-none');
                $dependentInput.val('');
            }
        });

        $('input[name="shipping_method"]').on('change', function(){
            var country = getCountry(),
                locality = getLocality(),
                coordinates = getCoordinates();
            
            if(coordinates){
                detectShippingZone({
                    coordinates
                });
            } else {
                detectShippingZone({
                    country, 
                    locality
                });
            }
        })

        /**
         * Autocomplete the address
         */
        function selectResult($input, result){
            console.log('Selected result: ', result);
            $input.val(parseAddress(result.address));
            const coordinates = {
                    lat: result.lat,
                    lng: result.lon
                };
            var $addressCoordinates = $('input[name="address[coordinates]"]');
            if($addressCoordinates.length){
                $addressCoordinates.val(JSON.stringify(coordinates));
            } else {
                $addressCoordinates = $("<input>").attr('type', 'hidden').attr('name', 'address[coordinates]').val(JSON.stringify(coordinates));
                $input.next('ul').after($addressCoordinates);
            }
            $input.next('ul').empty(); // Clear the results list
            detectShippingZone({coordinates})
        }

		function displayResults(results) {
            var $input = $('input[autocomplete="true"]'),
                $resultsList = $('#autocomplete-address');
            if($resultsList.length){
                $resultsList.empty().hide();
            } else {
                $resultsList = $("<ul>").attr('id', 'autocomplete-address');
            }
            
            if(results.length){
                results.forEach((result) => {
                    const li = $("<li>")
                    li.text(parseAddress(result.address))
                    li.on('click', function(){
                        selectResult($input, result);
                    })
                    $resultsList.append(li);
                })
            } else {
                const li = $("<li>")
                li.text(@js(__('template.address_not_found')))
                $resultsList.append(li);
            }
			
            $input.after($resultsList);
            $resultsList.show();
            // Remove the spinner after results were shown
            $('.spinner-border').remove();
		}

        /* 
         * Perform api request with given params
         */
		function autocompleteAddress(address) {
			var url = new URL('https://nominatim.openstreetmap.org/search'),
                params = {
                    'q': address,
                    'countrycodes': ['MD'], // Specify country code(s)
                    'addressdetails': 1,
                    //extratags: 'highway',
                    'format': 'json',
                    'accept-language': @js(app()->getLocale()) // Specify the language code
                }
            
			url.search = new URLSearchParams(params).toString()

			fetch(url)
				.then((response) => response.json())
				.then((data) => {
					console.log('Request result: ', data)
					displayResults(data)
				})
				.catch((error) => console.log('Error: ', error))
		}

        /**
         * Add a debounce timer to wait while user will stop typing
         */
        var debounceTimer
		const debounceInterval = 1000 // 1 second

        /**
         * Handle input and trigger autocomplete
         */
        function handleAutocompletedInput($input) {
			clearTimeout(debounceTimer)
            const address = $input.val();
			if (address.trim() !== '') {
                $input.attr('autocomplete', true);
                /**
                 * Bootstrap spinner is used, can be used any other instead
                 */
                var $spinner = $('.spinner-border');
                if(!$spinner.length){
                    $spinner = $('<div>').addClass('spinner-border').attr('type', 'status');
                    $input.before($spinner);
                }

				debounceTimer = setTimeout(() => {
					autocompleteAddress(address)
				}, debounceInterval)
			}
		}

        $('input[data-action="autocomplete-address"]').on('input', function(){
            handleAutocompletedInput($(this));
        })

        /**
         * Hide results if clicked outside
         */
        $(document).on('click', function(event){
            if(event.target !== $('input[data-action="autocomplete-address"]')){
                $('#autocomplete-address').empty().hide();
            }
        })

        $(document).ready(function(){
            var $submitButton = $('button[data-action="place-order"]'),
                action = @JS(route('checkout.place-order')),
                $form = $('form[action="'+action+'"]'); 

            $submitButton.on('click', function(e){
                console.log($form);
                e.preventDefault();
                $form.submit();
            })
        })

    </script>
</x-app-layout>