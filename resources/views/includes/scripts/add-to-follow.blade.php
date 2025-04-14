<script type="module">
    $(document).ready(function(){
    /**
     * Add to follow
     */
        $('[data-action="add-to-follow"]').on('click', function(e){
            e.preventDefault();
            var $button = $(this),
            followItem = {
                'product': $button.data('product'),
                'quantity': 1
            },
            type = $button.data('type'),
            disabledLabel = $button.data('disabled-label'),
            defaultLabel = $button.html();
    
            if(type === "{{\App\Models\Shop\Product::VARIABLE}}"){
                if($button.data('variation') !== undefined){
                    followItem.variation = $button.attr('data-variation');
                } else {
                    console.log('Variation should be selected!');
                    return false;
                }
            }
                    
            $button.prop('disabled', true); // Disable the button
            $button.html(disabledLabel); // Change button label
    
            // Perform the AJAX POST request
            $.ajax({
                url: '/follow/add',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                data: followItem,
                success: function(response) {
                    console.log(response)
                    if(response.status === 200){
                        setTimeout(function() {
                            $('[data-counter="follow-total-items"]').html(response.follow.totalItems);
                            $button.prop('disabled', false); // Enable the button
                            $button.html(defaultLabel); // Restore the original button label
                        }, 1000);
                    }   
                },
                error: function(xhr, status, error) {
                    console.error('Error:', xhr, status, error);
                    // Handle error response
                }
            });
    
        });
    })    
    </script>