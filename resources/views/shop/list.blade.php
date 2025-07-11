<x-app-layout>

    @section('title', __('template.shop') . ' - ' . $category->name)

    @section('meta')
        <meta name="description" content="{{strip_tags($category->description)}}">
        <!-- Facebook Open Graph Meta Tags -->
        <meta property="og:type" content="website">
        <meta property="og:title" content="{{$category->name}}">
        <meta property="og:description" content="{{strip_tags($category->description)}}">
        <meta property="og:image" content="{{$category->getImage()}}">
        <meta property="og:url" content="{{config('app.url')}}{{$category->link}}">
    @endsection

    @section('microdata')
    <!-- Schema.org Microdata -->
    <script type="application/ld+json">
        {
            "@context": "http://schema.org",
            "@type": "ItemList",
            "name": "{{$category->name}}",
            "description": "{{strip_tags($category->description)}}",
            "itemListElement": [
                @foreach($products as $product)
                {
                    "@type": "ListItem",
                    "position": {{ $loop->index + 1 }},
                    "item": {
                        "@type": "Product",
                        "name": "{{$product->name}}",
                        "description": "{{strip_tags($product->description)}}",
                        "image": "{{$product->getImage()}}",
                        "offers": {
                            "@type": "Offer",
                            "priceCurrency": "MDL",
                            "price": "{{$product->getPrice()}}",
                            "availability": "http://schema.org/InStock"
                        }
                    }
                }@if(!$loop->last),@endif
                @endforeach
            ]
        }
    </script>
    @endsection
    
    <!-- BREADCRUMB AREA START -->
        <div class="bg-neutral-100 py-5 mb-5">
            <div class="container">
                <!-- Modern Breadcrumbs Navigation -->
                <nav class='flex items-center text-sm mb-4 bg-white px-4 py-2 rounded-lg shadow-sm border max-w-fit'>
                    <div class="flex items-center flex-wrap gap-1">
                        <a href="{{ \App\Models\Navigation\Menu::getHomePageLink() }}" class="text-gray-600 hover:text-blue-600 transition-colors duration-200 font-medium flex items-center">
                            <i class="bi bi-house-door mr-1"></i>{{ __('template.home') }}
                        </a>
                        
                        @if($category->parent)
                            <i class="bi bi-chevron-right text-gray-400 text-xs mx-1"></i>
                            <a href="{{$category->parent->link}}" class="text-gray-600 hover:text-blue-600 transition-colors duration-200 font-medium truncate max-w-[120px] sm:max-w-none" title="{{$category->parent->name}}">
                                {{$category->parent->name}}
                            </a>
                        @endif
                        
                        <i class="bi bi-chevron-right text-gray-400 text-xs mx-1"></i>
                        <span class="text-blue-600 font-semibold truncate max-w-[120px] sm:max-w-none" title="{{ $category->name }}">
                            {{ $category->name }}
                        </span>
                    </div>
                </nav>
            </div>
        </div>
    <!-- BREADCRUMB AREA END -->

    <!-- PRODUCT DETAILS AREA START -->
        <div class=" lg:container lg:mx-auto z-0" x-data="{filter:{{ session('filter', null) === 'true' ? 'true' : 'false' }}}">
            <!-- Filter Btn toggle Start-->
                <div class="mx-2.5 my-2 flex justify-between items-center">
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-semibold text-gray-800">{{ $category->name }}</h1>
                    <div>
                        <button id="filterBtn" x-cloak 
                            data-bool='{{ session('filter', null) === 'true' ? true : false }}' 
                            x-on:click='filter = !filter' 
                            class="flex items-center gap-2 text-gray-600 hover:text-blue-600 transition-colors duration-200 text-sm font-medium bg-white px-3 py-2 rounded-lg border shadow-sm">
                            <span x-show='filter'>{{ __('template.hide_filter') }}</span>
                            <span x-show='!filter'>{{ __('template.filter') }}</span>
                            <i class="bi bi-sliders"></i>
                        </button>
                    </div>
                </div>
            <!-- Filter Btn toggle End-->

            <div class="z-0">
                <!-- Filter Product Start-->
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