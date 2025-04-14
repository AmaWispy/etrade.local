<x-app-layout>
    @section('title', $page->title)
    
    <!-- BREADCRUMB AREA START -->
        <div class="container mb-4">
            <nav class='inline-flex items-center text-sm'>
                <ul class="inline-flex items-center gap-2 ">
                    <li>
                        <h1 class="font-semibold">{{ __('template.you_are_here') }}</h1>
                    </li>
                    <li>
                        <a href="{{ \App\Models\Navigation\Menu::getHomePageLink() }}">{{ __('template.home') }}</a>
                    </li>
                    <li>
                        <h1 class="text-neutral-500">/</h1>
                    </li>
                    <li>
                        <h1 class="text-neutral-500">{{ $page->title }}</h1>
                    </li>
                </ul>
            </nav>
        </div>
    <!-- BREADCRUMB AREA END -->

    <!-- Delivery AREA START -->
        <div class="lg:container lg:mx-auto">
            <h1 class="text-3xl font-semibold">{{ __("template.delivery") }}</h1>
            
            <!-- Delivery select Start-->
                <div class="mt-3 2xl:px-40 xl:px-32 lg:px-10 sm:px-5 border rounded-lg xl:py-20 sm:py-10 !border-florarColor flex flex-col gap-2">
                    <h1 class="font-semibold text-xl">{{ __('template.city_select') }}</h1>
                    <div class="w-full flex flex-col gap-2">
                        <div class="flex gap-3 xl:flex-row flex-col">
                            <div class="w-full">
                                <select   
                                    id="locality"
                                    class="select-input checkout-data nice-select w-full bg-neutral-100 !m-0 w-full">
                                    <option data-null='NONE'>{{__('template.city')}}</option>
                                    @foreach(App\Models\City::get() as $city)
                                        <option
                                            data-code = '{{ $city->code }}'
                                            @if(App\Models\Shop\ShippingMethod::where('code', 'curier')->where('by_distance', 1)->first())
                                                data-city='{{ $city->getTranslation('name', 'en')}}'
                                            @else
                                                value="{{$city->code}}"
                                            @endif 
                                        >
                                            {{$city->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-full">
                                <select   
                                    id="raion"
                                    disabled
                                    class="select-input checkout-data nice-select w-full bg-neutral-100 !m-0 w-full">
                                    <option data-null='NONE'>{{__('template.district')}}</option>
                                    @foreach(App\Models\Shop\ShippingZone::whereNotNull('area')->get() as $raion)
                                        <option 
                                            data-raion="{{$raion->code}}"
                                        >
                                            {{$raion->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h1 class="w-full bg-black text-white py-2.5 px-4 text-lg flex gap-2  rounded-lg text-start"><i class="bi bi-info-circle-fill"></i>{{ __('template.shipping_cost') }} <span class="shipping-cost"></span></h1>
                    </div>
                </div>
            <!-- Delivery select End-->

            <!-- Delivery Content Start-->
                <div class="mt-3">
                    {!! $page->content !!}
                </div>
            <!-- Delivery Content End-->
        </div>
    <!-- Delivery AREA END -->
@include('includes.scripts.delivery')
</x-app-layout>