<style>
     /* slick-track на всю высоту и горизонтальный флекс */
    #header-recomendated-products .slick-track, #our_products .slick-track, #feedback-carousel .slick-track, #new-arrivals-2 .slick-track {
        display: flex !important;
        align-items: stretch !important;
        height: 100% !important;
    }

    #new-arrivals-2 .slick-track {
        height: fit-content !important;
    }
    #feedback-carousel .slick-current {
        padding-top: 40px; 
    }

    /* каждый слайд тянется по высоте контейнера */
    #header-recomendated-products .slick-slide, #our_products .slick-slide, #feedback-carousel .slick-slide, #new-arrivals-2 .slick-slide{
        height: 100% !important;
        display: flex !important;
        align-items: center;
        justify-content: center;
    }

    /* убираем баг с дублирующимися слайдами */
    #header-recomendated-products .slick-slide > div, #our_products .slick-slide > div, #feedback-carousel .slick-slide > div, #new-arrivals-2 .slick-slide > div {
        width: 100%;
        height: 100%;
    }

    .bubble {
        position: relative;
        background: #fff;
        font-family: Arial;
        filter: drop-shadow(1px 1px 10px #5757572c);
        border-radius: 10px;
    }
    .bubble:after {
        content: '';
        position: absolute;
        display: block;
        width: 0;
        z-index: 1;
        border-style: solid;
        border-color: #fff transparent;
        border-width: 20px 20px 0;
        bottom: -20px;
        left: 26%;
        margin-left: -20px;
    }
</style>

@php
    // Header
        $recomendationProductsFirst = [
            [
                'category' => 'SmartWatch',
                'category_icons' => 'bi bi-watch',
                'name' => 'Bloosom Smart Watch',
                'link' => '#',
                'image' => asset('template/images/smartwatch.png'),
            ],
            [
                'category' => 'SmartWatch',
                'category_icons' => 'bi bi-watch',
                'name' => 'Bloosom Smart Watch',
                'link' => '#',
                'image' => asset('template/images/smartwatch.png'),
            ],
        ];

        $recomendationProductsSecond = [
            'name' => 'Yantiti Leather Bag',
            'price' => '29.99$',
            'link' => '#',
            'image' => asset('template/images/bag.png'),
        ];
    //

    // Under recomendation 
        $blockMicroInfo = [
            [
                'name' => __("template.fast_secure_delivery"),
                'image' => asset('template/images/service1.png'),
            ],
            [
                'name' => __("template.product_guarantee"),
                'image' => asset('template/images/service2.png'),
            ],
            [
                'name' => __("template.return_policy_24h"),
                'image' => asset('template/images/service3.png'),
            ],
            [
                'name' => __("template.next_level_quality"),
                'image' => asset('template/images/service5.png'),
            ],
        ];
    //

    // Arrivals Sellers Carousel
        $carouselsArrivalsSellers = [
            [
                'id_name' => 'arrivals-carousel',
                'title' => __('template.new_arrivals'),
                'when' => __('template.this_weeks'),
                'color' => 'violet-500',
            ],
            [
                'id_name' => 'best-sellers',
                'title' => __('template.best_sellers'),
                'when' => __('template.this_month'),
                'color' => 'florarColor',
            ],
        ];
    //

    // Timer Data 
        $timerData = [
            [
                'id_name' => 'days',
                'name' => __('template.day'),
            ],
            [
                'id_name' => 'hrs',
                'name' => __('template.hrs'),
            ],
            [
                'id_name' => 'min',
                'name' => __('template.min'),
            ],
            [
                'id_name' => 'sec',
                'name' => __('template.sec'),
            ],
        ];

    //

    // Products Explore Our Data 
        $productsExploreOur = [
            $products->slice(0, 8)->values()->all(), $products->slice(8, 8)->values()->all()
        ];
    //

    // Users feedback
        $userFeedback = [
            [
                'content' => '“ It’s amazing how much easier it has been to meet new people and create instantly non connections. I have the exact same personal the only thing that has changed is my mind set and a few behaviors. “',
                'image' => asset('template/images/feedback-01.webp'),
                'role' => 'Head Of Idea',
                'name' => 'James C. Andriesi'
            ],
            [
                'content' => '“ It’s amazing how much easier it has been to meet new people and create instantly non connections. I have the exact same personal the only thing that has changed is my mind set and a few behaviors. “',
                'image' => asset('template/images/feedback-02.jpg'),
                'role' => 'Head Of Idea',
                'name' => 'James C. Andriesi'
            ],
            [
                'content' => '“ It’s amazing how much easier it has been to meet new people and create instantly non connections. I have the exact same personal the only thing that has changed is my mind set and a few behaviors. “',
                'image' => asset('template/images/feedback-03.jpg'),
                'role' => 'Head Of Idea',
                'name' => 'James C. Andriesi'
            ],
            [
                'content' => '“ It’s amazing how much easier it has been to meet new people and create instantly non connections. I have the exact same personal the only thing that has changed is my mind set and a few behaviors. “',
                'image' => asset('template/images/feedback-01.webp'),
                'role' => 'Head Of Idea',
                'name' => 'James C. Andriesi'
            ],
        ]
    //
@endphp

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

    <div class="container p-0 flex flex-col 2xl:gap-4 gap-5">
        <!-- Categories and recomendated products Start -->
            <div class="flex items-center gap-4" x-cloak style="z-index: -1">
                <!-- Categories Start -->
                    <div class="h-auto 2xl:block hidden">
                        <ul class="w-52 h-auto flex flex-col font-medium  text-base text-neutral-400">
                            <!-- <li class="flex items-center relative group">
                                <button class="border-t mx-4 w-full border-t-neutral-200 h-12 flex items-center justify-between xl:hover:text-black xl:group-hover:text-black">
                                    <span class="flex gap-3 items-center">
                                        <i class="bi bi-sunglasses text-blue-400 xl:group-hover:text-black"></i> 
                                        Fashion 
                                    </span>
                                    <span class="duration-300 rotate-0 group-hover:rotate-180"><i class="bi bi-chevron-up text-sm"></i></span>
                                </button>
                                <div class="absolute top-0 left-full opacity-0 scale-95 pointer-events-none group-hover:opacity-100 group-hover:scale-100 group-hover:pointer-events-auto 
                                    transition-all duration-200 bg-white border-b border-r shadow-sm p-4 rounded-br-lg z-10 flex items-center w-[1000px]">
                                    <div class="columns-4 border-r-2 border-r-neutral-200 w-2/3">
                                        <ul class="gap-1 break-inside-avoid mb-3 space-y-2">
                                            <li><h1 class="text-lg text-black font-medium">Example bla </h1></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                        </ul>
                                        <ul class="gap-1 break-inside-avoid mb-3 space-y-2">
                                            <li><h1 class="text-lg text-black font-medium">Example test</h1></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                        </ul>
                                        <ul class="gap-1 break-inside-avoid mb-3 space-y-2">
                                            <li><h1 class="text-lg text-black font-medium">Example</h1></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                        </ul>
                                        <ul class="gap-1 break-inside-avoid mb-3 space-y-2">
                                            <li><h1 class="text-lg text-black font-medium">Example</h1></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                        </ul>
                                        <ul class="gap-1 break-inside-avoid mb-3 space-y-2">
                                            <li><h1 class="text-lg text-black font-medium">Example</h1></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                        </ul>
                                        <ul class="gap-1 break-inside-avoid mb-3 space-y-2">
                                            <li><h1 class="text-lg text-black font-medium">Example</h1></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                        </ul>
                                        <ul class="gap-1 break-inside-avoid mb-3 space-y-2">
                                            <li><h1 class="text-lg text-black font-medium">Example</h1></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                        </ul>
                                        <ul class="gap-1 break-inside-avoid mb-3 space-y-2">
                                            <li><h1 class="text-lg text-black font-medium">Example</h1></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                            <li class="text-neutral-400"><a href="">example</a></li>
                                        </ul>
                                    </div>

                                    <div class="w-2/5 m-4 h-fit flex flex-col gap-2">
                                        <h1 class="text-xl font-semibold text-black">{{ __('template.featured') }}</h1>
                                        <div class="grid grid-cols-2 grid-rows-3 gap-2">
                                            <div class="col-span-2 w-full h-28 overflow-hidden">
                                                <a href="#" class="h-full w-full">
                                                    <img src="{{ asset('https://letsenhance.io/static/73136da51c245e80edc6ccfe44888a99/1015f/MainBefore.jpg') }}" class="border h-full w-full object-cover transition-transform duration-300 hover:scale-105" alt="Template">
                                                </a>
                                            </div>
                                            <div class="col-span-2 w-full h-28 overflow-hidden">
                                                <a href="" class="h-full w-full">
                                                    <img src="{{ asset('https://letsenhance.io/static/73136da51c245e80edc6ccfe44888a99/1015f/MainBefore.jpg') }}" class="border h-full w-full object-cover transition-transform duration-300 hover:scale-105" alt="Template">
                                                </a>
                                            </div>
                                            <div class="w-full h-28 overflow-hidden">
                                                <a href="#" class="h-full w-full">
                                                    <img src="{{ asset('https://letsenhance.io/static/73136da51c245e80edc6ccfe44888a99/1015f/MainBefore.jpg') }}" class="border h-full w-full object-cover transition-transform duration-300 hover:scale-105" alt="Template">
                                                </a>
                                            </div>
                                            <div class="w-full h-28 overflow-hidden">
                                                <a href="#" class="h-full w-full">
                                                    <img src="{{ asset('https://letsenhance.io/static/73136da51c245e80edc6ccfe44888a99/1015f/MainBefore.jpg') }}" class="border h-full w-full object-cover transition-transform duration-300 hover:scale-105" alt="Template">
                                                </a>
                                            </div>
                                        </div>
                                        <a href="" class="flex justify-center items-center !w-full text-center  transition-transform duration-300 hover:scale-105 bg-blue-500 text-white rounded-lg h-14 font-semibold text-lg">{{ __('template.see_all_offers') }}</a>
                                    </div>
                                </div>
                            </li>
                            <li class="flex items-center ">
                                <a href="#" class="border-t mx-4 w-full border-t-neutral-200 h-12 flex items-center gap-3 xl:hover:text-black"><i class="bi bi-pc-display text-blue-400"></i> Electronics</a>
                            </li>
                            <li class="flex items-center">
                                <a href="#" class="border-t mx-4 w-full border-t-neutral-200 h-12 flex items-center gap-3 xl:hover:text-black"><i class="bi bi-house-gear text-blue-400"></i> Home Decor</a>
                            </li>
                            <li class="flex items-center">
                                <a href="#" class="border-t mx-4 w-full border-t-neutral-200 h-12 flex items-center gap-3 xl:hover:text-black"><i class="bi bi-clipboard-heart-fill text-blue-400"></i> Medicine</a>
                            </li>
                            <li class="flex items-center">
                                <a href="#" class="border-t mx-4 w-full border-t-neutral-200 h-12 flex items-center gap-3 xl:hover:text-black"><i class="bi bi-usb-mini text-blue-400"></i> Furniture</a>
                            </li>
                            <li class="flex items-center">
                                <a href="#" class="border-t mx-4 w-full border-t-neutral-200 h-12 flex items-center gap-3 xl:hover:text-black"><i class="bi bi-tools text-blue-400"></i> Crafts</a>
                            </li>
                            <li class="flex items-center">
                                <a href="#" class="border-t mx-4 w-full border-t-neutral-200 h-12 flex items-center gap-3 xl:hover:text-black"><i class="bi bi-pencil text-blue-400"></i> Accesories</a>
                            </li>
                            <li class="flex items-center">
                                <a href="#" class="border-t mx-4 w-full border-t-neutral-200 h-12 flex items-center gap-3 xl:hover:text-black"><i class="bi bi-camera text-blue-400"></i> Camera</a>
                            </li> -->
                        </ul>
                    </div>
                <!-- Categories End -->

                <!-- Recomendated Start -->
                    <div class="w-full flex gap-5 xl:mt-7 lg:mt-9 2xl:mt-0 md:mt-8 mt-6  xl:h-[360px] xl:flex-row flex-col ">
                        <div class="relative bg-gray-100 xl:w-[800px] w-full xl:h-full lg:h-[350px] md:h-[250px] h-[200px] rounded-xl overflow-hidden">
                            <div class="w-full h-full justify-center items-center lg:flex hidden">
                                <div class='rounded-full h-64 w-64 bg-white/80 ml-24'></div>
                            </div>
                            <div class='absolute h-full overflow-hidden inset-0' id="header-recomendated-products">
                                @foreach ($recomendationProductsFirst as $product )
                                    <div class="flex justify-between lg:px-5 px-3 md:gap-5 gap-2 items-center !w-full h-full">
                                        <ul class="flex flex-col md:w-1/2 w-2/3 gap-4">
                                            <li class="flex items-center gap-2 text-florarColor lg:text-sm text-xs font-semibold">
                                                <p class="h-6 w-6 bg-florarColor text-center items-center flex justify-center rounded-full text-white"><i class="{{ $product['category_icons'] }} ml-[.9px]"></i></p>
                                                <h1>{{ $product['category'] }}</h1>
                                            </li>
                                            <li class='lg:text-4xl md:text-2xl text-2xl font-bold w-full'>
                                                <h1>{{ $product['name'] }}</h1>
                                            </li>
                                            <li class='border-b lg:w-48 md:w-32 w-28 duration-200 xl:hover:w-[188px] border-b-black text-black font-semibold'>
                                                <a href="{{ $product['link'] }}" class="flex justify-between items-center">{{ __('template.shop_now') }} <i class="bi bi-arrow-right"></i></a>
                                            </li>
                                        </ul>
                                        <div class="!w-2/5 lg:!h-72 md:!h-48 !h-24">
                                            <img src="{{ $product['image'] }}" class="h-full w-full object-contain" alt="{{ $product['name'] }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="bg-gray-100 xl:h-full xl:w-[280px] lg:h-[350px] md:h-[250px] h-[350px] w-full rounded-xl flex flex-col justify-center items-center relative overflow-hidden">
                            <div class="rounded-full lg:h-40 lg:w-40 md:h-28 md:w-28 bg-white/80 mb-14 xl:ml-10"></div>
                            <div class="absolute inset-0 flex justify-center items-center">
                                <ul class="flex flex-col items-center gap-2">
                                    <li class="lg:h-52 lg:w-52 md:h-36 md:w-36 w-44 h-44 mb-2.5">
                                        <a href="#" class="h-full w-full">
                                            <img src="{{ $recomendationProductsSecond['image'] }}" class="h-full w-full object-contain" alt="{{ $recomendationProductsSecond['name'] }}">
                                        </a>
                                    </li>
                                    <li>
                                        <a href='{{ $recomendationProductsSecond['link'] }}' class="text-neutral-500 text-base truncate">{{ $recomendationProductsSecond['name'] }}</a>
                                    </li>
                                    <li>
                                        <h1 class="text-xl font-bold">{{ $recomendationProductsSecond['price'] }}</h1>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <!-- recomendated End -->
            </div>
        <!-- Categories and recomendated products End -->

        <!-- Carousels New Arrivals and Best Sellers Start -->
            <div class="flex flex-col gap-4" x-cloak>
                @foreach ($carouselsArrivalsSellers as $carousel )
                    <div class="!mb-6">
                        <!-- Carousel Header Start -->
                            @include('includes.products.carousel.carousel-header', [
                                'nameIcon' => 'bi bi-basket',
                                'color' => $carousel['color'],
                                'title'=> $carousel['title'],
                                'id_name'=> $carousel['id_name'],
                                'when'=> $carousel['when'],
                            ])
                        <!-- Carousel Header End -->

                        <!-- Rroducts Start -->
                            <div class="pt-6" id="{{ $carousel['id_name'] }}" x-cloak>
                                @foreach ($products as $product )
                                    <div class="w-80 xl:mr-6 mx-3 ">
                                        @include('includes.products.item.default', [
                                            'product' => $product
                                        ])
                                    </div>
                                @endforeach
                            </div>
                        <!-- Rroducts End -->
                    </div>
                @endforeach
            </div>
        <!-- Carousels New Arrivals and Best Sellers End -->

        <!-- Dont Miss Start-->
            <div class="w-full 2xl:h-[450px] xl:h-[550px] bg-gray-100 rounded-xl flex xl:flex-row flex-col xl:gap-0 gap-5 items-center justify-between p-14 relative xl:mt-28 mt-14 mb-12" x-cloak>
                <div class="flex flex-col xl:justify-between h-full xl:w-1/2 justify-center xl:items-start items-center xl:gap-0 gap-4">
                    <ul class="xl:text-start !text-center">
                        <li class="flex items-center gap-2 text-florarColor xl:justify-normal justify-center">
                            <p class="h-6 w-6 bg-florarColor text-center items-center text-sm flex justify-center rounded-full text-white"><i class="bi bi-basket ml-[.9px]"></i></p>
                            <p class="font-semibold">{{ __('template.dont_miss') }}</p>
                        </li>
                        <li class="md:text-5xl text-3xl font-bold 2xl:w-auto xl:w-60 !w-full">
                            <h1>{{ __('template.let_s_shopping_today') }}</h1>
                        </li>
                    </ul>
                    <!-- Timer Start -->
                        <div>
                            <ul class="flex md:gap-3 gap-1">
                                @foreach ($timerData as $data)
                                    <li class="flex flex-col justify-center text-center rounded-full md:h-20 md:w-20 h-16 w-16 p-1.5 bg-white text-black overflow-hidden">
                                        <h1 id="{{ $data['id_name'] }}" class="font-medium md:text-lg text-base"></h1>
                                        <p class="text-neutral-500 md:text-sm text-xs">{{ $data['name'] }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    <!-- Timer End -->
                    <a href="#" class="md:h-10 h-8 w-fit bg-blue-500 rounded-lg flex items-center text-white px-10 md:py-[30px] py-[25px] md:text-xl text-lg font-medium">{{ __('template.check_it_out') }}</a>
                </div>

                <div class="2xl:w-[550px] 2xl:h-[550px] xl:w-[450px] xl:h-[450px] lg:w-[550px] lg:h-[550px] md:w-[400px] md:h-[400px] 2xl:absolute 2xl:right-32 2xl:-top-32">
                    <img src="{{ asset('template/images/smartwatch-2.png') }}" class="h-full w-full object-contain" alt="Product">
                </div>
            </div>
        <!-- Dont Miss End-->

        <!-- Explore Our Products Start -->
            <div x-cloak>
                <!-- Carousel Header Start -->
                    <div>
                        @include('includes.products.carousel.carousel-header', [
                            'nameIcon' => 'bi bi-basket',
                            'color' => 'violet-500',
                            'title'=> __('template.explore_our_products'),
                            'id_name'=> 'our_products',
                            'when'=> __('template.our_products'),
                        ])
                    </div>
                <!-- Carousel Header End -->

                <!-- Carousels Products Start -->
                    <div id="our_products" class="pt-7">
                        @foreach ($productsExploreOur as $productsGroup)
                            <div> 
                                <div class="grid 2xl:grid-cols-4 xl:grid-cols-3 lg:grid-cols-2 grid-cols-1 mx-2 lg:mx-0 xl:gap-6 md:gap-7">
                                    @foreach ($productsGroup as $product)
                                        @include('includes.products.item.default', [
                                            'product' => $product
                                        ])
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                <!-- Carousels Products End -->
                
                <div class="flex justify-center items-center h-16 my-5">
                    <a href=""
                        class="flex justify-center items-center text-center w-52 h-14 bg-gray-100 text-neutral-500 font-semibold rounded-lg transition-all duration-300 hover:text-neutral-500 hover:shadow-md xl:hover:h-[60px] xl:hover:w-[212px]">
                        {{ __('template.view_all_products') }}
                    </a>
                </div>
                
            </div>
        <!-- Explore Our Products End -->
    </div>

    <!-- User Feedback Start -->
        <div class="bg-orange-50/50 h-[580px] pt-5 !pb-8" x-cloak>
            <div class='container mx-auto h-full flex flex-col gap-2 mb-4'>
                <!-- Header carousel Start -->
                    <div>
                        @include('includes.products.carousel.carousel-header', [
                            'nameIcon' => 'bi bi-quote',
                            'color' => 'florarColor',
                            'title'=> __('template.users_feedback'),
                            'id_name'=> 'feedback-carousel',
                            'when'=> __('template.testimonials'),
                        ])
                    </div>
                <!-- Header carousel End -->

                <div id='feedback-carousel' class="h-full">
                    @foreach ( $userFeedback as $user )
                        <div class="!flex !flex-col !gap-5 md:h-[300px] h-[450px] lg:!w-[800px] !w-96 md:!mx-2 mx-1.5">
                            <div class="bubble 2xl:!h-52 w-full h-auto md:p-5 p-3 text-neutral-500">
                                <p>{{ $user['content'] }}</p>
                            </div>
                            <div class="flex gap-3 items-center !h-20">
                                <div class="w-14 h-14 rounded-lg overflow-hidden">
                                    <img src="{{ $user['image'] }}" class="object-cover h-full w-full" alt="{{ $user['name'] }}">
                                </div>
                                <ul>
                                    <li>
                                        <p class="text-sm text-neutral-400">{{ $user['role'] }}</p>
                                    </li>
                                    <li>
                                        <h1 class="font-medium">{{ $user['name'] }}</h1>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    <!-- User Feedback End -->

    <!-- New Arrivals 2 Start -->
        <div class="container mx-auto my-5 lg:h-[550px] md:h-[570px] !mb-6 flex flex-col gap-4" x-cloak>
            <!-- Carousel Header Start -->
                @include('includes.products.carousel.carousel-header', [
                    'nameIcon' => 'bi bi-basket',
                    'color' => 'violet-500',
                    'title'=> __('template.new_arrivals'),
                    'id_name'=> 'new-arrivals-2',
                    'when'=> __('template.this_weeks'),
                ])
            <!-- Carousel Header End -->

            <!-- Rroducts Start -->
                <div class="" id="new-arrivals-2">
                    @foreach ($products as $product )
                        <div class="xl:!w-[350px] lg:!w-[330px] md:!w-[330px]  xl:!mx-4 lg:!mx-5 !mx-9">
                            @include('includes.products.item.default', [
                                'product' => $product
                            ])
                        </div>
                    @endforeach
                </div>
            <!-- Rroducts End -->
        </div>
    <!-- New Arrivals 2 End -->
</x-app-layout>

<script type='module'>
    $(document).ready(function () {
        /**
        *   Timers to Section Lets Shopping today
        */
        var endDate = new Date("2025-09-06T12:55:33"),
            days = $('#days'),
            hrs = $('#hrs'),
            min = $('#min'),
            sec = $('#sec');

        var interval = setInterval(function() {
            var now = new Date(),
                timeRemaining = endDate - now; 

            if (timeRemaining <= 0) {
                [days, hrs, min, sec].map(function(el) {
                    el.html(0); 
                });
                clearInterval(interval);
            } else {
                var d = Math.floor(timeRemaining / (1000 * 60 * 60 * 24)),
                    h = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
                    m = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60)),
                    s = Math.floor((timeRemaining % (1000 * 60)) / 1000);

                days.html(d < 10 ? "0" + d : d);
                hrs.html(h < 10 ? "0" + h : h);
                min.html(m < 10 ? "0" + m : m);
                sec.html(s < 10 ? "0" + s : s);
            }
        }, 1000);


        /**
        *   Carousels 
        */
        const sliders = [ '#arrivals-carousel', '#best-sellers', '#new-arrivals-2']


        sliders.forEach(el => {
            $(el).slick({
                slidesToScroll: 1,
                slidesToShow: 5,
                arrows:false,
                responsive: [
                    {
                    breakpoint: 1440,
                        settings: {
                            slidesToShow: 4,
                            slidesToScroll: 4,
                        }
                    },
                    {
                    breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
                        }
                    },
                    {
                    breakpoint: 768,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        }
                    },
                ]
            })
            
            slickBtns(el)
        });

        $('#our_products').slick({
            arrows:false,
            slidesToScroll: 1,
            slidesToShow: 1,
        });
        slickBtns('#our_products')

        $('#feedback-carousel').slick({
            arrows:false,
            slidesToScroll: 1,
            slidesToShow: 3,
            infinite: true,
            centerMode: true,
            responsive: [
                    {
                    breakpoint: 1024,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        }
                    },
                ]

        });

        slickBtns('#feedback-carousel')

        $('#header-recomendated-products').slick({
            arrows: false,
            fade: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            adaptiveHeight: false,
            dots: true,
            autoplay: true,
            autoplaySpeed: 5000,
            speed: 1000,
            infinite: true,
        });

        function slickBtns(btn){
            $(btn + '-prev-btn').click(function() {
                $(btn).slick('slickPrev');
            });

            $(btn + '-next-btn').click(function() {
                $(btn).slick('slickNext');
            });
        }
    })
</script>