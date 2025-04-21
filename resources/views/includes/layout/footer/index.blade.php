@php
    $qucikLinkMenu = \App\Models\Navigation\Menu::where('key', 'quick-link-footer')->first();
    $blocksInfo = [
        [
            'title' => __('template.fast_secure_delivery'), 
            'description' => __('template.tell_about_service'), 
            'image' => 'template/images/service1.png'
        ],
        [
            'title' => __('template.money_back_guarantee'), 
            'description' => __('template.within_10_days'), 
            'image' => 'template/images/service2.png'
        ],
        [
            'title' => __('template.return_policy_24h'), 
            'description' => __('template.no_questions_asked'), 
            'image' => 'template/images/service3.png'
        ],
        [
            'title' => __('template.pro_quality_support'), 
            'description' => __('template.live_support_24_7'), 
            'image' => 'template/images/service4.png'
        ],
    ];
@endphp
<div class="container mx-auto h-fit w-full xl:mt-32 lg:mt-24 md:mt-20 mt-16 space-y-10">
    <!-- NewsLetter Start -->
        <div class="rounded-lg overflow-hidden !h-96 border relative">
            <div class="w-full h-full border">
                <img src="{{ asset('template/images/footer-image.jpg') }}" class="w-full h-full object-cover" alt="Footer Image">
            </div>

            <div class="w-full h-full">
                <ul class="absolute xl:top-20 xl:left-20 lg:left-16 lg:top-10 md:top-6  md:p-0 p-4  lg:mr-0 md:mr-3.5 md:left-6 sm:top-0 sm:left-0 space-y-4">
                    <li class="flex items-center gap-2">
                        <span class="bg-blue-500 h-7 w-7 rounded-full flex items-center justify-center"><i class="bi bi-envelope-open-fill text-white text-sm"></i></span> 
                        <h1 class="text-blue-500 font-bold">{{ __('template.newsletter') }}</h1>
                    </li>
                    <li class="lg:pb-8 pb-6">
                        <h1 class='lg:text-3xl text-2xl font-bold'>{{ __('template.get_weekly_update') }}</h1>
                    </li>
                    <form class="w-full flex xl:items-center gap-4 xl:flex-row flex-col">   
                        @csrf
                        <div class="lg:w-[450px] h-16 flex items-center gap-4 border-gray-300 relative">
                            <div class="absolute inset-y start-0 flex items-center ps-3 pointer-events-none w-full h-full">
                                <i class="bi bi-envelope-paper"></i>
                            </div>
                            <input type="text" name="newseller" class="block w-full h-full ps-10 text-lg font-medium text-neutral-500 border  rounded-lg bg-white focus:ring-blue-400 focus:border-blue-400" placeholder="example@gmail.com"/>
                        </div>
                        <button class="h-16 w-fit px-4 bg-neutral-900 rounded-lg text-white font-semibold lg:text-xl md:text-lg flex items-center justify-center">{{ __("template.subscribe") }}</button>
                    </form>
                </ul>
            </div>
        </div>
    <!-- NewsLetter End -->

    <!-- Block Info Start -->
        <div class="xl:flex items-center xl:justify-between xl:gap-2 lg:space-y-3 xl:columns-5 lg:columns-2 space-y-2">
            @foreach ( $blocksInfo as $info)
                <div class="flex items-center gap-3 md:w-auto w-full">
                    <div class="h-10 w-10">
                        <img src="{{ asset($info['image']) }}" class="h-full w-full object-contain" alt="{{ $info['title'] }}">
                    </div>
                    <ul class="text-neutral-500 w-fit">
                        <li>
                            <h1 class="text-black font-semibold w-fit text-lg">{{ $info['title'] }}</h1>
                        </li>
                        <li>
                            <p class="text-base">{{ $info['description'] }}</p>
                        </li>
                    </ul>
                </div>
            @endforeach
        </div>
    <!-- Block Info End -->

    <div class="border xl:my-10 sm:my-10 rounded-xl "></div>
