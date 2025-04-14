<x-app-layout>
    @section('title', __('template.favorite_products') )

    <!-- Favorite products AREA START -->
        @if(null !== $follow && $follow->items->count() > 0)
            <div class="xl:container xl:mx-auto xl:px-0 px-3">
                <h1 class="text-2xl font-semibold my-4">{{ __("template.my_wish_list") }}</h1>
                <div class="w-full text-xl text-ellipsis font-medium mb-3 xl:inline-flex hidden">
                    <div class="2xl:px-16 px-12 ">
                        <h1 class="text-xl text-ellipsis font-medium">{{ __('template.product') }}</h1>
                    </div>
                    <div class="2xl:mx-[21.5rem] xl:ml-[19.5rem]">
                        <ul class="flex xl:gap-9 2xl:gap-[4rem]">
                            <li>
                                <h1>{{ __('template.unit_price') }}</h1>
                            </li>
                            <li>
                                <h1>{{ __('template.stock_status') }}</h1>
                            </li>
                        </ul>
                    </div>
                </div>
                <div>
                    <div>
                        @foreach($follow->items as $follow)
                            @include('includes.products.item.follow', ['product' => $follow->product])
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            @include('includes.empty-cart')
        @endif
    <!-- Favorite products AREA END -->
</x-app-layout>