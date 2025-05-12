<div class="group w-full !h-fit rounded-lg @if(isset($circle)) !text-center items-center flex flex-col gap-2 @endif" x-data="{open:false, follow:false}">
    <div class="w-full relative flex flex-col gap-1 @if(isset($circle)) !gap-3 @endif">
            <div class="w-full xl:h-[290px] lg:h-[320px] md:h-[330px] overflow-hidden rounded-lg @if(isset($circle)) !rounded-full  xl:!h-[310px] lg:!h-[330px] md:!h-[330px] @endif group">
                <a 
                    href="{{ route('shop.card', ['slug' => $product->slug[app()->getLocale()], 'id' => $product->id]) }}" 
                    data-action="add-viewed-item"
                    data-id = '{{ $product->id }}'
                    data-type = 'product'
                    class="w-full h-full overflow-hidden">
                    <img src="{{ $product->getImages()[0]['medium'] }}" class="w-full h-full duration-300 group-hover:transform group-hover:scale-110 object-cover rounded-lg @if(isset($circle)) !rounded-full @endif" alt="Image {{ $product->name }}">
                </a>
                
                <!-- Follow Add to Cart and show Start-->
                    @if(!isset($circle) && Auth::guard('client')->user())
                        <div class="absolute bottom-9 left-1/2 w-fit -translate-x-1/2 !h-fit opacity-0 group-hover:opacity-100 transition-all duration-300 xl:inline hidden">
                            <ul class="flex items-center gap-1 text-sm h-10">
                                <li class="h-full">
                                    @include('includes.buttons.follow')
                                </li>
                                <li class="text-center">
                                    <a
                                        href="#" 
                                        data-disabled-label="{{__('template.adding')}}"
                                        data-action="add-to-cart"
                                        data-product="{{$product->id}}"
                                        data-type="{{$product->type}}"
                                        title="{{__('template.to_cart')}}" 
                                        class="text-white block bg-florarColor h-10 w-[160px] py-2.5 font-medium rounded-md text-center" 
                                    >{{ __('template.add_to_cart') }}</a>
                                </li>
                                <li class="h-full">
                                    <button x-on:click="open = true; body = true" data-id="{{ $product->id }}" class="btn-popup bg-white !h-full rounded-md w-10"><i class="bi bi-eye"></i></button>
                                </li>
                            </ul>
                        </div>
                    @endif
                <!-- Follow Add to Cart and show End-->
            </div>

        <!-- Sale or New Start -->
            <div class="absolute font-bold top-5 -right-4 flex flex-col gap-2">
                @if($product->isNew())
                    <li class="text-white text-xs !bg-red-500 h-fit w-[75px] px-3 py-1 !rounded-lg !inline-flex items-center justify-center">{{ __('template.new') }}</li>
                @endif
                @if($product->onSale())
                    <li class="text-white text-xs !bg-blue-500 h-fit w-[75px] px-3 py-1 !rounded-lg !inline-flex items-center justify-center">{{$product->getSaleBadge()}}</li>
                @endif
            </div>
        <!-- Sale or New End -->

        <a href="{{ route('shop.card', ['slug' => $product->slug[app()->getLocale()], 'id' => $product->id]) }}" class="xl:hover:text-blue-500 duration-300 font-medium truncate w-[95%]">{{ $product->getTranslatedName() }}</a>
    </div>
    @if($product->onSale())
        <ul class="font-bold flex gap-2 text-xl">
            <li>
                <span>{{$product->default_price}}</span>
            </li>
            <li>
                <del class="text-[12px] text-neutral-400">{{$product->default_price}}</del>
            </li>
        </ul>
    @else
        @if(Auth::guard('client')->user())
            <ul class="font-bold flex gap-2 text-xl">
                <li>
                    <span>{{$product->getExchangedPriceCustom2(true)}}</span>
                </li>
                <li>
                    <span class="text-[12px] text-neutral-400">{{__('template.for_you')}}</span>
                </li>
            </ul>
            <span class="font-bold text-xl">{{$product->getExchangedPriceCustom2(false)}}</span>
        @else
            <span class="font-bold text-xl">{{$product->getExchangedPriceCustom2(false)}}</span>
        @endif
    @endif

    <!-- Follow Add to Cart and show Start-->
        @if(isset($circle)) 
            <div class="w-fit !h-fit">
                <ul class="flex items-center gap-1 text-sm h-10">
                    <li class="h-full">
                        @include('includes.buttons.follow')
                    </li>
                    <li class="text-center">
                        <a  
                        href="{{ Auth::guard('client')->user() ? '#' : route('custom.login') }}"
                        @if(Auth::guard('client')->user())
                            data-disabled-label="{{__('template.adding')}}"
                            data-action="add-to-cart"
                            data-product="{{$product->id}}"
                            data-type="{{$product->type}}"
                        @endif
                        title="{{__('template.to_cart')}}" 
                        class="text-white block bg-florarColor h-10 w-[160px] py-2.5 font-medium rounded-md text-center" 
                        >{{ __('template.add_to_cart') }}</a>
                    </li>
                    <li class="h-full">
                        <button x-on:click="open = true; body = true" data-id="{{ $product->id }}" class="btn-popup bg-white !h-full rounded-md w-10"><i class="bi bi-eye"></i></button>
                    </li>
                </ul>
            </div>
        @endif
    <!-- Follow Add to Cart and show End-->
</div>