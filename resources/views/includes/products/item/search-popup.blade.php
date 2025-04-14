<div x-show='searchOpen' class="fixed !inset-0 bg-black/50  !w-full !h-full flex items-center justify-center flex-col" x-cloak>
    <div class="relative 2xl:h-[700px] 2xl:w-[900px] xl:w-[750px] xl:h-[650px] lg:w-[700px] lg:h-[600px] w-full px-3 lg:mx-0 h-[650px]  flex gap-3">
        <div class="bg-white w-full h-full flex flex-col gap-3 rounded-xl p-4">
            <!-- Close Popup Mobile Start -->
            <div class="xl:hidden  flex items-center justify-end">
                <button x-on:click='searchOpen = !searchOpen; body = !body' type="button" class="duration-300 bg-white h-10 w-11 rounded-xl hover:!bg-blue-500 hover:!text-white border"><i class="bi bi-x-lg h-full w-full"></i></button>
            </div>
            <!-- Close Popup Mobile End -->
            
            <!-- Search Start -->
                <form id="product-search" class="w-full mt-1.5 mb-0 h-12 flex items-center relative ">   
                    @csrf
                    <div class="absolute inset-y start-0 flex items-center ps-3 pointer-events-none w-full h-full">
                        <i class="bi bi-search "></i>
                    </div>
                    <input type="text" name="product-search" class="block w-full h-full ps-10 text-lg font-medium text-neutral-500 border border-gray-300 rounded-lg bg-white focus:ring-blue-400 focus:border-blue-400" placeholder="{{ __('template.what_are_you_looking_for') }}"/>
                </form>
            <!-- Search End -->

            <!-- Founded Info Start -->
                <ul class="font-semibold text-neutral-500 md:text-base text-sm flex w-full justify-between items-center border-b border-neutral-300 pb-2">
                    <li class="flex gap-1.5 items-center">    
                        <h1 id="results_count">0</h1>
                        <span>{{ __('template.result_found') }}</span>
                    </li>
                    <li class="group">
                        <a href="#" class="hover:!text-black">{{ __('template.view_all') }}</a>
                        <div class="h-[2px] group-hover:w-full group-hover:opacity-100 duration-500  bg-black w-1 opacity-0"></div>
                    </li>
                </ul>
            <!-- Founded Info End -->

            <!-- Products views Start -->
                <div class="flex w-full h-fit  mt-2 rounded-lg p-2.5 flex-col gap-2" id="result-products">
                    <ul class="flex items-center gap-1 h-24 text-center justify-center text-neutral-400 text-base font-semibold">
                        <li>
                            <i class="bi bi-emoji-frown"></i>
                        </li>
                        <li>
                            <h1>{{ __('template.empty') }}...</h1>
                        </li>
                    </ul>
                </div>
            <!-- Products views End -->
        </div>
        <!-- Close Popup Start -->
            <div>
                <button x-on:click='searchOpen = !searchOpen; body = !body' type="button" class="duration-300 bg-white !h-10 !w-10 rounded-full hover:!bg-blue-500 hover:!text-white xl:block hidden"><i class="bi bi-x-lg h-full w-full"></i></button>
            </div>
        <!-- Close Popup End -->
    </div>
</div> 

