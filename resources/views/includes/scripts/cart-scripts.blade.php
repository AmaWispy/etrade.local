<script type="module">
    //Increment decrement
    $(document).ready(function() {
        $('button[data-action="increment"]').off('click').on('click', function(){
            console.log(1)
        var $input = $(this).siblings('input'),
            val = parseInt($input.val());
            $input.val(val + 1).trigger('change');
        });

        $('button[data-action="decrement"]').off('click').on('click', function(){
            var $input = $(this).siblings('input'),
                val = parseInt($input.val());
            if(val > 1){
                $input.val(val - 1).trigger('change');
            }
        });
    });

    /**
     * Sanitize quantity input
     */
    $('input[data-action="update-cart-item-quantity"]').off('input').on('input', function() {
        var inputValue = $(this).val(),
            sanitizedValue = inputValue.replace(/[^0-9]/g, '');

        sanitizedValue = Math.max(1, sanitizedValue);
        $(this).val(sanitizedValue);
    });
    $('input[data-action="update-cart-item-quantity"]').off('change').on('change', function() {
        var $input = $(this),
            item = {
                'item': $input.data('item'),
                'quantity': $input.val() ?? 1
            },
            $controls = $('button[data-action="increment"], button[data-action="decrement"]');

        // Disable quantity controls while ajax in progress
        $input.prop('disabled', true);
        $controls.prop('disabled', true);

        // Perform the AJAX POST request to update item quantity
        $.ajax({
            url: '/cart/update',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            data: item,
            success: function(response) {
                if(response.status === 200){
                    $('[data-counter="cart-total-items"]').text(response.cart.totalItems);
                    $('.cart-total-price').text(response.cart.totalPrice);
                    $('.item-' + response.item.id + '-subtotal2').text(response.item.subtotal);

                    setTimeout(function() {
                        $input.prop('disabled', false);
                        $controls.prop('disabled', false);
                    }, 200);
                }   
            },
            error: function(xhr, status, error) {
                console.error('Error:', xhr, status, error);
                // Handle error response
            }
        });
    });

    $('button[data-action="remove-cart-item"]').off('click').on('click', function(e) {
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
                        
                    } else {
                        if($('#box-cart-item-' + response.item.id).remove() || response.cart.totalItems === 0){
                            setTimeout(()=>{
                                // If cart is empty, render empty cart message
                                $('#cart-box').html(response.empty);

                            }, 500);
                        }
                    } 

                    let parent = $(`#box-check-add-${productId}`);

                    if (parent.find(`.add-rec-${productId}`).length > 0) {
                        setTimeout(() => {
                            $(`.add-rec-${productId}`).removeClass('hidden').addClass('inline-flex')
                            $(`#check-rec-${productId}`).removeClass('inline-flex').addClass('hidden') 
                        }, 500);
                    } 
                    console.log(response.cart.totalPrice)   
                }   
            },
            error: function(xhr, status, error) {
                console.error('Error:', xhr, status, error);
                // Handle error response
            }
        });
    });
</script>
