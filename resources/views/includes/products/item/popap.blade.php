<div class="sm:w-full sm:h-full xl:w-auto xl:h-auto justify-center items-center xl:flex hidden" style="z-index: 9999;">
    <div class="flex sm:overflow-y-scroll xl:overflow-y-hidden sm:!max-h-screen sm:w-full xl:!h-auto xl:w-auto sm:flex-col xl:flex-row 2xl:gap-5 xl:gap-2">
        <div class="bg-white duration-[.4s] 2xl:w-[1200px] 2xl:h-[800px] xl:w-[950px] xl:!h-auto sm:!max-h-screen sm:pb-12 sm:pt-8 rounded-lg xl:p-7 md:pb-14 sm:p-1 overflow-y-auto flex flex-col gap-2" style="padding-top: env(safe-area-inset-top);">
            <!-- Close Btn Start -->
            <div class="w-full text-end items-end mt-2 py-2.5">
                <button type="button" data-action="clear-value" class="text-neutral-300 text-3xl close-btn" x-on:click='open = false; body = false'>
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="w-full h-[1px] bg-neutral-300 mb-3"></div>
            <!-- Close Btn End -->

            <div class="flex justify-between w-full gap-7">
                <!-- Gallery show Start -->
                <div class="xl:w-1/2 hidden xl:inline" id="gallery-popup-desk">
                    @include('gallery.layout')
                </div>
                <!-- Gallery show End -->

                <!-- Product Infos Start -->
                <div class="xl:w-1/2 mt-8 xl:mt-0">
                    <ul class="flex flex-col gap-3 border-b pb-2 mb-2">
                        <li>
                            <h1 class="lg:text-3xl md:text-2xl text-xl font-semibold" id="product_name"></h1>
                        </li>
                        <li class="flex gap-2 items-center lg:text-lg text-base font-semibold text-neutral-500">
                            <h1 class="text-black" id="price-on-sale"></h1>
                            <span class="line-through text-base" id="price-default"></span>
                        </li>
                        <li>
                            <div id="rating_product" class="flex items-center gap-1.5">
                                <div id="rating_product_stars"></div>
                                <ul>
                                    <li class="text-neutral-500 flex items-center gap-1">
                                        <h1>{{ '(' }}</h1>
                                        <h1 id="qnty_rating_users"></h1>
                                        <h1>{{ ' ' . __('template.customer_reviews') }}</h1>
                                        <h1>{{ ')' }}</h1>
                                    </li>
                                </ul>
                            </div>
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
                                <li>
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
                                <li>
                                    <ul class="flex items-center gap-2 md:text-sm text-xs">
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
                            <!-- Qnty Select Product Start -->
                            <div class="relative flex items-center">
                                <button type="button" id="decrement" class="text-xl text-black bg-gray-200 w-7 h-7 p-1.5 rounded-full flex justify-center items-center text-center xl:hover:bg-blue-500 xl:hover:text-white cursor-pointer">
                                    <i class="bi bi-dash-lg text-sm"></i>
                                </button>
                                <input id="quantity" data-action="update-item-quantity-show" type="text" autocomplete="off" name="quantity" class="w-14 font-medium !m-0 focus:border-transparent focus:border-white bg-transparent !p-0 text-center border-transparent outline-none ring-0 focus:ring-transparent" value="1" required />
                                <button type="button" id="increment" class="increment text-xl text-black bg-gray-200 w-7 h-7 p-1.5 rounded-full flex justify-center items-center text-center xl:hover:bg-blue-500 xl:hover:text-white cursor-pointer">
                                    <i class="bi bi-plus-lg text-sm"></i>
                                </button>
                            </div>
                            <!-- Qnty Select Product End -->

                            <div class="flex xl:!gap-3 gap-3 w-full items-center">
                                <!-- Add To Cart Btn Start -->
                                <button type="button" data-action="add-to-cart" title="{{__('template.to_cart')}}" data-disabled-label="{{__('template.adding')}}" id="add-to-cart-btn" class="bg-blue-500 text-white rounded-lg py-3 sm:text-xs md:text-sm lg:text-lg w-full font-medium">
                                    {{ __('template.add_to_cart') }}
                                </button>
                                <!-- Add To Cart Btn End -->

                                <!-- Add to Follow Start -->
                                <div class="border border-black rounded-lg py-2.5 px-2">
                                    <div class="text-2xl flex items-center justify-center h-5 w-5 rounded-full p-3" id="div-follow">
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
                <!-- Product Infos End -->
            </div>
        </div>
    </div>
</div>