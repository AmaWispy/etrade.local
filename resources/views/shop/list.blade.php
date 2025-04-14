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
        <div class="container">
            <nav class='inline-flex items-center text-sm'>
                <ul class="inline-flex items-center gap-2 text-">
                    <li>
                        <h1 class="font-semibold">{{ __('template.you_are_here') }}</h1>
                    </li>
                    <li>
                        <a class="hover:text-black hover:font-medium" href="{{ \App\Models\Navigation\Menu::getHomePageLink() }}">{{ __('template.home') }}</a>
                    </li>
                    <li>
                        <h1 class="text-neutral-500">/</h1>
                    </li>
                    @if($category->parent)
                        <li>
                            <a class="text-neutral-500 hover:text-black hover:font-medium" href="{{$category->parent->link}}">{{$category->parent->name}}</a>
                        </li>
                        <li>
                            <h1 class="text-neutral-500">/</h1>
                        </li>
                    @endif
                    <li>
                        <h1 class="text-neutral-500">{{ $category->name }}</h1>
                    </li>
                </ul>
            </nav>
        </div>
    <!-- BREADCRUMB AREA END -->

    <!-- PRODUCT DETAILS AREA START -->
        <div class=" lg:container lg:mx-auto" x-data="{filter:{{ session('filter', null) === 'true' ? 'true' : 'false' }}}">
            <!-- Filter Btn toggle Start-->
                <div class="mx-2.5 my-2 flex justify-between items-center">
                    <h1 class="text-4xl font-semibold">{{ $category->name }}</h1>
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
            <!-- Filter Btn toggle End-->

            <div class="">
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