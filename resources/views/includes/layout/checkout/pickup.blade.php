<div class="bg-white rounded-lg p-4 flex-col flex gap-6">
    <!-- Address Shop Start-->
        <div class="flex flex-col gap-3">
            <h1 class="text-xl font-semibold">{{ str_replace(':', '', __('template.salon_address')) }}</h1>
            <div >
                <p class="text-neutral-600">{{ $templateSettings['address'] }}</p>
                <p class="text-neutral-600">{{ $templateSettings['working-days'] }}: {{ $templateSettings['working-hours']  }}</p>
            </div>
        </div>
    <!-- Address Shop End-->

    <!-- Yours Contacts Data Start-->
        <div>
            <h1 class="font-semibold text-xl">{{ __('template.your_contact_details') }}</h1>
            <div class="flex flex-col gap-2 mt-2">
                <input type="text" 
                    onfocus="this.classList.remove('!border-red-500','!text-red-500')" 
                    class="checkout-data w-full py-2 px-3 !m-0 !rounded-lg border @error('customer.name') !border-red-500 !text-red-500 @enderror" 
                    placeholder="{{ __('template.name') }}"
                    x-bind:name="pickup ? 'customer[name]' : ''"
                    @if (isset($oldDataCheckout['customer']['name']) && $oldDataCheckout['customer']['name'] !== null) value="{{ $oldDataCheckout['customer']['name'] }}" @endif
                    value="{{null !== $cart->order ? $cart->order->customer->name : ''}}{{ old('customer.name') }}" 
                    required >
                <div class="flex gap-2">
                    <input type="text" 
                        onfocus="this.classList.remove('!border-red-500','!text-red-500')" 
                        class="checkout-data w-full py-2 px-3 !m-0 !rounded-lg border @error('customer.phone') !border-red-500 !text-red-500 @enderror" 
                        placeholder="{{ __('template.phone') }}"
                        x-bind:name="pickup ? 'customer[phone]' : ''" 
                        @if (isset($oldDataCheckout['customer']['phone']) &&  $oldDataCheckout['customer']['phone'] !== null) value="{{ $oldDataCheckout['customer']['phone'] }}" @endif
                        value="{{null !== $cart->order ? $cart->order->customer->phone : ''}}{{ old('customer.phone') }}" 
                        required >
                    <input type="email"
                        onfocus="this.classList.remove('!border-red-500','!text-red-500')" 
                        class="checkout-data w-full py-2 px-3 !m-0 !rounded-lg border @error('customer.email') !border-red-500 !text-red-500 @enderror" 
                        placeholder="{{ __('template.email') }}"
                        @if (isset($oldDataCheckout['customer']['email']) && $oldDataCheckout['customer']['email'] !== null) value="{{ $oldDataCheckout['customer']['email'] }}" @endif
                        x-bind:name="pickup ? 'customer[email]' : ''" 
                        value="{{null !== $cart->order ? $cart->order->customer->email : ''}}{{ old('customer.number') }}" 
                        required >
                </div>
            </div>
        </div>
    <!-- Yours Contacts Data End-->

    <!-- When pickup Start-->
        <div class="flex flex-col gap-1">
            <h1 class="text-xl font-semibold">{{ __("template.when_pick_up") }}</h1>
            <div class="flex flex-col gap-2">
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <input 
                            type="text" 
                            name="details[shipping_date]" 
                            class="checkout-data datepicker w-full py-2 px-3 !m-0 h-[42px] !rounded-lg border"
                            @if (isset($oldDataCheckout['details']['shipping_date']) && !empty($oldDataCheckout) && $oldDataCheckout['details']['shipping_date'] !== null) value="{{ $oldDataCheckout['details']['shipping_date'] }}" @endif
                            placeholder="{{ __('template.shipping_date') }}" 
                            value="{{null !== $cart->order && $cart->order->details ? $cart->order->details->shipping_date : date('Y-m-d')}}" 
                            required 
                        />
                        <select name="details[shipping_range]" class="select-input checkout-data nice-select w-full !h-full py-1 bg-neutral-100" required >
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
                    <div>
                        @include('includes.warning.pickup')
                    </div>
                </div>
                <textarea
                    id="" 
                    cols="30" 
                    rows="10" 
                    placeholder="{{ __('template.order_comment') }}" maxlength="500"
                    x-bind:name="pickup ? 'details[message]' : ''"  
                    class="checkout-data h-40 border rounded-lg bg-neutral-100 resize-none">@if (isset($oldDataCheckout['details']['message']) && !empty($oldDataCheckout) && $oldDataCheckout['details']['message'] !== null){{ trim($oldDataCheckout['details']['message']) }}@endif</textarea>
            </div>
        </div>
    <!-- When pickup End-->
</div>