</div>
<footer class="xl:container xl:mx-auto sm:px-2 lg:p-0 my-10">
    <div>
        <!-- Footer Links Start-->
            <div class="flex xl:justify-between lg:flex-row sm:flex-col sm:gap-2">
                <!-- Company info Start-->
                    <div class="flex flex-col xl:gap-4 sm:gap-1 py-2 lg:w-1/2 w-screen">
                        <h1 class="text-xl font-semibold">{{ __('template.support') }}</h1>
                        <div>
                            {{-- @if (!empty($templateSettings['idno']))
                                <p class="text-neutral-500 w-64">{{ $templateSettings['idno'] }}</p>
                            @endif --}}
                            @if (!empty($templateSettings['company-legal-address']))
                                <p class="text-neutral-500 w-64">{{ __('template.legal_address') }} {{ $templateSettings['company-legal-address'] }}</p>
                            @endif
                            <br>
                            @if(!empty($templateSettings['company-email']))
                                <p class="text-neutral-500 w-64 flex gap-2.5"><i class="bi bi-envelope-open"></i> {{ $templateSettings['company-email'] }}</p>
                            @endif
                            @if(!empty($templateSettings['company-telephone']))
                                <p class="text-neutral-500 w-64 flex gap-2.5"><i class="bi bi-telephone"></i> {{ $templateSettings['company-telephone'] }}</p>
                            @endif
                        </div>
                    </div>
                <!-- Company info Start-->

                <!-- Quick Links Start-->
                    <div class="flex flex-col gap-2 py-2 lg:w-1/2 w-screen" >
                        <h1 class="text-xl font-semibold">{{ __('template.account') }}</h1>
                        <div>
                            <ul>
                                <li class="mt-2">
                                    <a class="!text-black hover:border-b border-neutral-400" href="{{ route('custom.login') }}">{{ __('template.account') }}</a>
                                </li>       
                                <li class="mt-2">
                                    <a class="!text-black hover:border-b border-neutral-400" href="{{ route('cart.view') }}">{{ __('template.cart') }}</a>
                                </li>       
                                <li class="mt-2">
                                    <a class="!text-black hover:border-b border-neutral-400" href="{{ route('follow.view') }}">{{ __('template.whislist') }}</a>
                                </li>       
                                <li class="mt-2">
                                    <a class="!text-black hover:border-b border-neutral-400" href="{{ route('shop.home') }}">{{ __('template.shop') }}</a>
                                </li>       
                            </ul>
                        </div>
                    </div>
                <!-- Quick Links End-->

                <!-- Account Links Start-->
                    <div class="flex flex-col gap-2 py-2 lg:w-1/2 w-screen" >
                        <h1 class="text-xl font-semibold">{{ $qucikLinkMenu['name'] }}</h1>
                        <div>
                            <ul>
                                @foreach($qucikLinkMenu->items as $link)
                                    @if ($link->is_active === 1 )
                                        <li class="mt-2">
                                            <a class="!text-black hover:border-b border-neutral-400" href="{{ (App\Models\Page\Page::find($link->entity_id))->link }}">{{ $link->label}}</a>
                                        </li>       
                                    @endif
                                @endforeach 
                            </ul>
                        </div>
                    </div>
                <!-- Account Links End-->
            </div>
        <!-- Footer Links End-->
    </div>
    <div class="border sm:my-5 rounded-xl "></div>
    <div class="flex xl:flex-row flex-col gap-3.5 justify-between items-center w-full">
        <!-- Social Links Start-->
            <div class="text-neutral-500">
                @include('includes.links.media')
            </div>
        <!-- Social Links End-->

        <!-- CopyRight Start-->
            <div>
                <h1 class="text-neutral-500 sm:text-xs lg:text-sm ">&copy;{{date('Y')}} Florar.md  {{ __('template.flower_delivery_service') }}</h1>
            </div>
        <!-- CopyRight Start-->

        <!-- Payments Accept Start-->
            <div class="sm:h-4 inline-flex items-center gap-2">
                <p class="text-xs text-neutral-500">{{ __('template.accept_for ') }}</p>
                @include('includes.links.payments-accept')
            </div>
    <!-- Payments Accept End-->
    </div>
</footer>