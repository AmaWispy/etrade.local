<x-app-layout>
    @section('title', __('template.cart'))
   
    <!-- SHOPING CART AREA START -->
        @if(null !== $cart && $cart->items->count() > 0)
        <!-- Cart Start-->
            <div class="lg:container lg:mx-auto sm:mx-2 xl:gap-4 md:gap-5 sm:gap-2"  x-data="{addCard: @if(isset($oldDataCard)) true @else false @endif}">
                <ul>
                    <li>
                        <h1 class="font-semibold lg:text-3xl sm:text-2xl lg:!py-5 md:!py-1 lg:pb-0 sm:pb-2">{{ __("template.cart") }}</h1>
                    </li>
                    <li>
                        <h1></h1>
                    </li>
                </ul>
                <!-- Names Sections Start-->    
                    <div class="xl:inline-flex hidden justify-between w-full text-xl font-semibold my-4">
                        <div class="ml-20">
                            <h1>{{ __('template.product') }}</h1>
                        </div>
                        <ul class="flex items-center ml-[320px] gap-32 w-[340px]">
                            <li>
                                <h1>{{ __('template.price') }}</h1>
                            </li>
                            <li>
                                <h1>{{ __('template.quantity') }}</h1>
                            </li>
                        </ul>
                        <div class="w-[135px]">
                            <h1>{{ __('template.subtotal') }}</h1>
                        </div>
                <!-- Names Sections End-->    
                </div>

                <form action="{{ route('cart.card') }}" method="POST" class="flex gap-2  xl:flex-row sm:flex-col">
                    @csrf
                    <!-- Cart Section Start -->

                        <!-- Products list Start -->
                            <div id="box-cart-items" class="!w-full">
                                @foreach($cart->items as $item)
                                    @include('includes.products.item.cart', [
                                        'item' => $item
                                    ])
                                @endforeach
                            </div>
                        <!-- Products list End -->

                    <!-- Cart Section End -->

                    <!-- Cart Box Total Section Start -->
                        {{-- @include('includes.layout.cart.details-to-order',[
                            'cart'=> $cart
                        ]) --}}
                    <!-- Cart Box Total Section End -->
                </form>
            </div>
        <!-- Cart End-->
        
        <!-- Coupon Start  -->
            <form action="" class="md:container md:mx-auto flex gap-3 md:px-0 px-2">
                @csrf
                <input type="text" class="bg-transparent border-b border-white xl:w-96 w-full !border-b-neutral-300 focus:outline-none focus:ring-transparent focus:border-transparent focus:border-white" placeholder="{{ __('template.enter_coupon_code') }}">
                <button class="border xl:px-10 py-3 px-4 lg:px-8 rounded-lg font-semibold">{{ __('template.apply') }}</button>
            </form>
        <!-- Coupon End -->

        <!-- Check Sumary Start-->
        <div class="container flex justify-end w-full my-5">
            <form class="bg-orange-50 2xl:w-2/6 xl:w-[45%] w-full lg:p-[35px] p-[25px] flex flex-col gap-4 rounded-lg">
                <h1 class="text-xl font-medium">{{ __('template.order_summary') }}</h1>
                <div class="bg-white flex flex-col p-3">
                    <!-- Subtotal Start border-b-->
                        <ul class="flex justify-between items-center py-3">
                            <li>
                                <h1>{{ __('template.subtotal') }}</h1>
                            </li>
                            <li class="w-1/2">
                                <h1 class="cart-total-price" id="order-total">
                                    @if (session()->has('checkout.totalCost'))
                                        {{ str_replace([',', '.00'], [' ', ''], Currency::format(session()->get('checkout.totalCost'))) }}
                                    @else
                                        {{null !== $cart->order ? $cart->order->total : $cart->getTotal() }} 
                                    @endif
                                </h1>
                            </li>
                        </ul>
                    <!-- Subtotal End -->
    
                    <!-- Shipping Start-->
                        <!-- <div class="border-b py-3 flex justify-between items-center">
                            <h1>{{ __('template.shipping') }}</h1>
                            <div class="w-1/2 flex flex-col gap-2">
                                <div class="flex items-center gap-2">
                                    <input id="default-radio-1" type="radio" value="" name="shipping" class="w-4 h-4">
                                    <label for="default-radio-1" class="text-sm">{{ __('template.free_shipping') }}</label>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input id="default-radio-2" type="radio" value="" name="shipping" class="w-4 h-4">
                                    <label for="default-radio-2" class="text-sm">{{ __('template.local') }}: $8.20</label>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input  id="default-radio-3" type="radio" value="" name="shipping" class="w-4 h-4">
                                    <label for="default-radio-3" class="text-sm">{{ __('template.flat_rate') }}: $12.59</label>
                                </div>
                            </div>
                        </div> -->
                    <!-- Shipping End-->

                    <!-- State Tax Start-->
                        <!-- <ul class="flex justify-between items-center border-b py-3">
                            <li>
                                <h1>{{ __('template.state_tax') }}</h1>
                            </li>
                            <li class="w-1/2">
                                <h1>$8.00</h1>
                            </li>
                        </ul> -->
                    <!-- State Tax End-->
    
                    <!-- Total Start-->
                        <!-- <ul class="flex justify-between items-center py-3">
                            <li>
                                <h1>{{ __('template.total') }}</h1>
                            </li>
                            <li class="w-1/2">
                                <h1 class="font-bold text-blue-500 text-lg">$125.00</h1>
                            </li>
                        </ul> -->
                    <!-- Total End-->
                </div>
                {{-- <button class="py-3 rounded-lg bg-blue-500 hover:bg-blue-600 duration-500 text-white font-semibold">{{ __('template.process_to_checkout') }}</button> --}}
                <a href="{{ route('checkout.custom.place-order') }}" class="block items-center text-center py-3 rounded-lg bg-blue-500 hover:bg-blue-600 duration-500 text-white font-semibold">{{ __('template.proceed_to_checkout') }}</a> 
                {{-- eубрать ссылку когда буду уже подкл бэк --}}
            </form>
        </div>
        <!-- Check Sumary End-->
        @else
            @include('includes.empty-cart')
        @endif
    <!-- SHOPING CART AREA END -->
