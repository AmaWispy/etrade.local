<script type="module">
    $(document).ready(function(){
        const skeleton = `
            <div role="status" class="space-y-8 animate-pulse md:space-y-0 md:space-x-8 w-full rtl:space-x-reverse md:flex md:items-center mb-3">
                <div class="flex items-center justify-center w-32 h-[85px] bg-gray-300 rounded-lg dark:bg-gray-700">
                    <svg class="w-10 h-10 text-gray-200 dark:text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                        <path d="M18 0H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-5.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm4.376 10.481A1 1 0 0 1 16 15H4a1 1 0 0 1-.895-1.447l3.5-7A1 1 0 0 1 7.468 6a.965.965 0 0 1 .9.5l2.775 4.757 1.546-1.887a1 1 0 0 1 1.618.1l2.541 4a1 1 0 0 1 .028 1.011Z"/>
                    </svg>
                </div>
                <div class="w-full">
                    <div class="h-2.5 bg-gray-200 rounded-full dark:bg-gray-700 w-32 mb-2"></div>
                    <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 max-w-[480px] mb-2.5"></div>
                    <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 mb-2.5"></div>
                    <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 w-32 mb-2.5"></div>
                </div>
                <span class="sr-only">Loading...</span>
            </div>`

        $('body').on('click', '#cart', function(){
            console.log(123)
            $.ajax({
                url: `/cart/show`,
                method: 'GET',
                beforeSend: function () {
                    $('#cart_menu').html(''); 
                    for (let i = 0; i < 3; i++) {
                        $('#cart_menu').append(skeleton); 
                    }
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    console.log(response);
                    $('#cart_menu').text('');
                    if(response.status === 200 && Array.isArray(response.products_info)){
                        response['products_info'].forEach(product => {
                            $('#cart_menu').append(`
                                <div class="flex lg:justify-between lg:items-center flex-col lg:flex-row" id="box-cart-item-${product['id']}">
                                    <div class="flex items-center gap-3">
                                        <div class="relative border h-24 w-24 rounded-lg">
                                            <a href="${product['link']}" class="h-full w-full">
                                                <img src="${product['image']}" class="h-full w-full object-cover rounded-lg" alt="${product['name']}">
                                            </a>
                                            <div class="absolute -top-2 -left-2 bg-white p-1 rounded-full">
                                                <button 
                                                type="button"
                                                data-action="remove-cart-item" 
                                                data-item="${product['id']}" 
                                                data-id="${product['product_id']}"
                                                class="bg-gray-200 h-7 w-7 p-1.5 rounded-full text-sm font-bold">
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
                                                <a href="${product['link']}" class="font-semibold truncate lg:w-40 md:w-52 w-40">${product['name']}</a>
                                            </li>
                                            <li>
                                                <h1 class="item-${product['id']}-subtotal font-semibold">${product['price']}</h1>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="flex gap-2 items-center justify-end">
                                        <button type="button" data-action="decrement-menu" class="text-xl text-black bg-gray-200 h-6 w-6 p-1.5 rounded-full flex justify-center items-center text-center xl:hover:bg-blue-500 xl:hover:text-white cursor-pointer">
                                            <i class="bi bi-dash-lg text-sm"></i>
                                        </button>
                                        <input 
                                            type="text" 
                                            value="${product['qty']}"
                                            data-action="update-cart-item-quantity" 
                                            data-item="${product['id']}"
                                            class="w-14 !m-0 focus:border-transparent focus:border-white bg-transparent !p-0 text-center border-transparent outline-none ring-0 focus:ring-transparent"
                                        />
                                        <button type="button" data-action="increment-menu" class="increment text-xl  text-black bg-gray-200 h-6 w-6 p-1.5 rounded-full flex justify-center items-center text-center xl:hover:bg-blue-500 xl:hover:text-white cursor-pointer">
                                            <i class="bi bi-plus-lg text-sm"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="h-[.5px] bg-gray-200 rounded-xl w-full my-5 box-cart-item-${product['id']}"></div>
                            `);
                        });
                    }
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
            
            timeout = setTimeout(function() {
                $('[data-action="increment-menu"], [data-action="decrement-menu"]').prop('disabled', true);
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
                                $('[data-action="increment-menu"], [data-action="decrement-menu"]').prop('disabled', false);
                                
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