<script type="module">
    $(document).ready(function(){
        $('#product-search').submit(function(e){
            e.preventDefault()

            let data = $(this).find('input[name="product-search"]').val();

            $.ajax({
                url: `/product/search`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    $('#result-products').html(''); 
                    for (let i = 0; i < 2; i++) {
                        $('#result-products').append(`
                            <div role="status" class="space-y-8 animate-pulse md:space-y-0 md:space-x-8 rtl:space-x-reverse md:flex md:items-center mb-2">
                                <div class="flex items-center justify-center bg-gray-300 rounded-lg aspect-square lg:w-44 md:w-40 w-28 lg:mb-0 md:mb-10 mb-16 dark:bg-gray-700">
                                    <svg class="w-10 h-10 text-gray-200 dark:text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                                        <path d="M18 0H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-5.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm4.376 10.481A1 1 0 0 1 16 15H4a1 1 0 0 1-.895-1.447l3.5-7A1 1 0 0 1 7.468 6a.965.965 0 0 1 .9.5l2.775 4.757 1.546-1.887a1 1 0 0 1 1.618.1l2.541 4a1 1 0 0 1 .028 1.011Z"/>
                                    </svg>
                                </div>
                                <div class="w-full">
                                    <div class="h-2.5 bg-gray-200 rounded-full dark:bg-gray-700 w-48 mb-4"></div>
                                    <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 max-w-[480px] mb-2.5"></div>
                                    <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 max-w-[440px] mb-2.5"></div>
                                    <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 max-w-[460px] mb-2.5"></div>
                                    <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 max-w-[360px]"></div>
                                </div>
                                <span class="sr-only">Loading...</span>
                            </div>
                        `); 
                        
                    }
                },
                dataType: 'json',
                data: {
                    'search': data
                },
    
                success: function (response) {
                    if(response.status === 200){
                        $('#result-products').empty(); 

                        console.log(response)
                        console.log(response.products)
                        $("#results_count").text(response.results)
                        
                        if(response.products !== null){
                            response.products.forEach(product => { 
                                $('#result-products').append(`
                                    <div class="border border-neutral-300 h-44 w-full rounded-lg p-3.5 flex lg:items-center md:gap-4 gap-2">
                                        <div class="aspect-square lg:w-44 md:w-40 w-28 lg:mb-0 md:mb-10 mb-16 rounded-lg overflow-hidden">
                                            <a href="${product.view_link}" x-on:click='searchOpen = !searchOpen' class='w-full h-full cursor-pointer'>
                                                <img src="${product.image}" alt="${product.name}" class="object-cover rounded-lg h-full w-full duration-300 group-hover:transform group-hover:scale-110">
                                            </a>
                                        </div>
                                        <div class="flex lg:items-center justify-between w-full lg:flex-row lg:gap-0 flex-col gap-3">
                                            <ul class="space-y-2 w-full">
                                                <li>
                                                    <h1 class="flex gap-1 lg:text-sm text-xs">
                                                        <i class="bi bi-star"></i>
                                                        <i class="bi bi-star"></i>
                                                        <i class="bi bi-star"></i>
                                                        <i class="bi bi-star"></i>
                                                        <i class="bi bi-star"></i>
                                                        <span class="text-neutral-500 flex gap-[3px]">100+ <span class="md:block hidden">{{ __('template.reviews') }}</span></span> 
                                                    </h1>
                                                </li>
                                                <li class="xl:w-96 lg:w-[350px] md:w-[90%] w-32 truncate">
                                                    <a href="${product.view_link}" x-on:click='searchOpen = !searchOpen' class="lg:text-xl md:text-lg text-base truncate !w-full font-semibold text-neutral-500">${product.name}</a>
                                                </li>
                                                <li class="flex gap-2 items-center lg:text-lg text-base font-semibold text-neutral-500">
                                                    <h1 class="text-black">${product.on_sale === true ? product.price_on_sale : product.price_default}</h1>
                                                    <span class="line-through text-base">${product.on_sale === true ? product.price_default : ''}</span>
                                                </li>
                                            </ul>
                                            <ul class="lg:space-y-3 lg:space-x-0 space-x-3 lg:!flex-col flex-row items-center inline-flex ">
                                                <li>
                                                    <button type="button" class="h-10 w-10 border rounded-lg border-neutral-300 duration-300 hover:bg-blue-500 hover:text-white">
                                                        <i class="bi bi-cart2"></i>
                                                    </button>
                                                </li>
                                                <li >
                                                    <button type="button" class="h-10 w-10 rounded-lg border border-neutral-500 duration-300 hover:bg-blue-500 hover:text-white">
                                                        <i class="bi bi-heart"></i>
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                `)
                            });
                        } else {
                            $('#result-products').append(`
                                <ul class="flex items-center gap-1 h-24 text-center justify-center text-neutral-400 text-base font-semibold">
                                    <li>
                                        <i class="bi bi-emoji-frown"></i>
                                    </li>
                                    <li>
                                        <h1>{{ __('template.empty') }}...</h1>
                                    </li>
                                </ul>
                            `)
                        }
                    }
                },
    
                error:function(xhr, status, error) {
                    console.error('Error:', xhr, status, error);
                }
            });
        })
    })
</script>