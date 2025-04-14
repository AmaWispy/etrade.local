<style>
    table tr:nth-child(odd) {
        background-color: white; /* белый цвет */
    }

    table tr:nth-child(even) {
        background-color: #f4f2f2; /* Темно-серый цвет */
    }
</style>
<x-app-layout class="xl:mx-0 mx-4">

    @section('title', __('template.shop') . ' - ' . $product->name)

    <div class="mt-16" x-data='{follow:false}'>
        <!-- Card Show Start-->
            <div class="container flex xl:flex-row flex-col 2xl:gap-3 xl:gap-5 justify-between">
                <!-- Images Show Start -->
                    @include('gallery.card',[
                        'images' => $product->getMedia("product-images"),
                    ])
                <!-- Images Show End -->

                <!-- Product Infos Start-->
                    <div class="xl:w-1/2 mt-8 xl:mt-0">
                        <ul class="flex flex-col gap-3 border-b pb-2 mb-2">
                            <li>
                                <h1 class="lg:text-3xl md:text-2xl text-xl font-semibold">{{ $product->name }}</h1>
                            </li>
                            <li>
                                <h1 class="font-medium lg:text-xl md:text-lg text-base">$155-$200</h1>
                            </li>
                            <li>
                                <ul class="flex gap-1">
                                    <li><i class="bi bi-star"></i></li>
                                    <li><i class="bi bi-star"></i></li>
                                    <li><i class="bi bi-star"></i></li>
                                    <li><i class="bi bi-star"></i></li>
                                    <li><i class="bi bi-star"></i> <span class="text-neutral-500">(0 {{ __('template.customer_reviews') }})</span></li>
                                </ul>
                            </li>
                        </ul>

                        <ul class="text-blue-500 flex flex-col gap-1 lg:text-lg md:text-base text-sm font-medium my-3">
                            <li class="flex gap-2 items-center">
                                <i class="bi bi-check-lg"></i>
                                <h1>{{ __('template.in_stock_v2') }}</h1>
                            </li>
                            <li class="flex gap-2 items-center">
                                <i class="bi bi-check-lg"></i>
                                <h1>{{ __('template.free_delivery_available') }}</h1>
                            </li>
                            <li class="flex gap-2 items-center">
                                <i class="bi bi-check-lg"></i>
                                <h1>{{ __('template.sales') . ' 30% ' . __('template.off_use_code') . ' MOTIVE30 ' }}</h1>
                            </li>
                        </ul>

                        <div>
                            <p class="text-neutral-500 lg:text-base text-sm">{{ $product->description }}</p>
                        </div>

                        <div class="flex flex-col gap-3 mt-3">
                            <!-- Filters Start -->
                                <div class="flex flex-col gap-2">
                                    <ul class="flex justify-between !items-center lg:gap-5 gap-4 w-52">
                                        <li>
                                            <h1 class="lg:text-2xl md:text-xl text-lg font-medium">{{ __('template.colors') }}:</h1>
                                        </li>
                                        <li >
                                            <ul class="flex items-center gap-2 mt-2">
                                                <li>
                                                    <button class="h-5 w-5 rounded-full bg-red-300 border-[2px] border-blue-500"></button>
                                                </li>
                                                <li>
                                                    <button class="h-5 w-5 rounded-full bg-green-500 border-[2px]"></button>
                                                </li>
                                                <li>
                                                    <button class="h-5 w-5 rounded-full bg-violet-500 border-[2px]"></button>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>

                                    <ul class="flex justify-between !items-center lg:gap-5 gap-4 w-52">
                                        <li>
                                            <h1 class="lg:text-2xl md:text-xl text-lg font-medium">{{ __('template.size') }}:</h1>
                                        </li>
                                        <li >
                                            <ul class="flex items-center gap-2  md:text-sm text-xs">
                                                <li>
                                                    <button class="rounded-full h-10 w-10 border-[2px] border-neutral-300 text-neutral-500">XS</button>
                                                </li>
                                                <li>
                                                    <button class="rounded-full h-10 w-10 border-[2px] border-neutral-300 text-neutral-500">S</button>
                                                </li>
                                                <li>
                                                    <button class="rounded-full h-10 w-10 border-[2px] border-blue-500 text-neutral-500">M</button>
                                                </li>
                                                <li>
                                                    <button class="rounded-full h-10 w-10 border-[2px] border-neutral-300 text-neutral-500">L</button>
                                                </li>
                                                <li>
                                                    <button class="rounded-full h-10 w-10 border-[2px] border-neutral-300 text-neutral-500">XL</button>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            <!-- Filters End -->
                            <form class="flex md:items-center md:flex-row flex-col items-center md:gap-5 gap-3 mt-3 md:mt-0">
                                <!-- Qnty Select Product Start-->
                                    <div class="relative flex items-center">
                                        <button type="button" id="decrement" class="text-xl text-black bg-gray-200 w-fit h-fit px-[6px] py-[4px] rounded-full flex justify-center items-center text-center xl:hover:bg-blue-500 xl:hover:text-white cursor-pointer"><i class="bi bi-dash-lg text-sm"></i></button>
                                        <input id="quantity" data-action="update-item-quantity-show" type="text" autocomplete="off" name="quantity" class="w-14 font-medium !m-0 focus:border-transparent focus:border-white bg-transparent !p-0 text-center border-transparent outline-none ring-0 focus:ring-transparent" value="1" required />
                                        <button type="button" id="increment" class="increment text-xl  text-black bg-gray-200 w-fit h-fit px-[6px] py-[3px] rounded-full flex justify-center items-center text-center xl:hover:bg-blue-500 xl:hover:text-white cursor-pointer"><i class="bi bi-plus-lg text-sm"></i></button>
                                    </div>
                                <!-- Qnty Select Product Start-->

                                <div class="flex xl:!gap-3 gap-3 w-full items-center">
                                    <!-- Add To Cart Btn Start-->
                                        <button  
                                        type="button"
                                        data-product='{{ $product->id }}'
                                        data-type='{{ $product->type }}'
                                        data-action="add-to-cart" 
                                        title="{{__('template.to_cart')}}" 
                                        data-disabled-label="{{__('template.adding')}}"
                                        id="add-to-cart"
                                        class="bg-blue-500 text-white rounded-lg py-3 sm:text-xs md:text-sm lg:text-lg w-full font-medium">{{ __('template.add_to_cart') }}</button>
                                    <!-- Add To Cart Btn End-->
    
                                    <!-- Add to Follow Start -->
                                    <div class="border border-black rounded-lg py-2.5 px-2">
                                        @include('includes.buttons.follow')
                                    </div>
                                    <!-- Add to Follow End -->
                                </div>
                            </form>
                                
                        </div>
                    </div>
                <!-- Product Infos End-->
            </div>
        <!-- Card Show End-->

        <!-- Description, Information, Reviews Start -->
        <div class="bg-orange-50 mt-10 py-12" x-data="{description:true, additional_information:false, reviews:false }">
            <div class="container flex flex-col gap-5">
                <!-- Navigate with Inf Start-->
                    <ul class="flex gap-10 lg:text-2xl md:text-xl text-lg font-medium lg:flex-row flex-col items-center">
                        <li>
                            <button class="pb-1" x-on:click='description = true; additional_information = false; reviews = false' x-bind:class="description ? 'text-blue-500 border-b-2 border-b-blue-500 ' : 'text-neutral-400' ">{{ __('template.description') }}</button>
                        </li>
                        <li>
                            <button class="pb-1" x-on:click='description = false; additional_information = true; reviews = false' x-bind:class="additional_information ? 'text-blue-500 border-b-2 border-b-blue-500' : 'text-neutral-400' ">{{ __('template.additional_information') }}</button>
                        </li>
                        <li>
                            <button class="pb-1" x-on:click='description = false; additional_information = false; reviews = true' x-bind:class="reviews ? 'text-blue-500 border-b-2 border-b-blue-500' : 'text-neutral-400' ">{{ __('template.reviews') }}</button>
                        </li>
                    </ul>
                <!-- Navigate with Inf End-->

                <!-- Description Start -->
                    <div x-show='description' x-cloak class="flex justify-between gap-5 flex-col lg:flex-row">
                        <ul class="lg:w-1/2 w-full flex flex-col gap-2">
                            <li>
                                <h1 class="lg:text-xl md:text-lg text-base font-medium">{{ __('template.specifications') }}</h1>
                            </li>
                            <li>
                                <p class="text-neutral-500 md:text-sm text-xs">Lorem ipsum dolor sit amet consectetur adipisicing elit. Vel neque, vitae qui aliquid necessitatibus dolores accusamus tenetur totam reprehenderit quas saepe id culpa, sunt eos! Dicta voluptates excepturi repellendus qui.</p>
                            </li>
                        </ul>
                        
                        <ul class="lg:w-1/2 w-full flex flex-col gap-2">
                            <li>
                                <h1 class="lg:text-xl md:text-lg text-base font-medium">{{ __('template.care_maintenance') }}</h1>
                            </li>
                            <li>
                                <p class="text-neutral-500 md:text-sm text-xs">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Beatae, odit quo amet modi nesciunt recusandae?</p>
                            </li>
                        </ul>
                    </div>
                <!-- Description End -->

                <!-- Additional Information Start -->
                    <div x-show='additional_information' x-cloak class="bg-white rounded-xl xl:px-16 lg:px-10 md:px-5 py-10">
                        <table class="w-full md:text-base text-sm font-medium rounded-lg">
                            <tr class="">
                                <td class="py-2 px-3 w-1/2">{{ __('template.size') }}</td>
                                <td class="text-neutral-500 w-1/2">S,M,L,XL</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-3 w-1/2">Lorem, ipsum.</td>
                                <td class="text-neutral-500 w-1/2">35″L x 24″W x 37-45″H(front to back wheel)</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-3 w-1/2">Lorem, ipsum dolor.</td>
                                <td class="text-neutral-500 w-1/2">24</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-3 w-1/2">Lorem, ipsum.</td>
                                <td class="text-neutral-500 w-1/2">60 LBS</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-3 w-1/2">Lorem ipsum dolor sit.</td>
                                <td class="text-neutral-500 w-1/2">37-45″</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-3 w-1/2">{{ __('template.size') }}</td>
                                <td class="text-neutral-500 w-1/2">S,M,L,XL</td>
                            </tr>
                        </table>
                    </div>
                <!-- Additional Information End -->

                <!-- Reviews Start -->
                    <div x-show='reviews' x-cloak class="flex gap-20 lg:flex-row flex-col-reverse">
                        <div class="lg:w-1/2 flex flex-col gap-4">
                            <h1 class="text-xl font-bold">3 {{ __('template.review_for_this_product') }}</h1>
                            <!-- Users comment Start -->
                                <div class="flex flex-col gap-4">
                                    <div class="flex gap-2">
                                        <div class="rounded-full !w-[70px] !h-16">
                                            <img src="{{ asset('template/images/user-template.png') }}" class="h-full w-full rounded-full object-cover" alt="User Nick Name">
                                        </div>
                                        <ul class="flex flex-col gap-2 w-full">
                                            <div class="flex justify-between">
                                                <li>
                                                    <h1 class="text-xl font-medium">Eleanor Pena</h1>
                                                </li>
                                                <li>
                                                    <ul class="flex gap-1">
                                                        <li><i class="bi bi-star"></i></li>
                                                        <li><i class="bi bi-star"></i></li>
                                                        <li><i class="bi bi-star"></i></li>
                                                        <li><i class="bi bi-star"></i></li>
                                                        <li><i class="bi bi-star"></i></li>
                                                    </ul>
                                                </li>
                                            </div>
                                            <li>
                                                <p class="text-neutral-500">Lorem ipsum dolor sit amet consectetur adipisicing elit. Tenetur harum eveniet doloremque? Eos deserunt cumque atque praesentium omnis nihil, quia deleniti necessitatibus tempore neque, facilis nulla ad. Provident recusandae.</p>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="flex gap-2">
                                        <div class="rounded-full !w-[70px] !h-16">
                                            <img src="{{ asset('template/images/user-template.png') }}" class="h-full w-full rounded-full object-cover" alt="User Nick Name">
                                        </div>
                                        <ul class="flex flex-col gap-2 w-full">
                                            <div class="flex justify-between">
                                                <li>
                                                    <h1 class="text-xl font-medium">Eleanor Pena</h1>
                                                </li>
                                                <li>
                                                    <ul class="flex gap-1">
                                                        <li><i class="bi bi-star"></i></li>
                                                        <li><i class="bi bi-star"></i></li>
                                                        <li><i class="bi bi-star"></i></li>
                                                        <li><i class="bi bi-star"></i></li>
                                                        <li><i class="bi bi-star"></i></li>
                                                    </ul>
                                                </li>
                                            </div>
                                            <li>
                                                <p class="text-neutral-500">Lorem ipsum dolor sit amet consectetur adipisicing elit. Tenetur harum eveniet doloremque? Eos deserunt cumque atque praesentium omnis nihil, quia deleniti necessitatibus tempore neque, facilis nulla ad. Provident recusandae voluptatum rerum repudiandae pariatur eius distinctio laborum reiciendis quis natus. Sint esse corporis asperiores nihil! Doloremque veritatis at obcaecati rerum eum.</p>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="flex gap-2">
                                        <div class="rounded-full !w-[70px] !h-16">
                                            <img src="{{ asset('template/images/user-template.png') }}" class="h-full w-full rounded-full object-cover" alt="User Nick Name">
                                        </div>
                                        <ul class="flex flex-col gap-2 w-full">
                                            <div class="flex justify-between">
                                                <li>
                                                    <h1 class="text-xl font-medium">Eleanor Pena</h1>
                                                </li>
                                                <li>
                                                    <ul class="flex gap-1">
                                                        <li><i class="bi bi-star"></i></li>
                                                        <li><i class="bi bi-star"></i></li>
                                                        <li><i class="bi bi-star"></i></li>
                                                        <li><i class="bi bi-star"></i></li>
                                                        <li><i class="bi bi-star"></i></li>
                                                    </ul>
                                                </li>
                                            </div>
                                            <li>
                                                <p class="text-neutral-500">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum doloribus voluptatem repellat hic tenetur, odit officiis magni, consequuntur ipsam aperiam labore numquam ab! Itaque dolores voluptatibus excepturi! Consequatur quam alias doloribus similique reiciendis, ab delectus debitis facilis quae vero officiis voluptate blanditiis, id laborum magnam itaque veniam nesciunt magni accusantium et. Atque sequi delectus corrupti voluptatibus saepe officiis asperiores omnis.</p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            <!-- Users comment End -->
                        </div>

                        <div class="lg:w-1/2 w-full flex flex-col gap-4">
                            <ul class="flex flex-col gap-3">
                                <li>
                                    <h1 class="text-xl font-semibold">{{ __('template.add_a_review') }}</h1>
                                </li>
                                <li>
                                    <p class="text-neutral-500">{{ __('template.your_email_address_will_not_be_published') }}</p>
                                </li>
                            </ul>

                            <!-- Send Review Start -->
                                <form action='' class="flex flex-col gap-3" x-cloak x-data='{star1:false, star2:false, star3:false, star4:false, star5:false,}'>
                                    <ul class="flex gap-4">
                                        <li>
                                            <h1>{{ __('template.your_rating') }}<span class="text-red-500">*</span></h1>
                                        </li>
                                        <div class="flex gap-2">
                                            <li>
                                                <button type="button" x-on:click='star1 = true; star2 = false; star3 = false; star4 = false; star5 = false;'>
                                                    <i x-show='!star1' class="bi bi-star"></i>
                                                    <i x-show='star1' class="bi bi-star-fill text-yellow-500"></i>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" x-on:click='star1 = true; star2 = true; star3 = false; star4 = false; star5 = false;'>
                                                    <i x-show='!star2' class="bi bi-star"></i>
                                                    <i x-show='star2' class="bi bi-star-fill text-yellow-500"></i>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" x-on:click='star1 = true; star2 = true; star3 = true; star4 = false; star5 = false;'>
                                                    <i x-show='!star3' class="bi bi-star"></i>
                                                    <i x-show='star3' class="bi bi-star-fill text-yellow-500"></i>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" x-on:click='star1 = true; star2 = true; star3 = true; star4 = true; star5 = false;'>
                                                    <i x-show='!star4' class="bi bi-star"></i>
                                                    <i x-show='star4' class="bi bi-star-fill text-yellow-500"></i>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" x-on:click='star1 = true; star2 = true; star3 = true; star4 = true; star5 = true;'>
                                                    <i x-show='!star5' class="bi bi-star"></i>
                                                    <i x-show='star5' class="bi bi-star-fill text-yellow-500"></i>
                                                </button>
                                            </li>
                                        </div>
                                    </ul>

                                    <label for="notes" class="relative w-full">
                                        <span class="text-neutral-500 absolute bg-orange-50 -top-2.5 left-4 text-sm">{{ __('template.notes') }}</span>
                                        <textarea type="text" id="notes" name="notes" class="w-full border !border-neutral-300 rounded-md h-60 resize-none bg-transparent" placeholder="{{ __('template.order_notes') }}"></textarea>
                                    </label>

                                    <div class="flex xl:justify-between xl:gap-0 lg:flex-row flex-col gap-3">
                                        <label for="first_name" class="relative w-full">
                                            <span class="text-neutral-500 absolute bg-orange-50 -top-2.5 left-4 text-sm">{{ __('template.first_name') }} <span class="text-red-500">*</span></span>
                                            <input type="text" id="first_name" name="first_name" required class="w-full border !border-neutral-300 bg-transparent rounded-md h-14" placeholder="Adam">
                                        </label>
                                        <label for="last_name" class="relative w-full">
                                            <span class="text-neutral-500 absolute bg-orange-50 -top-2.5 left-4 text-sm">{{ __('template.last_name') }} <span class="text-red-500">*</span></span>
                                            <input type="text" id="last_name" name="last_name" required class="w-full border !border-neutral-300 bg-transparent rounded-md h-14" placeholder="John">
                                        </label>
                                    </div>
                                    <button class="py-3 text-lg rounded-lg font-semibold lg:w-fit w-full px-10 bg-blue-500 xl:hover:bg-blue-600 text-white">{{ __('template.submit_comment') }}</button>
                                </form>
                            <!-- Send Review End -->
                        </div>
                    </div>
                <!-- Reviews End -->
            </div>

            <!-- Icons Start -->
                <div x-show='description' class="container flex lg:flex-row flex-col gap-5 lg:items-center mt-12">
                    <div class="flex flex-col gap-2">
                        <div class="flex justify-center items-center rounded-full bg-white w-16 h-16">
                            <img src="{{ asset('template/images/service3.png') }}" class="w-8 h-8" alt="{{ __('template.easy_returns') }}">
                        </div>
                        <h1 class="text-xl font-semibold">{{ __('template.easy_returns') }}</h1>
                    </div>
                    <div class="flex flex-col gap-2">
                        <div class="flex justify-center items-center rounded-full bg-white w-16 h-16">
                            <img src="{{ asset('template/images/service2.png') }}" class="w-[29px] h-8" alt="{{ __('template.quality_service') }}">
                        </div>
                        <h1 class="text-xl font-semibold">{{ __('template.quality_service') }}</h1>
                    </div>
                    <div class="flex flex-col gap-2">
                        <div class="flex justify-center items-center rounded-full bg-white w-16 h-16">
                            <img src="{{ asset('template/images/service1.png') }}" class="w-8 h-8" alt="{{ __('template.easy_returns') }}">
                        </div>
                        <h1 class="text-xl font-semibold">{{ __('template.easy_returns') }}</h1>
                    </div>
                </div>
            <!-- Icons End -->
        </div>
        <!-- Description, Information, Reviews End -->
    </div>
</x-app-layout>
<script type="module">
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
</script>