</x-app-layout>

<script type="module">
    let timeout;

    $(document).on('click', 'button[data-action="decrement"]', function() {
        const productId = $(this).data('item'); // Get the product ID from the button data attribute
        var $input = $(this).siblings('input'),
            val = parseInt($input.val());
        if(val > 1){
            $input.val(val - 1).trigger('change');
        }
        
        updateCart()
    });

    $(document).on('click', 'button[data-action="increment"]', function() {
        var $input = $(this).siblings('input'),
        val = parseInt($input.val());
        $input.val(val + 1).trigger('change');
        
        updateCart()
    });

    let cartData = [];

    // delete 
    $(document).on('click', 'button[data-action="remove-cart-item"]', function(e) {
        cartData = []
        e.preventDefault();
        var $button = $(this),
            productId = $button.data('id'),
            item = {
                'item': $button.data('item'),
            };

        // Disable button while ajax in progress
        $button.prop('disabled', true);

        // Perform the AJAX POST request to remove the item
        $.ajax({
            url: '/cart/remove',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            data: item,
            success: function(response) {
                if(response.status === 200){
                    $('[data-counter="cart-total-items"]').html(response.cart.totalItems);
                    $('.cart-total-price').text(response.cart.totalPrice);
                    if(response.cart.totalItems > 1){
                        $('#box-cart-item-' + response.item.id).remove();
                        $('.box-cart-item-' + response.item.id).remove();
                    }
                }   
            },
            error: function(xhr, status, error) {
                console.error('Error:', xhr, status, error);
                // Handle error response
            }
        });
    });

    function updateCart() {
        clearTimeout(timeout);
        
        timeout = setTimeout(function() {
            $('[data-action="increment"], [data-action="decrement"]').prop('disabled', true);
            $('[data-action="update-cart-item-quantity"]').each(function() {
                var product = $(this); 
                let data = {
                    'item': product.data('item'),    
                    'quantity': product.val(),      
                };

                // Проверяем, был ли этот товар уже отправлен
                if (!cartData.some(existingItem => existingItem.item === data.item && existingItem.quantity === data.quantity)) {
                    // Добавляем товар в список отправленных товаров
                    cartData.push(data);

                    $.ajax({
                        url: '/cart/update',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'json',
                        data: data,
                        success: function(response) {
                            $('[data-action="increment"], [data-action="decrement"]').prop('disabled', false);
                            
                            $('.item-' + response.item.id + '-subtotal2').text(response.item.subtotal);
                            
                            $('.cart-total-price').text(response.cart.totalPrice);
                            console.log(response);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', xhr, status, error);
                            $('[data-action="increment"], [data-action="decrement"]').prop('disabled', false);
                        }
                    });
                    console.log(data);
                }
            });
        }, 1500); // Ожидание 1500 мс перед отправкой запроса
    }
</script>