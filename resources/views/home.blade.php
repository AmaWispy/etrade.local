<x-app-layout>

    @section('title', isset($templateSettings['homepage-meta-title']) ? $templateSettings['homepage-meta-title'] : __('template.home'))

    @section('meta')
        <meta name="description" content="{{$templateSettings['homepage-meta-description']}}">
        <!-- Facebook Open Graph Meta Tags -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{config('app.url')}}">
        <meta property="og:title" content="{{$templateSettings['homepage-meta-title']}}">
        <meta property="og:description" content="{{$templateSettings['homepage-meta-description']}}">
        <meta property="og:image" content="{{$templateSettings['homepage-meta-image']}}">
        <meta property="og:image:alt" content="{{$templateSettings['homepage-meta-title']}}">
    @endsection

    @section('microdata')
        <!-- Schema.org Microdata -->
        <script type="application/ld+json">
            {
                "@context": "http://schema.org",
                "@type": "Organization",
                "name": "{{$templateSettings['company-legal-name']}}",
                "url": "{{config('app.url')}}",
                "logo": "{{$templateSettings['logo']}}",
                "contactPoint": {
                    "@type": "ContactPoint",
                    "telephone": "{{$templateSettings['company-telephone']}}",
                    "contactType": "customer service"
                },
                "sameAs": [
                    "https://www.facebook.com/FlorarMoldova/",
                    "https://www.instagram.com/florar.md/",
                    "https://www.pinterest.com/florarmd/"
                ]
            }
        </script>
    @endsection
    
    <div class="ltn__utilize-overlay"></div>

    <!-- Title Home Start-->
        <div class="container xl:pt-5 sm:pt-2 xl:mb-4 sm:mb-2">
            <h1 class="xl:text-3xl sm:text-[19px] md:text-[26px]  font-semibold">{{ __('template.these_products_are_often_bought') }}</h1>
        </div>
    <!-- Title Home End-->

    <div>
        <!-- Products Grid Home Start-->
            <div>
                @if(isset($oftenBuy))
                    @include('includes.products.grid.home', [
                        'products' => $oftenBuy,
                        'title' => __('template.new_arrivals')
                    ])
                @endif
            </div>
        <!-- Products Grid Home End-->
        
        <!-- Last Section Info For Personal Start-->
            <div class="container flex items-center justify-around my-10 sm:gap-5 xl:gpa-0 xl xl:flex-row sm:flex-col" >
                <!-- Title Start-->
                    <div class="sm:text-center" data-aos='fade-up'>
                        <h1 class="font-bold text-xl">{{ __('template.trust_us') }}</h1>
                    </div>
                <!-- Title End-->
                
                <!-- Info box Title and Image Start-->
                    <div class="flex xl:justify-around sm:justify-between md:justify-around items-center w-full" >
                        <!-- Section 1 info Start-->
                            <div class="xl:border-l 2xl:w-1/4 xl:w-1/2 sm:items-center sm:justify-center xl:pl-5 flex xl:gap-2 sm:gap-5 sm:flex-col xl:flex-row" data-aos='fade-up'>
                                <div class="w-full flex justify-center xl:border-none sm:border-l">
                                    <img src="{{ asset('template/svg/florarIconSvg.svg') }}" alt="Florar icon">
                                </div>
                                <div class="sm:w-20 lg:w-40 xl:w-full text-center">
                                    <h1 class="font-semibold text-3xl pb-1">17</h1>
                                    <p class="lg:text-sm sm:text-[10px]">{{ __("template.years_experience") }}</p>
                                    <a class="lg:text-xs sm:text-[10px]" href="">{{ __('template.view_diploma') }}</a>
                                </div>
                            </div>
                        <!-- Section 1 info End-->

                        <!-- Section 2 info End-->
                            <div class="xl:border-l 2xl:w-1/4 xl:w-1/2 sm:justify-center sm:items-center xl:pl-5 flex xl:gap-2 sm:gap-5 sm:flex-col xl:flex-row" data-aos='fade-up'>
                                <div class="w-full sm:px-7 lg:px-24 xl:p-0 flex justify-center xl:border-none sm:border-x">
                                    <img src="{{ asset('template/svg/gardenWoman.svg') }}" alt="Garden Woman">
                                </div>
                                <div class="sm:w-20 lg:w-40 xl:w-52 text-center">
                                    <h1 class="font-semibold text-3xl pb-1">3</h1>
                                    <p class="lg:text-sm sm:text-[10px]">{{ __('template.florists_with_exquisite_taste') }}</p>
                                </div>
                            </div>
                        <!-- Section 2 info End-->

                        <!-- Section 3 info End-->
                            <div class="xl:border-x 2xl:w-1/4 xl:w-1/2 sm:justify-center sm:items-center xl:px-2 flex xl:gap-2 sm:gap-5 sm:flex-col xl:flex-row" data-aos='fade-up'>
                                <div class="w-full flex justify-center xl:border-none sm:border-r">
                                    <img src="{{ asset('template/svg/curier.svg') }}" alt="Curier">
                                </div>
                                <div class="sm:w-[92px] lg:w-40 xl:w-full text-center">
                                    <h1 class="font-semibold text-3xl pb-1">2</h1>
                                    <p class="lg:text-sm sm:text-[10px]">{{ __('template.couriers_for_your_gift_delivery') }}</p>
                                </div>
                            </div>
                        <!-- Section 3 info End-->
                    </div>
                <!-- Info box Title and Image Start-->
            </div>
        <!-- Last Section Info For Personal End-->
    </div>
    @include('includes.layout.footer.info')
</x-app-layout>