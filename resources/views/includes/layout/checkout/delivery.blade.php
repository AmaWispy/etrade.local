<!-- Whom to Deliver Box Start-->
    <div @if (isset($oldDataCheckout['whom_deliver']) && !empty($oldDataCheckout) || session()->get('whom_deliver', null) !== null) x-data='{whomDeliver:true}' @else x-data="{whomDeliver:false}" @endif class="bg-white rounded-lg p-4 flex flex-col gap-6">
        <!-- Whom to Deliver checkBox Start-->
            <div class="flex flex-col gap-3 border-b pb-3">
                <h1 class="text-xl font-semibold">{{ __("template.delivery_to_whom") }}</h1>
                <input type="checkbox" x-bind:name="!pickup ? 'whom_deliver' : '' " class="checkout-data hidden" x-bind:checked='whomDeliver'>
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
                        onfocus="this.classList.remove('!border-red-500','!text-red-500')" 
                        class="checkout-data w-full py-2 px-3 !m-0 !rounded-lg border @error('customer.name') !border-red-500 !text-red-500 @enderror" 
                        placeholder="{{ __('template.name') }}"
                        x-bind:name="!pickup ? 'customer[name]' : ''"
                        @if (isset($oldDataCheckout['customer']['name']) && $oldDataCheckout['customer']['name'] !== null && !empty($oldDataCheckout)) value="{{ $oldDataCheckout['customer']['name'] }}" @endif
                        value="{{ old('customer.name') }}" 
                        required >
                    <div class="flex gap-2">
                        <input type="text" 
                            onfocus="this.classList.remove('!border-red-500','!text-red-500')" 
                            class="checkout-data w-full py-2 px-3 !m-0 !rounded-lg border @error('customer.phone') !border-red-500 !text-red-500 @enderror" 
                            placeholder="{{ __('template.phone') }}"
                            x-bind:name="!pickup ? 'customer[phone]' : ''" 
                            @if (isset($oldDataCheckout['customer']['phone']) &&  $oldDataCheckout['customer']['phone'] !== null && !empty($oldDataCheckout)) value="{{ $oldDataCheckout['customer']['phone'] }}" @endif
                            value="{{ old('customer.phone') }}" 
                            required >
                        <input type="email" 
                            onfocus="this.classList.remove('!border-red-500','!text-red-500')" 
                            class="checkout-data w-full py-2 px-3 !m-0 !rounded-lg border @error('customer.email') !border-red-500 !text-red-500 @enderror" 
                            placeholder="{{ __('template.email') }}"
                            x-bind:name="!pickup ? 'customer[email]' : '' " 
                            @if (isset($oldDataCheckout['customer']['email']) && $oldDataCheckout['customer']['email'] !== null && !empty($oldDataCheckout)) value="{{ $oldDataCheckout['customer']['email'] }}" @endif
                            value="{{ old('customer.email') }}" 
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
                        x-bind:disabled='pickup || whomDeliver' 
                        class="checkout-data w-full py-2 px-3 !m-0 !rounded-lg border @error('details.contact_person') !border-red-500 !text-red-500 @enderror" 
                        placeholder="{{ __('template.name') }}"
                        x-bind:name="!pickup ? 'details[contact_person]' : '' " 
                        @if (isset($oldDataCheckout['details']['contact_person']) && $oldDataCheckout['details']['contact_person'] !== null && !empty($oldDataCheckout)) value="{{ $oldDataCheckout['details']['contact_person'] }}" @endif
                        value="{{ old('details.contact_person') }}" 
                        required>
                    <input type="text" 
                        x-bind:disabled='pickup || whomDeliver'
                        class="checkout-data w-full py-2 px-3 !m-0 !rounded-lg border @error('details.phone') !border-red-500 !text-red-500 @enderror" 
                        placeholder="{{ __('template.phone') }}"
                        x-bind:name="!pickup ? 'details[phone]' : '' " 
                        @if (isset($oldDataCheckout['details']['phone']) && $oldDataCheckout['details']['phone'] !== null && !empty($oldDataCheckout)) value="{{ $oldDataCheckout['details']['phone'] }}" @endif
                        value="{{ old('details.phone') }}" 
                        required>
                </div>
            </div>
        <!-- Contacts Recipient End-->
    </div>
