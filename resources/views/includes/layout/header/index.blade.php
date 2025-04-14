<style>
    .nice-select{
        z-index: 9999;
        background-color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        padding-left: 1px;
    }
</style>
@php
    use App\Models\Shop\Cart;
    $HeaderMenu = \App\Models\Navigation\Menu::where('key', 'header-nav')->first();
    $cart = null;
    if(session()->has('cart')){
        $cartData = session()->get('cart');
        $cart = Cart::where('code', $cartData['code'])->first();
    }
@endphp

<header class="sticky top-0 z-[9999] bg-white shadow-sm pb-3">
    <div class="xl:container xl:!mx-auto mx-2 flex flex-col">
        <div class="flex justify-between !items-center w-full h-[62px] py-2.5">
            <a href="{{\App\Models\Navigation\Menu::getHomePageLink()}}" class="w-40 h-full items-center flex">
                <img src="{{ asset($templateSettings['logo-image']) }}" class="w-full" alt="Logo Site">
            </a>
            <div class="flex justify-end gap-3 w-full h-full !items-center">
                <form class="w-[80%] h-full relative ">   
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none w-full h-full">
                        <i class="bi bi-search "></i>
                    </div>
                    <input type="search" id="default-search" class="block w-full h-full ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-white focus:ring-blue-400 focus:border-blue-400" placeholder="{{ __('template.what_are_you_looking_for') }}" required />
                </form>
        
                <div class="flex gap-3 h-full ">
                    @include('includes.swichers.currency')
                    @include('includes.swichers.language')
                </div>
            </div>
        </div>
    
        <!-- Links Pages And Menus Start-->
            <nav class="justify-between w-full items-center px-3 md:inline-flex hidden">
                <ul class="flex gap-3 font-semibold">
                    <li><a href="{{ route('home.default') }}" class="!text-black hover:border-b border-neutral-400">{{ __('template.home') }}</a></li>
                    <li><a href="{{ route('shop.home') }}" class="!text-black hover:border-b border-neutral-400">{{ __('template.store') }}</a></li>
                    @foreach($HeaderMenu->items as $link)
                        @if ($link->is_active === 1 )
                            <li class="">
                                <a class="!text-black hover:border-b border-neutral-400" href="{{ (App\Models\Page\Page::find($link->entity_id))->link }}">{{ $link->label}}</a>
                            </li>       
                        @endif
                    @endforeach 
                </ul>
                
                <ul class="flex items-center gap-2 text-xl">
                    <li><a href="{{ route('follow.view') }}" class="duration-500 xl:hover:bg-florarColor xl:hover:text-white rounded-full flex items-center text-center justify-center p-2 w-9 h-9"><i class="bi bi-heart"></i></a></li>
                    <li><button type="button" id="cart" data-drawer-target="drawer-right-example" data-drawer-show="drawer-right-example" data-drawer-placement="right" aria-controls="drawer-right-example" class="duration-500 text-center xl:hover:bg-florarColor xl:hover:text-white rounded-full flex items-center justify-center p-2 w-9 h-9"><i class="bi bi-cart3"></i></button></li>
                    <li><a href="{{ route('account.index') }}" class="duration-500 text-center xl:hover:bg-florarColor xl:hover:text-white rounded-full flex items-center justify-center p-2 w-9 h-9"><i class="bi bi-person"></i></a></li>
                </ul>
            </nav>
        <!-- Links Pages And Menus Start-->
    </div>
</header>


