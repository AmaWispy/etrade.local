<select x-bind:disabled='pickup' name="details[shipping_range]" class="checkout-data nice-select w-full !h-full py-1 bg-neutral-100" required >
    <option value="{{__('template.any_time')}}">{{__('template.any_time')}}</option>
    @foreach($time_ranges as $range)
        @if(date('H:i') < $range['end'])
        <option 
            value="{{$range['start']}} - {{$range['end']}}"
            @if (isset($oldDataCheckout['details']['shipping_range']) && $oldDataCheckout['details']['shipping_range'] === ($range['start'] . ' - ' . $range['end'])) selected @endif
            {{null !== $cart->order && $cart->order->details->shipping_range === ($range['start'] . '-' . $range['end']) ? 'selected' : ''}}
        >
            {{$range['start']}} - {{$range['end']}}
        </option>
        @endif
    @endforeach
</select>