<!-- Whom to Deliver Box End-->

<!-- Delivery Address Start-->
    <div class="bg-white p-4 flex-col flex gap-2 rounded-lg" @if(isset($oldDataCheckout['address']['dont_know_address']) && !empty($oldDataCheckout)) x-data="{idka:true}" @else x-data="{idka:false}" @endif> <!-- idka -> i don't know address -->
        <h1 class="text-xl font-semibold">{{ __('template.delivery_address') }}</h1>
        <!-- Region, City and Region City Start-->
            <div class="flex flex-col gap-2">
                <div class="flex gap-2">
                    <div class="w-full">
                        <select   
                            id="raion"
                            x-bind:name="!pickup ? 'address[district]' : '' "
                            disabled
                            class="select-input checkout-data nice-select w-full bg-neutral-100 !m-0 w-full @error('address.district') !border-red-500 !text-red-500 @enderror">
                            <option data-null='NONE'>{{__('template.district')}}</option>
                            @foreach(App\Models\Shop\ShippingZone::whereNotNull('area')->get() as $raion)
                                <option 
                                    @if (isset($oldDataCheckout['address']['district']) && !empty($oldDataCheckout) &&  $oldDataCheckout['address']['district'] !== null && $oldDataCheckout['address']['district'] === $raion->name) selected @endif
                                    data-raion="{{$raion->code}}"
                                >
                                    {{$raion->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full">
                        <select   
                            x-bind:name="!pickup ? 'address[locality]' : '' " 
                            id="locality"
                            onfocus="this.classList.remove('!border-red-500','!text-red-500')"
                            class="select-input checkout-data nice-select w-full bg-neutral-100 !m-0 w-full @error('address.locality') !border-red-500 !text-red-500 @enderror"
                            required>
                            <option data-null='NONE'>{{__('template.city')}}</option>
                            @foreach($cities as $city)
                                <option
                                    data-code = '{{ $city->code }}'
                                    @if (isset($oldDataCheckout['address']['locality']) && !empty($oldDataCheckout) &&  $oldDataCheckout['address']['locality'] !== null && $oldDataCheckout['address']['locality'] === $city->name) selected @endif
                                    value="{{$city->code}}"
                                    {{-- {{null !== $cart->order && $cart->order->address->locality === $city->code ? 'selected' : ''}} --}}
                                    {{null !== $cart->order && $cart->order->address->locality === $city->code ? 'selected' : ''}}
                                >
                                    {{$city->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- <div class="w-full">
                        <select   
                            x-bind:name="!pickup ? 'address[locality]' : '' " 
                            id="locality"
                            class="select-input checkout-data nice-select w-full bg-neutral-100 !m-0  
                            @error('address.locality') !border-red-500 !text-red-500 @enderror" 
                            onfocus="this.classList.remove('!border-red-500','!text-red-500')" 
                            required >
                            <option>{{__('template.city')}}</option>
                            @foreach($cities as $city)
                                <option 
                                    @if (isset($oldDataCheckout['address']['locality']) && !empty($oldDataCheckout) &&  $oldDataCheckout['address']['locality'] !== null && $oldDataCheckout['address']['locality'] === $city->code) selected @endif
                                    value="{{$city->code}}"
                                    {{null !== $cart->order && $cart->order->address->locality === $city->code ? 'selected' : ''}}
                                >
                                    {{$city->name}}
                                </option>
                            @endforeach
                        </select>
                    </div> --}}
                </div>
                <select  x-bind:name="!pickup ? 'address[district_city]' : '' " 
                    class="select-input checkout-data nice-select !m-0 bg-neutral-100 !rounded-lg border w-full @error('address.district_city') !border-red-500 !text-red-500 @enderror"">
                    <option value="NONE">{{ __("template.city_district") }}</option>
                </select>
            
                <label for="dont_know_address" class="flex gap-2 cursor-pointer" class="relative">
                    <input x-on:click="idka = !idka" 
                        type="checkbox" 
                        id="dont_know_address" 
                        x-bind:name="!pickup ? 'address[dont_know_address]' : ''" 
                        class="checkout-data text-black mt-1" x-bind:checked="idka">
                    <span>{{ __('template.unknown_address') }}</span>
                </label>
            </div>
        <!-- Region, City and Region City End-->

        <!-- Exact Address Start-->
            <div class="flex flex-col gap-2 mt-3">
                <input x-bind:disabled='pickup || idka' 
                    x-show='!idka' 
                    type="text" 
                    x-bind:name="!pickup ? 'address[street]' : '' "
                    @if (isset($oldDataCheckout['address']['street']) && !empty($oldDataCheckout) && $oldDataCheckout['address']['street'] !== null) value="{{ $oldDataCheckout['address']['street'] }}" @endif
                    value="{{ old('address.street') }}" 
                    class="checkout-data w-full py-2 px-3 !m-0 !rounded-lg border @error('address.street') !border-red-500 !text-red-500 @enderror"" 
                    placeholder="{{ __('street') }}">
                <div x-show='!idka' class="flex gap-2">
                    <input x-bind:disabled='pickup || idka' 
                        type="text" 
                        class="checkout-data w-full py-2 px-3 !m-0 !rounded-lg border @error('address.house_number') !border-red-500 !text-red-500 @enderror"" 
                        x-bind:name="!pickup ? 'address[house_number]' : '' "
                        @if (isset($oldDataCheckout['address']['house_number']) && !empty($oldDataCheckout) && $oldDataCheckout['address']['house_number'] !== null) value="{{ $oldDataCheckout['address']['house_number'] }}" @endif 
                        value="{{ old('address.house_number') }}" 
                        placeholder="{{ __('template.house_number') }}">
                    <input x-bind:disabled='pickup || idka' 
                        type="text" 
                        class="checkout-data w-full py-2 px-3 !m-0 !rounded-lg border @error('address.appartament_number') !border-red-500 !text-red-500 @enderror"" 
                        x-bind:name="!pickup ? 'address[appartament_number]' : '' "
                        @if (isset($oldDataCheckout['address']['appartament_number']) && !empty($oldDataCheckout) && $oldDataCheckout['address']['appartament_number'] !== null) value="{{ $oldDataCheckout['address']['appartament_number'] }}" @endif 
                        value="{{ old('address.appartament_number') }}" 
                        placeholder="{{ __('template.apartment_number') }}">
                </div>
                <div x-show='!idka' class="flex gap-2">
                    <input x-bind:disabled='pickup || idka' 
                        type="text" 
                        class="checkout-data w-full py-2 px-3 !m-0 !rounded-lg border @error('address.entrance') !border-red-500 !text-red-500 @enderror"" 
                        x-bind:name="!pickup ? 'address[entrance]' : '' "
                        @if (isset($oldDataCheckout['address']['entrance']) && !empty($oldDataCheckout) && $oldDataCheckout['address']['entrance'] !== null) value="{{ $oldDataCheckout['address']['entrance'] }}" @endif
                        value="{{ old('address.entrance') }}"  
                        placeholder="{{ __('template.entrance') }}">
                    <input x-bind:disabled='pickup || idka' 
                        type="text" 
                        class="checkout-data w-full py-2 px-3 !m-0 !rounded-lg border @error('address.floor') !border-red-500 !text-red-500 @enderror"" 
                        @if (isset($oldDataCheckout['address']['floor']) && !empty($oldDataCheckout) && $oldDataCheckout['address']['floor'] !== null) value="{{ $oldDataCheckout['address']['floor'] }}" @endif
                        x-bind:name="!pickup ? 'address[floor]' : '' " 
                        value="{{ old('address.floor') }}" 
                        placeholder="{{ __('template.floor') }}">
                    <input x-bind:disabled='pickup || idka' 
                        type="text" 
                        class="checkout-data w-full py-2 px-3 !m-0 !rounded-lg border @error('address.intercom') !border-red-500 !text-red-500 @enderror"" 
                        @if (isset($oldDataCheckout['address']['intercom']) && !empty($oldDataCheckout) && $oldDataCheckout['address']['intercom'] !== null) value="{{ $oldDataCheckout['address']['intercom'] }}" @endif
                        x-bind:name="!pickup ? 'address[intercom]' : ''" 
                        value="{{ old('address.intercom') }}" 
                        placeholder="{{ __('template.intercom') }}">
                </div>
                <textarea
                    id="" 
                    cols="30" 
                    rows="10" 
                    placeholder="{{ __('template.delivery_comment') }}" maxlength="500"
                    x-bind:name="!pickup ? 'details[message]' : ''"  
                    class="checkout-data h-40 border rounded-lg bg-neutral-100 resize-none">@if (isset($oldDataCheckout['details']['message']) && !empty($oldDataCheckout) && $oldDataCheckout['details']['message'] !== null){{ trim($oldDataCheckout['details']['message']) }}@endif</textarea>
            </div>
        <!-- Exact Address End-->

        <!-- Exact Time and Data Start-->
            <div class="flex flex-col gap-2" @if(isset($oldDataCheckout['details']['fixed_time']) && !empty($oldDataCheckout))  x-data='{exact_time:true}' @else x-data='{exact_time:false}' @endif>
                <label for="exact_time" class="flex gap-2 cursor-pointer">
                    <input x-bind:name="!pickup ? 'details[fixed_time]' : '' " x-on:click="exact_time = !exact_time; fixedPrice = !fixedPrice" type="checkbox" id="exact_time" class="checkout-data text-black mt-1" x-bind:checked="exact_time">
                    <span>{{ __('template.fixed_delivery_time') }}</span>
                </label>

                <!-- Date and Time Set Start-->
                    <div class="flex items-center gap-2">
                        <input 
                        type="text" 
                        x-bind:name="!pickup ? 'details[shipping_date]' : '' " 
                        class="checkout-data datepicker w-full py-2 px-3 !m-0 h-[42px] !rounded-lg border"
                        @if (isset($oldDataCheckout['details']['shipping_date']) && !empty($oldDataCheckout) && $oldDataCheckout['details']['shipping_date'] !== null) value="{{ $oldDataCheckout['details']['shipping_date'] }}" @endif
                        placeholder="{{ __('template.shipping_date') }}" 
                        value="{{null !== $cart->order && $cart->order->details ? $cart->order->details->shipping_date : date('Y-m-d')}}" 
                        required 
                        />
                        <select  x-bind:name="!pickup ? 'details[shipping_range]' : '' " class="select-input checkout-data nice-select w-full !h-full py-1 bg-neutral-100" required >
                            <option value="{{__('template.any_time')}}">{{__('template.any_time')}}</option>
                            @foreach($time_ranges as $range)
                                @if(date('H:i') < $range['end'])
                                <option 
                                    value="{{$range['start']}} - {{$range['end']}}"
                                    @if (isset($oldDataCheckout['details']['shipping_range']) && !empty($oldDataCheckout) && $oldDataCheckout['details']['shipping_range'] === ($range['start'] . ' - ' . $range['end'])) selected @endif
                                    {{null !== $cart->order && $cart->order->details->shipping_range === ($range['start'] . '-' . $range['end']) ? 'selected' : ''}}
                                >
                                    {{$range['start']}} - {{$range['end']}}
                                </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                <!-- Date and Time Set End-->

                <!-- Warning Start-->
                <div x-show='!exact_time' class="w-full">
                    @include('includes.warning.night-delivery')
                </div>
                <!-- Warning End-->
            </div>
        <!-- Exact Time and Data End-->
    </div>
<!-- Delivery Address End-->