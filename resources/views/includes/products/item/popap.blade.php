<div class="sm:w-full sm:h-full xl:w-auto xl:h-auto justify-center items-center xl:flex hidden" style="z-index: 9999;">
    <div class="flex sm:overflow-y-scroll xl:overflow-y-hidden sm:!max-h-screen sm:w-full xl:!h-auto xl:w-auto sm:flex-col xl:flex-row 2xl:gap-5 xl:gap-2 ">
        <div class="bg-white duration-[.4s] 2xl:w-[1200px] 2xl:h-[800px] xl:w-[950px] xl:!h-auto sm:!max-h-screen sm:pb-12 sm:pt-8 rounded-lg xl:p-7 md:pb-14 sm:p-1 overflow-y-auto flex flex-col gap-2" style="padding-top: env(safe-area-inset-top);">
            <!-- Close Btn Start-->
                <div class="w-full text-end items-end mt-2">
                    <button type="button" data-action="clear-value" class="text-neutral-300 text-3xl close-btn" x-on:click='open = false; body = false'><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="w-full h-[1px] bg-neutral-300 mb-3"></div>
            <!-- Close Btn End-->

            <div class="flex justify-between w-full gap-7">
                <!-- Gallery show Start-->
                    <div class="xl:w-1/2 hidden xl:inline" id="gallery-popup-desk">
                        @include('gallery.layout')
                    </div> 
                <!-- Gallery show Start-->
                
                <!-- Product Infos Start-->
                    <div class="xl:w-1/2 mt-8 xl:mt-0">
                        <ul class="flex flex-col gap-3 border-b pb-2 mb-2">
                            <li>
                                <h1 class="lg:text-3xl md:text-2xl text-xl font-semibold" id="product_name"></h1>
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
                            <p class="text-neutral-500 lg:text-base text-sm h-44 overflow-y-scroll" id="product_description"></p>
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
                                        data-action="add-to-cart" 
                                        title="{{__('template.to_cart')}}" 
                                        data-disabled-label="{{__('template.adding')}}"
                                        id="add-to-cart-btn"
                                        class="bg-blue-500 text-white rounded-lg py-3 sm:text-xs md:text-sm lg:text-lg w-full font-medium">{{ __('template.add_to_cart') }}</button>
                                    <!-- Add To Cart Btn End-->

                                    <!-- Add to Follow Start -->
                                    <div class="border border-black rounded-lg py-2.5 px-2">
                                        <div class="text-2xl flex items-center justify-center h-5 w-5 rounded-full p-3"
                                            id="div-follow">
                                            <a href="#" data-action="add-to-follow" class="text-xl pt-1">
                                                <i class="bi bi-heart-fill"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <!-- Add to Follow End -->
                                </div>
                            </form>
                                
                        </div>
                    </div>
                <!-- Product Infos End-->
            </div>

            {{-- <div class="xl:w-1/2 w-full px-4 pt-4" style="padding-top: env(safe-area-inset-top, 30px);">
                <div class="flex flex-col gap-3">
                    <!-- Badge Sale or New, Follow and Close Btn Start-->
                        <div class="flex justify-between items-center">
                            <!-- Badge Sale or New Start-->
                                <ul class="flex gap-5">
                                    <li class="text-white !m-0 !bg-red-600 font-semibold px-2 py-1 rounded-lg" id="product_on_sale"></li>
                                    <li class="text-white !bg-green-600 font-semibold px-2 py-1 rounded-lg" id='product_is_new'></li>

                                    @if(request()->path() === 'cart/view')
                                        <ul class="pre-order "></ul>
                                    @endif
                                </ul>
                            <!-- Badge Sale or New Start-->

                            <!-- Follow and Mobile Close Btn Start-->
                                <div>
                                    <ul class="flex gap-3">
                                        <li>
                                            <div class="text-2xl flex items-center justify-center h-5 w-5 rounded-full p-3"
                                                id="div-follow">
                                                <a href="#" data-action="add-to-follow" class="text-xl pt-1">
                                                    <i class="bi bi-heart-fill"></i>
                                                </a>
                                            </div>
                                        </li>
                                        <li>
                                            <button type="button" class="text-neutral-300 text-2xl inline xl:hidden close-btn" x-on:click='open = false; body = false'><i class="bi bi-x-lg"></i></button>
                                        </li>
                                    </ul>
                                </div>
                            <!-- Follow and Close Btn Start-->
                        </div>
                    <!-- Badge Sale or New, Follow and Close Btn Start-->

                    <!-- Mobile Gallery Start-->
                        <div class="inline xl:hidden w-auto h-auto">
                            @include('gallery.layout')
                        </div>
                    <!-- Mobile Gallery End-->

                    <!-- Box Price, Name, Sku and Price Start-->
                        <div class="border-b pb-3 flex justify-between items-end">
                            <!-- Sku and Name Start-->
                                <div class="md:w-1/2 lg:w-full">
                                    <p class="lg:text-sm sm:text-xs text-neutral-600" id="product_sku"></p>
                                    <h1 class="lg:text-2xl sm:text-sm md:text-lg font-semibold" id="product_name"></h1>
                                </div>
                            <!-- Sku and Name End-->

                            <!-- Mobile Price Start-->
                                <h1 class="flex items-center font-semibold gap-2 text-center sm:w-1/2 sm:inline xl:hidden" >
                                    <span class="lg:text-3xl md:text-xl sm:text-sm product_on_sale_price" ></span>
                                </h1>
                            <!-- Mobile Price Start-->
                        </div>
                    <!-- Box Price, Name, Sku and Price Start-->
                    @if (request()->path() !== 'cart/view')
                        <div class="flex flex-col gap-3 border-b pb-20">
                            <!-- Pre order Start-->
                                <ul class="pre-order "></ul>
                            <!-- Pre order End-->

                            <!-- Price Start-->
                                <h1 class="flex items-center font-semibold gap-2 sm:hidden xl:inline" >
                                    <span class="text-3xl product_on_sale_price"></span>
                                </h1>
                            <!-- Price End-->

                            <!-- Form Qnty and Add To Cart Btn Start-->
                                <div>
                                    <h1 class="sm:pb-2 lg:pb-0">{{ __('template.quantity') }}</h1>
                                    <div>
                                        <form class="flex justify-between gap-3">
                                            <!-- Qnty Select Product Start-->
                                                <div class="relative flex items-center w-60">
                                                    <button type="button" class="bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-s-lg lg:p-3 sm:p-2 h-11 flex items-center" id="decrement">
                                                        <i class="bi bi-dash-lg"></i>
                                                    </button>
                                                    <input id="quantity" data-action="update-item-quantity-show" type="number" autocomplete="off" name="quantity" class="bg-gray-50 border-x-0 border-gray-300 h-11 text-center text-gray-900 text-sm block w-full py-2.5" value="1" required />
                                                    <button type="button" class="bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-e-lg lg:p-3 sm:p-2 h-11 flex items-center" id="increment">
                                                        <i class="bi bi-plus-lg"></i>
                                                    </button>
                                                </div>
                                            <!-- Qnty Select Product Start-->

                                            <!-- Add To Cart Btn Start-->
                                                <button  
                                                type="button"
                                                data-action="add-to-cart" 
                                                title="{{__('template.to_cart')}}" 
                                                data-disabled-label="{{__('template.adding')}}"
                                                id="add-to-cart-btn"
                                                class="bg-black text-white w-full rounded-lg py-2 sm:text-xs lg:text-lg">{{ __('template.to_cart') }}</button>
                                            <!-- Add To Cart Btn End-->
                                        </form>
                                    </div>
                                </div>
                            <!-- Form Qnty and Add To Cart Btn End-->
                        </div>
                    @endif

                    <!-- Composition List and Warning Banner Start-->
                        <div class="flex flex-col gap-2 border-b pb-20">
                            <!-- Composition Start-->
                                <div id="composition_section" class="flex items-center gap-2 "></div> 
                                <ul id="product_composition_block"></ul> 
                            <!-- Composition Start-->

                            <!-- Warning banner Start-->
                                @include('includes.warning.check-flowe-availability')
                            <!-- Warning banner End-->
                        </div>
                    <!-- Composition List and Warning Banner End-->

                    <!-- Payments Section Start-->
                    <div class="h-5">
                        @include('includes.links.payments-accept')
                    </div>
                    <!-- Payments Section End-->
                </div>
            </div>
        </div> --}}
    </div>
</div>