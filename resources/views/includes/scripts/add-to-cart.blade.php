<script type="module">
    $(document).ready(function(){
        /**
         * Add to cart
         */
        $(document).on('click', '[data-action="add-to-cart"]', function(e){
            e.preventDefault();
            let $button = $(this),
                $qtyInput = $('input[name="quantity"]'),
                cartItem = {
                    'product': $button.data('product'),
                    'quantity': $qtyInput.val() ?? 1
                },
                type = $button.data('type'),
                disabledLabel = $button.data('disabled-label'),
                defaultLabel = $button.html(),
                productId = $button.data('product');

            $button.prop('disabled', true); // Disable the button
            $button.html(disabledLabel); // Change button label

            console.log($button)
            console.log(productId)
            // Perform the AJAX POST request

            $.ajax({
                url: '/cart/add',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                data: {cartItem},
                success: function(response) {
                    console.log(response)
                    if(response.status === 200){
                        console.log(response)
                        $('[data-counter="cart-total-items"]').html(response.cart.totalItems);
                        $('[data-counter="cart-total-price"]').html(response.cart.totalPrice);
                        $('.cart-total-price').html(response.cart.totalPrice);
                        $('#box-cart-items').html(response.html);
                        $('.cart-count-indicator').html(response.cart.totalItems);

                        let parent = $(`#box-check-add-${productId}`);

                        if(productId && $("#btn-rec")){
                            if (parent.find(`.add-rec-${productId}`).length > 0) {
                                setTimeout(() => {
                                    $(`.add-rec-${productId}`).removeClass('inline-flex').addClass('hidden')
                                    $(`#check-rec-${productId}`).removeClass('hidden').addClass('inline-flex') 
                                }, 500);
                            }
                        }

                        $('#quantity').val(1)
                        $('#quantity').trigger('quantityReset')
                        setTimeout(function() {
                            $button.prop('disabled', false); // Enable the button
                            $button.html(defaultLabel); // Restore the original button label
                        }, 1000);
                        console.log(response.cart.totalPrice)
                    }   
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error('Error:', xhr, status, error);
                }
            });

        });
    })    
</script>