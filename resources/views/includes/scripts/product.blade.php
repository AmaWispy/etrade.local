<script type="module">
    let open = $('#filterBtn').data('bool');

    $('#filterBtn').on('click', function () {
        open = !open

        $.ajax({
            url: `/filter`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            data:{open},

            success: function (response) {
                if(response.status === 200){
                    // console.log(response)
                }
            },

            error:function(xhr, status, error) {
                console.error('Error:', xhr, status, error);
            }
        })
    })

    function initializeSlickSliders() {
        /* 
        * Refresh and initial Slick and LightCase
        */
        $(document).ready(function(){
            $(".ltn__shop-details-large-img").on("init", function(event, slick){
            $(".slick-track").css("display", "flex"); // Выравниваем слайды по центру
            $(".slick-slide").css("display", "flex").css("align-items", "center").css("justify-content", "center");
            }).slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                fade: false,
                cssEase: "linear",
                asNavFor: ".ltn__shop-details-small-img, .images-mobile",
            });
        
            // $('.mobile-item').slick({
            //     dots: false,
            //     arrows: false,
            //     centerMode: false,
            //     centerPadding: '40px',
            //     infinite: true,
            //     speed: 500,
            //     vertical: false,
            //     fade: false,
            //     adaptiveHeight: true,
            //     slidesToShow: 1,
            //     cssEase: 'linear',
            // });

            $(".ltn__shop-details-small-img").slick({
                infinite: false,
                vertical: true,
                slidesToShow: 4,
                slidesToScroll: 1,
                asNavFor: ".ltn__shop-details-large-img",
                dots: false,
                arrows: false,
                focusOnSelect: true,
            });

            $(".images-mobile").slick({
                vertical: false,
                slidesToShow: 5,
                slidesToScroll: 1,
                asNavFor: ".ltn__shop-details-large-img",
                dots: false,
                arrows: false,
                focusOnSelect: true,
                centerMode:false,
            });

            $("a[data-rel^=lightcase]").lightcase({
                transition:
                    "elastic" /* none, fade, fadeInline, elastic, scrollTop, scrollRight, scrollBottom, scrollLeft, scrollHorizontal and scrollVertical */,
                swipe: true,
                maxWidth: 1170,
                maxHeight: 600,
            });
        })
    }


    $(document).on('click', '.btn-popup', function () {
        const $button = $(this),
            productCartItemId = $button.data('item') ?? null,
            productId = $button.data('id'); 

            console.log(productId)
        /* 
        * Qnty update
        */
        $('#quantity').val(1)
        let count = parseInt($('#quantity').val());
        $(document).on('quantityReset', function (){
            count = parseInt($('#quantity').val());
        })

        $('#increment').on('click', function () {
            count++;  
            $('#quantity').val(count);
        });

        $('#decrement').on('click', function () {
            if (count > 0) { 
                count--;  
                $('#quantity').val(count); 
            }
        });

        /* 
        * Product id 
        */
        $.ajax({
            url: `/product/${productId}`,
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                console.log(response)
                /* 
                * Clear
                */
                $('#add-to-cart-btn').removeAttr('data-product data-type');

                $('#quantity').val(1);
                $('#desc-gallery').removeClass(' xl:inline hidden ')

                $('#div-follow').removeAttr('x-bind:class x-data x-on:click');
                $('#div-follow a').removeAttr('data-product data-type');

                if ($(".ltn__shop-details-large-img").hasClass('slick-initialized')) {
                    $(".ltn__shop-details-large-img").slick('unslick');
                }

                if ($('.mobile-item').hasClass('slick-initialized')) {
                    $('.mobile-item').slick('unslick');
                }

                if ($(".ltn__shop-details-small-img").hasClass('slick-initialized')) {
                    $(".ltn__shop-details-small-img").slick('unslick');
                }

                $('.mobile-item, #nav, #view-box, .pre-order, #composition_section').empty()

                $('#add-to-cart-btn').attr('data-product', `${productId}`).attr('data-type', `${response.product.type}`)
                if(response.status === 200){
                    /* 
                    * Add Badeges New\Sale
                    */
                    if(response.product_on_sale !== null){
                        $('#product_on_sale').text(response.product_on_sale)
                        $('#product_on_sale').removeClass('hidden').addClass('inline-flex')
                    } else{
                        $('#product_on_sale').removeClass('inline-flex').addClass('hidden')
                    }
                    
                    if(response.product_is_new !== null){
                        $('#product_is_new').removeClass('hidden').addClass('inline-flex')
                    } else{
                        $('#product_is_new').removeClass('inline-flex').addClass('hidden')
                    }

                    /* 
                    * Add Images Section
                    */
                    if(response.product_images_thumb && response.product_images_thumb_sm){
                        response.product_images_thumb.forEach((el, key) => {
                            $('#view-box').append(`<div class="single-large-img !h-full !w-full rounded-xl overflow-hidden">
                                <a href="${response.product_images_main[key]}" data-rel="lightcase:myCollection" class="!w-full !h-full rounded-xl">
                                    <img src="${el}" class="!h-full !w-full object-cover" alt="${response.product_name}">
                                </a>
                            </div>`)
                        });
    
                        response.product_images_thumb_sm.map(el=> {
                            $('#nav').append(`<div class="2xl:!w-[85px] 2xl:!h-[82px] xl:!h-[70px] xl:!w-[70px] xl:mb-2 rounded-xl">
                                <img src="${el}" class="h-full w-full object-cover rounded-xl" alt="${response.product_name}">
                            </div>`)
                        });
                        console.log("Добавлено в #nav:", $("#nav").children().length);

                    }

                    if(response.product_is_follow !== null){
                        $('#div-follow').attr('x-data', '{follow:true}').attr('x-bind:class', "follow ? 'bg-white text-red-500 hover:text-red-300'  : 'bg-none hover:bg-white text-black hover:text-red-500'").attr('x-on:click', 'follow = !follow')
                        $('#div-follow a').attr('data-product', `${response.product.id}`).attr('data-type', `${response.product.type}`)
                    } else {
                        $('#div-follow').attr('x-data', '{follow:false}').attr('x-bind:class', "follow ? 'bg-white text-red-500 hover:text-red-300'  : 'bg-none hover:bg-white text-black hover:text-red-500'")
                        $('#div-follow a').attr('data-product', `${response.product.id}`).attr('data-type', `${response.product.type}`)
                    }

                    $('#desc-gallery').addClass(' xl:inline hidden ')
                    $('#nav-mobile').addClass(' inline xl:hidden ')

                    $('#product_name').text(response.product_name)
                    $('#product_description').text(response.product_description)
                    $('#product_sku').text(response.product_sku)
                    $('.product_on_sale_price').text(response.product_on_sale_price)

                    initializeSlickSliders()

                    $('#popup').css({
                        'visibility': 'visible',
                        'opacity': '1'
                    });

                }
            },
            error:function(xhr, status, error) {
                console.error('Error:', xhr, status, error);
            }
        });
    })

    // Close popup and clear
    $('.close-btn').on('click', function() {
        $('#desc-gallery').removeClass(' xl:inline hidden ')
        $('#nav-mobile').removeClass(' inline xl:hidden ')

        $('#div-follow').removeAttr('x-bind:class x-data x-on:click');
        $('#div-follow a, #add-to-cart-btn').removeAttr('data-product data-type');

        $('.mobile-item, #nav, #view-box, .pre-order').empty()

        $('#quantity').val(1);
        $('#popup').css({
            'visibility': 'hidden',
            'opacity': '0'
        });

    });

    /* 
    * Composition control
    */
    //Helpers colectData
    function collectData(attr, variable, defaultVariable = false){
        Object.keys(variable).forEach(key => delete variable[key]);
        
        if(defaultVariable){
            $(attr).each(function (index) {
                let id = parseInt($(this).attr('data-id'));
                let qnty = parseInt($(this).attr('data-qnty')) - defaultVariable[index]['Quantity'];
                variable[index] = { id, Quantity: qnty };
            });
        } else {
            $(attr).each(function (index) {
                let id = parseInt($(this).attr('data-id'));
                let qnty = parseInt($(this).attr('data-qnty'));
                variable[index] = { id, Quantity: qnty };
            });
        }
    } 

</script>