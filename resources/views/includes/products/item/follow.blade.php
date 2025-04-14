@php
    use App\Models\Shop\Follow;
    use Illuminate\Support\Facades\Cookie;
    use App\Models\Shop\FollowItems;
    
    $followCode = Cookie::get('follow');
    $follow = Follow::where('code', $followCode)->first();

    if(Follow::where('code', $followCode)->first()){
        $isFollow =  FollowItems::where('follow_id', $follow['id'])->where('shop_product_id', $product['id'])->first() ?? null;
    } else{
        $isFollow = null;
    }
@endphp

<div x-data="{open:false, follow:false}" x-bind:class=" follow ? 'hidden' : 'inline-flex'" class="justify-between items-center w-full">
    <!-- Desk design Start -->
        <div class="justify-between items-center w-full xl:inline-flex hidden">
            <div class="inline-flex items-center 2xl:gap-6 xl:gap-3 w-full">
                <div 
                    class="bg-neutral-100 rounded-full w-fit h-fit px-2 py-1 "
                    x-on:click="follow = !follow">
                    <a href="#" 
                        data-action="add-to-follow"
                        data-product="{{$product->id}}"
                        data-type="{{$product->type}}"
                        class="sm:text-sm md:text-lg lg:text-xl pt-1 text-gray-500">
                        <i class="bi bi-x"></i>
                    </a>
                </div>
                <div>
                    <img src="{{ $product->getThumb() }}" class="h-20 w-20 object-cover rounded-lg" alt="{{ $product->name }}">
                </div>
                <div class="text-xl 2xl:w-80 text-ellipsis xl:w-72 font-medium  truncate">
                    <h1>{{ $product->name }}</h1>
                </div>
            </div>
            
            <ul class="inline-flex items-center justify-around w-full text-xl text-gray-700">
                @if($product->onSale())
                    <li class="">{{$product->getExchangedPrice(false)}}</li>
                @else
                    <li>{{$product->getExchangedPrice()}}</li>
                @endif
                <li>
                    <h1>{{ __("template.in_stock") }}</h1>
                </li>
            </ul>
            
            <div class="2xl:w-full xl:w-96 flex justify justify-center">
                <a  
                    href="#" 
                    title="{{__('template.to_cart')}}" 
                    data-disabled-label="{{__('template.adding')}}"
                    data-action="add-to-cart"
                    data-product="{{$product->id}}"
                    data-quantity='10'
                    data-type="{{$product->type}}"
                    class="duration-500 h-fit rounded-lg py-2.5 xl:hover:bg-blue-500 xl:hover:text-white font-semibold px-9 border"
                >
                    {{ __('template.to_cart') }}
                </a>
            </div>
        </div>
    <!-- Desk design End -->

    <!-- Mobile design Start-->
        <div class="xl:hidden inline-flex gap-4 w-full">
            <div class="h-20 w-24">
                <img src="{{ $product->getThumb() }}" class="h-full w-full object-cover rounded-lg" alt="{{ $product->name }}">
            </div>
            <div class="w-full">
                <div class="w-full">
                    <div class="flex justify-between items-center w-full border-b py-2">
                        <h1 class="lg:text-xl md:text-lg truncate sm:w-36 md:w-60 lg:w-auto ">{{ $product->name }}</h1>
                        <div 
                        class="bg-neutral-100 rounded-full w-fit h-fit px-2 py-1 "
                        x-on:click="follow = !follow">
                            <a href="#" 
                                data-action="add-to-follow"
                                data-product="{{$product->id}}"
                                data-type="{{$product->type}}"
                                class="sm:text-sm md:text-lg lg:text-xl pt-1 text-gray-500">
                                <i class="bi bi-x"></i>
                            </a>
                        </div>
                    </div>
                    <ul class="flex justify-between w-full border-b py-2 xl:text-xl md:text-lg">
                        <li>
                            <h1>{{ __('template.price') }}:</h1>
                        </li>
                        @if($product->onSale())
                            <li class="text-neutral-500">{{$product->getExchangedPrice(false)}}</li>
                        @else
                            <li class="text-neutral-500">{{$product->getExchangedPrice()}}</li>
                        @endif
                    </ul>

                    <ul class="flex justify-between w-full border-b py-2 xl:text-xl md:text-lg">
                        <li>
                            <h1>{{ __("template.status") }}:</h1>
                        </li>
                        <li>
                            <h1 class="text-neutral-500">{{ __('template.in_stock') }}</h1>
                        </li>
                    </ul>
                </div>
                <div class="mt-2 flex justify-end">
                    <a  
                        href="#" 
                        title="{{__('template.to_cart')}}" 
                        data-disabled-label="{{__('template.adding')}}"
                        data-action="add-to-cart"
                        data-product="{{$product->id}}"
                        data-quantity='10'
                        data-type="{{$product->type}}"
                        class="duration-500 h-fit rounded-lg py-2.5 xl:hover:bg-blue-500 xl:hover:text-white font-semibold px-9 border"
                    >
                        {{ __('template.to_cart') }}
                    </a>
                </div>
            </div>
        </div>
    <!-- Mobile design End-->
</div>
<div class="bg-neutral-200 h-[.5px] my-4 w-full container" x-bind:class="follow ? 'hidden' : 'inline-flex'"></div>