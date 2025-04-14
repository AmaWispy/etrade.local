@php
    $isAuth = auth()->check();
@endphp
<style>
    #viewed-items-carousel .slick-track {
        display: flex !important;
        align-items: stretch !important;
        height: fit-content !important;

    }
</style>
<!-- Viwed Items Desktop Start -->
    <div class="" id="viwed-block-products">
        @include('includes.products.carousel.carousel-header', [
            'nameIcon' => 'bi bi-basket',
            'color' => 'violet-500',
            'title'=> __('template.viewed_items'),
            'id_name'=> 'viewed-items',
            'when'=> __('template.your_recently'),
        ])
        <div id="viewed-items-carousel" x-cloak class="mt-[25px]">

        </div>
    </div>
<!-- Viwed Items Desktop End -->


<script type="module">
    $(document).ready(function(){
        $('#viwed-block-products').removeClass('hidden').addClass('block');
        let data = JSON.parse(localStorage.getItem('viewed_items'));

        if(data !== null || @json($isAuth) === true){
            $.ajax({
                url: `/viewed-items`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                data: {
                    'viewed': data
                },
        
                success: function (response) {
                    if(response.status === 200){
                        console.log(data)
                        console.log(response)
    
                        if(response.is_auth === true){
                            localStorage.removeItem('viewed_items');
                        }
    
                        response.formated_data.forEach(el => {
                            $('#viewed-items-carousel').append(el.html)
                        });
    
                        setTimeout(() => {
                            slickInt()
                        }, 500);
                    }
                },
        
                error:function(xhr, status, error) {
                    console.error('Error:', xhr, status, error);
                }
            })
        } else {
            $('#viwed-block-products').removeClass('block').addClass('hidden');
        }

        function slickInt(){
            $('#viewed-items-carousel').slick({
                slidesToScroll: 1,
                slidesToShow: 4,
                arrows:false,
                responsive: [
                    {
                    breakpoint: 1440,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
                        }
                    },
                    {
                    breakpoint: 1024,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2,
                        }
                    },
                    {
                    breakpoint: 768,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        }
                    },
                ]
            })
    
            $('#viewed-items-prev-btn').click(function() {
                console.log(1)
                $('#viewed-items-carousel').slick('slickPrev');
            });
    
            $('#viewed-items-next-btn').click(function() {
                $('#viewed-items-carousel').slick('slickNext');
            });
        }
    })
</script>