<x-app-layout>

    @section('title', __('template.shop'))

    <!-- BREADCRUMB AREA START -->
        <div class="container mb-4">
            <nav class='inline-flex items-center text-sm'>
                <ul class="inline-flex items-center gap-2 ">
                    <li>
                        <h1 class="font-semibold">{{ __('template.you_are_here') }}</h1>
                    </li>
                    <li>
                        <a class="hover:text-black hover:font-medium" href="{{ \App\Models\Navigation\Menu::getHomePageLink() }}">{{ __('template.home') }}</a>
                    </li>
                    <li>
                        <h1 class="text-neutral-500">/</h1>
                    </li>
                    <li>
                        <h1 class="text-neutral-500">{{__('template.shop')}}</h1>
                    </li>
                </ul>
            </nav>
        </div>
    <!-- BREADCRUMB AREA END -->

    <!-- PRODUCT DETAILS AREA START -->
    <div class=" lg:container lg:mx-auto">
        <div class="" x-data="{filter:{{ session('filter', null) === 'true' ? 'true' : 'false' }}}">
            <!-- Filter Product Start-->
                <div class="mx-2.5 my-2 flex justify-between items-center">
                    <h1 class="text-4xl font-semibold">{{__('template.shop')}}</h1>
                    <div>
                        <button id="filterBtn" x-cloak 
                            data-bool='{{ session('filter', null) === 'true' ? true : false }}' 
                            x-on:click='filter = !filter' 
                            class="flex items-center gap-2 text-black text-sm">
                            <span x-show='filter'>{{ __('template.hide_filter') }}</span>
                            <span x-show='!filter'>{{ __('template.filter') }}</span>
                            <i class="bi bi-sliders"></i></button>
                    </div>
                </div>
            
                <div x-show='filter' x-cloak >
                    @include('includes.products.filter.layout')
                </div>
            <!-- Filter Product Emd-->

            <!-- Products Start-->
                <div >
                    @include('includes.products.grid.default', [
                        'product' => $products    
                    ])
                </div>
            <!-- Products End-->

            @if($products->hasPages() > 0)
                <div class="inline lg:hidden">
                    {!! $products->links('pagination.default') !!}
                </div>
                <div class="hidden lg:inline xl:hidden">
                    {!! $products->onEachSide(4)->links('pagination.default') !!}
                </div>
                <div class="hidden xl:inline">
                    {!! $products->onEachSide(5)->links('pagination.default') !!}
                </div>
            @endif
        </div>
    </div>
    <!-- PRODUCT DETAILS AREA END -->
        
</x-app-layout>