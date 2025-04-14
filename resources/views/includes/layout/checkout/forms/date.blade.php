<input 
x-bind:disabled='pickup'
type="text" 
name="details[shipping_date]" 
class="checkout-data datepicker w-full py-2 px-3 !m-0 h-[42px] !rounded-lg border"
@if (isset($oldDataCheckout['details']['shipping_date']) && $oldDataCheckout['details']['shipping_date'] !== null) value="{{ $oldDataCheckout['details']['shipping_date'] }}" @endif
placeholder="{{ __('template.shipping_date') }}" 
value="{{null !== $cart->order && $cart->order->details ? $cart->order->details->shipping_date : date('Y-m-d')}}" 
required 
/>