<!-- Menu Start -->
    <div id="drawer-right-example" class="fixed top-0 right-0 h-screen p-4 overflow-y-auto transition-transform translate-x-full bg-white lg:w-[500px] w-screen z-[9999]" tabindex="-1" aria-labelledby="drawer-right-label">
        <div class="flex justify-between items-center border-b border-neutral-300 py-4  my-4">
            <h1 class="text-xl font-bold">{{ __('template.cart_review') }}</h1>
            <button type="button" data-drawer-hide="drawer-right-example" aria-controls="drawer-right-example" class="bg-gray-200 w-fit h-fit rounded-full px-2 py-1 inline-flex items-center justify-center" >
                <i class="bi bi-x"></i>
            </button>
        </div>
        
        <div>
            <!-- Products Start-->
                <div id="cart_menu" class="my-3"></div>
            <!-- Products End-->
            <div class="flex flex-col gap-8">
                <ul class="flex justify-between text-xl font-bold">
                    <li>
                        <h1>{{ __('template.cart_subtotal') }}:</h1>
                    </li>
                    <li>
                        <h1 class="cart-total-price">
                            @if (session()->has('checkout.totalCost'))
                            {{ str_replace([',', '.00'], [' ', ''], Currency::format(session()->get('checkout.totalCost'))) }}
                            @else
                                {{null !== $cart ? (null !== $cart->order ? $cart->order->total : $cart->getTotal()) : '' }} 
                            @endif
                        </h1>
                    </li>
                </ul>
                <ul class="flex lg:justify-between lg:flex-row flex-col gap-3">
                    <li class="w-full">
                        <a href="{{ route('cart.view') }}" class="block text-center bg-blue-500 text-white font-semibold  py-3 h-fit !w-full rounded-lg">{{ __('template.view_cart') }}</a>
                    </li>
                    <li class="w-full">
                        <a href="{{ route('checkout.index') }}" class="block text-center bg-florarColor text-white font-semibold py-3 h-fit w-full rounded-lg">{{ __('template.checkout') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
<!-- Menu End -->
<script type="module">
    $(document).ready(function(){
        $('#cart').on('click', function(){
            $.ajax({
                url: `/cart/show`,
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    console.log(response);
                    $('#cart_menu').text('');

                    response['products_info'].forEach(product => {
                        $('#cart_menu').append(`
                            <div class="flex lg:justify-between lg:items-center flex-col lg:flex-row" id="box-cart-item-${product['id']}">
                                <div class="flex items-center gap-3">
                                    <div class="relative border h-24 w-24 rounded-lg">
                                        <img src="${product['image']}" class="h-full w-full object-cover rounded-lg" alt="${product['name']}">
                                        <div class="absolute -top-2 -left-2 bg-white p-1 rounded-full">
                                            <button 
                                            type="button"
                                            data-action="remove-cart-item" 
                                            data-item="${product['id']}" 
                                            data-id="${product['product_id']}"
                                            class="bg-gray-200 w-fit h-fit px-2 py-1 rounded-full text-sm font-bold">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <ul class="flex flex-col gap-2">
                                        <li>
                                            <h1 class="flex gap-1">
                                                <i class="bi bi-star"></i>
                                                <i class="bi bi-star"></i>
                                                <i class="bi bi-star"></i>
                                                <i class="bi bi-star"></i>
                                                <i class="bi bi-star"></i>
                                                (0)
                                            </h1>
                                        </li>
                                        <li>
                                            <h1 class="font-semibold truncate lg:w-40 md:w-52 w-40">${product['name']}</h1>
                                        </li>
                                        <li>
                                            <h1 class="item-${product['id']}-subtotal font-semibold">${product['price']}</h1>
                                        </li>
                                    </ul>
                                </div>
                                <div class="flex gap-2 items-center justify-end">
                                    <button type="button" data-action="decrement-menu" class="text-xl text-black bg-gray-200 w-fit h-fit px-[6px] py-[3px] rounded-full flex justify-center items-center text-center xl:hover:bg-blue-500 xl:hover:text-white cursor-pointer">
                                        <i class="bi bi-dash-lg text-sm"></i>
                                    </button>
                                    <input 
                                        type="text" 
                                        value="${product['qty']}"
                                        data-action="update-cart-item-quantity" 
                                        data-item="${product['id']}"
                                        class="w-14 !m-0 focus:border-transparent focus:border-white bg-transparent !p-0 text-center border-transparent outline-none ring-0 focus:ring-transparent"
                                    />
                                    <button type="button" data-action="increment-menu" class="increment text-xl  text-black bg-gray-200 w-fit h-fit px-[6px] py-[3px] rounded-full flex justify-center items-center text-center xl:hover:bg-blue-500 xl:hover:text-white cursor-pointer">
                                        <i class="bi bi-plus-lg text-sm"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="h-[.5px] bg-gray-200 rounded-xl w-full my-5 box-cart-item-${product['id']}"></div>
                        `);
                    });
                },
                error:function(xhr, status, error) {
                    console.error('Error:', xhr, status, error);
                }
            });
        });
        let timeout;

        $(document).on('click', 'button[data-action="decrement-menu"]', function() {
            const productId = $(this).data('item'); // Get the product ID from the button data attribute
            var $input = $(this).siblings('input'),
                val = parseInt($input.val());
            if(val > 1){
                $input.val(val - 1).trigger('change');
            }
            
            updateCart()
        });

        $(document).on('click', 'button[data-action="increment-menu"]', function() {
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
            
            // Отключаем кнопки сразу после клика
            $('[data-action="increment-menu"], [data-action="decrement-menu"]').prop('disabled', true);

            timeout = setTimeout(function() {
                // Перебираем все товары с атрибутом data-action="update-cart-item-quantity"
                $('[data-action="update-cart-item-quantity"]').each(function() {
                    var product = $(this); // Это текущий элемент
                    let data = {
                        'item': product.data('item'),     // Получаем item
                        'quantity': product.val(),        // Получаем quantity
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
                                // Включаем кнопки после успешного запроса
                                $('[data-action="increment-menu"], [data-action="decrement-menu"]').prop('disabled', false);
                                
                                // Обновляем subtotal для товара
                                $('.item-' + response.item.id + '-subtotal').text(response.item.subtotal);
                                
                                $('.cart-total-price').text(response.cart.totalPrice);
                                console.log(response);
                            },
                            error: function(xhr, status, error) {
                                console.error('Error:', xhr, status, error);
                                $('[data-action="increment-menu"], [data-action="decrement-menu"]').prop('disabled', false);
                            }
                        });
                        console.log(data);
                    }
                });
            }, 1500); // Ожидание 1500 мс перед отправкой запроса
        }

    });
</script>
