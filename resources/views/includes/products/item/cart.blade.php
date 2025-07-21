<!-- Cart Product Start-->
    <div id="box-cart-item-{{ $item->id }}" class="mb-[20px]">
        <div class="w-full xl:inline hidden">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-10">
                    <!-- Remove Product End-->
                        <div>
                            <button
                                type="button"
                                data-action="remove-cart-item" 
                                data-item="{{$item->id}}" 
                                data-id="{{$item->product->id}}" 
                                class="xl:hover:text-neutral-400 w-fit h-fit px-2 py-1 text-black bg-gray-200 rounded-full text-sm"
                            >
                            <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    <!-- Remove Product End-->

                    <!-- Image product Start-->
                        <div class="!h-20 !w-20">
                            <a href="{{ route('shop.card', ['slug' => $item->product->slug[app()->getLocale()], 'id' => $item->product->id]) }}"  data-id="{{ $item->product->id }}" data-item="{{ $item->id }}"  class="h-full w-full rounded-lg btn-popup">
                                @if(!$item->product->type === \App\Models\Shop\Product::VARIABLE)
                                    <img src="{{$item->variation->getImage()}}" class= "h-full w-full object-cover rounded-lg" alt="{{$item->variation->name}}">
                                @else
                                    <img src="{{$item->product->getImage()}}" class="h-full w-full object-cover rounded-lg" alt="{{$item->product->name}}">
                                @endif
                            </a>
                        </div>
                    <!-- Image product End-->

                    <!-- Name Start-->
                        <div  class="inline-flex flex-col justify-center sm:w-full lg:w-auto ">
                            <div class="!w-full sm:h-full flex justify-between">
                                @if($item->product->type === \App\Models\Shop\Product::VARIABLE)
                                    <a href="{{ route('shop.card', ['slug' => $item->product->slug[app()->getLocale()], 'id' => $item->product->id]) }}" data-id="{{ $item->product->id }}" data-item="{{ $item->id }}" class="btn-popup text-start truncate 2xl:w-80 xl:!w-60 lg:!w-60 sm:!w-40 md:!w-64 hover:text-neutral-500">{{$item->variation->name}}
                                    </a>
                                @else
                                    <a href="{{ route('shop.card', ['slug' => $item->product->slug[app()->getLocale()], 'id' => $item->product->id]) }}" data-id="{{ $item->product->id }}" data-item="{{ $item->id }}" class="btn-popup text-start w-full">
                                        <h4  class="lg:text-lg sm:text-sm  font-semibold !m-0 2xl:w-80 xl:!w-60 lg:!w-60 sm:!w-48 md:!w-64 truncate hover:text-neutral-500">{{$item->product->name}}</h4 >
                                    </a>
                                @endif
                            </div>
                        </div>
                    <!-- Name End-->
                </div>
                <div class="flex items-center justify-between w-[300px]">
                    <!--Price Start --->
                        <div>
                            <h1 class="text-lg">{{ $item->getUnitPrice(false) . ' USD' }}</h1>
                        </div>
                    <!--Price End --->

                    <!-- Qnty Product Start-->
                        <div class="flex gap-2 items-center justify-end">
                            <button type="button" data-action="decrement" class="text-xl text-black bg-gray-200 w-fit h-fit px-[6px] py-[3px] rounded-full flex justify-center items-center text-center xl:hover:bg-blue-500 xl:hover:text-white cursor-pointer"><i class="bi bi-dash-lg text-sm"></i></button>
                            <input
                                type="number" 
                                value="{{$item->qty}}"
                                min="1"
                                data-action="update-cart-item-quantity" 
                                data-item="{{$item->id}}" 
                                class="item-qty-input w-14 !m-0 focus:border-transparent focus:border-white bg-transparent !p-0 text-center border-transparent outline-none ring-0 focus:ring-transparent"
                            />
                            <button type="button" data-action="increment" class="increment text-xl  text-black bg-gray-200 w-fit h-fit px-[6px] py-[3px] rounded-full flex justify-center items-center text-center xl:hover:bg-blue-500 xl:hover:text-white cursor-pointer"><i class="bi bi-plus-lg text-sm"></i></button>
                        </div>
                    <!-- Qnty Product End-->
                </div>
                <!-- Subtotal Product Start-->
                    <div class="w-32 ">
                        <h1 class="item-{{$item->id}}-subtotal2 cart-product-subtotal text-lg">
                            {{$item->getUnitSubtotal(false) . ' USD'}}
                        </h1>
                    </div>
                <!-- Subtotal Product End-->
            </div>
        </div>

        <!-- Mobile Start-->
            <div class="md:container md:mx-auto xl:hidden inline-flex justify-between w-full lg:gap-5 md:gap-3 gap-3">
                <div class="">
                    <!-- Image Start-->
                    <div class="lg:h-28 lg:w-28 md:h-24 md:w-24 h-20 w-20">
                        <a href="{{ route('shop.card', ['slug' => $item->product->slug[app()->getLocale()], 'id' => $item->product->id]) }}" data-id="{{ $item->product->id }}" data-item="{{ $item->id }}" class="h-full w-full rounded-lg btn-popup">
                            @if(!$item->product->type === \App\Models\Shop\Product::VARIABLE)
                                <img src="{{$item->variation->getImage()}}" class= "h-full w-full object-cover rounded-lg" alt="{{$item->variation->name}}">
                            @else
                                <img src="{{$item->product->getImage()}}" class="h-full w-full object-cover rounded-lg" alt="{{$item->product->name}}">
                            @endif
                        </a>
                    </div>
                    <!-- Image End-->
                </div>
                <div class="w-full">
                    <!-- Name and Delete Start-->
                        <ul class="flex justify-between border-b py-3 w-full">
                            <li>
                                @if($item->product->type === \App\Models\Shop\Product::VARIABLE)
                                        <a href="{{ route('shop.card', ['slug' => $item->product->slug[app()->getLocale()], 'id' => $item->product->id]) }}" data-id="{{ $item->product->id }}" data-item="{{ $item->id }}" class="btn-popup text-start truncate 2xl:w-80 xl:!w-60 lg:!w-80 sm:!w-40 md:!w-60 hover:text-neutral-500">{{$item->variation->name}}
                                        </a>
                                    @else
                                        <a href="{{ route('shop.card', ['slug' => $item->product->slug[app()->getLocale()], 'id' => $item->product->id]) }}" data-id="{{ $item->product->id }}" data-item="{{ $item->id }}" class="btn-popup text-start w-full">
                                            <h4  class="lg:text-lg sm:text-sm font-semibold !m-0 2xl:w-80 xl:!w-60 lg:!w-80 sm:!w-40 md:!w-60 truncate hover:text-neutral-500">{{$item->product->name}}</h4 >
                                        </a>
                                    @endif
                            </li>
                            <li>
                                <button
                                type="button"
                                data-action="remove-cart-item" 
                                data-item="{{$item->id}}" 
                                data-id="{{$item->product->id}}" 
                                class="xl:hover:text-neutral-400 w-fit h-fit px-2 py-1 text-black bg-gray-200 rounded-full text-sm"
                                >
                                <i class="bi bi-x-lg"></i>
                                </button>
                            </li>
                        </ul>
                    <!-- Name and Delete End-->

                    <!-- Price Start-->
                        <ul class="flex justify-between border-b py-3">
                            <li>
                                <h1>{{ __('template.price') }}</h1>
                            </li>
                            <li>
                                <h1>{{ $item->getUnitPrice() }}</h1>
                            </li>
                        </ul>
                    <!-- Price End-->

                    <!-- Quantity Start-->
                        <ul class="flex justify-between border-b py-3">
                            <li>
                                <h1>{{ __('template.quantity') }}</h1>
                            </li>
                            <li>
                                <div class="flex gap-2 items-center justify-end">
                                    <button type="button" data-action="decrement" class="text-xl text-black bg-gray-200 w-fit h-fit px-[6px] py-[3px] rounded-full flex justify-center items-center text-center xl:hover:bg-blue-500 xl:hover:text-white cursor-pointer"><i class="bi bi-dash-lg text-sm"></i></button>
                                    <input 
                                        type="text" 
                                        value="{{$item->qty}}"
                                        data-action="update-cart-item-quantity1" 
                                        data-item="{{$item->id}}" 
                                        class="w-14 !m-0 focus:border-transparent focus:border-white bg-transparent !p-0 text-center border-transparent outline-none ring-0 focus:ring-transparent"
                                    />
                                    <button type="button" data-action="increment" class="increment text-xl  text-black bg-gray-200 w-fit h-fit px-[6px] py-[3px] rounded-full flex justify-center items-center text-center xl:hover:bg-blue-500 xl:hover:text-white cursor-pointer"><i class="bi bi-plus-lg text-sm"></i></button>
                                </div>
                            </li>
                        </ul>
                    <!-- Quantity End-->

                    <!-- Subtotal Start-->
                        <ul class="flex justify-between border-b py-3">
                            <li>
                                <h1>{{ __('template.subtotal') }}</h1>
                            </li>
                            <li>
                                <h1 class="item-{{$item->id}}-subtotal2 cart-product-subtotal ">
                                    {{$item->getUnitSubtotal()}}
                                </h1>
                            </li>
                        </ul>
                    <!-- Subtotal End-->
                </div>
            </div>
        <!-- Mobile End-->
    </div>
<!-- Cart Product End-->

