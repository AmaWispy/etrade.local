<div class="w-full h-fit rounded-lg" x-data="{open:false, follow:false}">
    <div class="w-full relative flex flex-col gap-1">
            <div class="w-full h-[290px] overflow-hidden rounded-lg group">
                <a href="{{ route('shop.card', ['slug' => $product->slug, 'id' => $product->id]) }}" class="w-full h-full overflow-hidden">
                    <img src="{{ $product->getThumb() }}" class="w-full h-full object-cover rounded-lg" alt="Image {{ $product->name }}">
                </a>
                
                <!-- Follow Add to Cart and show Start-->
                    <div class="absolute bottom-9 left-1/2 w-fit -translate-x-1/2 !h-fit opacity-0 group-hover:opacity-100 transition-all duration-300 xl:inline hidden">
                        <ul class="flex items-center gap-1 text-sm">
                            <li>
                                @include('includes.buttons.follow')
                            </li>
                            <li class="text-center">
                                <a  
                                @guest
                                    href="{{ route('register') }}"
                                @endguest
                                @auth
                                    href="#" 
                                    data-disabled-label="{{__('template.adding')}}"
                                    data-action="add-to-cart"
                                    data-product="{{$product->id}}"
                                    data-type="{{$product->type}}"
                                @endauth
                                title="{{__('template.to_cart')}}" 
                                class="text-white block bg-florarColor h-10 w-[160px] py-2.5 font-medium rounded-md text-center" 
                                >{{ __('template.add_to_cart') }}</a>
                            </li>
                            <li>
                                <button x-on:click="open = true; body = true" data-id="{{ $product->id }}" class="btn-popup bg-white py-2 px-[12px] rounded-md"><i class="bi bi-eye"></i></button>
                            </li>
                        </ul>
                    </div>
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

        <a href="{{ route('shop.card', ['slug' => $product->slug, 'id' => $product->id]) }}" class="text-neutral-400 xl:hover:text-blue-500 font-medium truncate w-[95%]">{{ $product->name }}</a>
    </div>
    @if($product->onSale())
        <ul class="font-bold flex gap-2 text-xl">
            <li>
                <span>{{$product->getExchangedPrice(false)}}</span>
            </li>
            <li>
                <del class="text-[12px] text-neutral-400">{{$product->getExchangedPrice(true)}}</del>
            </li>
        </ul>
    @else
        <span class="font-bold text-xl">{{$product->getExchangedPrice()}}</span>
    @endif
</div>