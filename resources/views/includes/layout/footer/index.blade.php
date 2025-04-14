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
<footer class="xl:container xl:mx-auto sm:px-2 lg:p-0 my-10">
    <!-- Block Info Start -->
        {{-- <div class="flex justify-between">
            @foreach ( $blocksInfo as $info)
                <div class="flex items-center gap-3 md:w-auto w-full">
                    <div>
                        <img src="{{ asset($info['image']) }}" class="h-10 " alt="{{ $info['title'] }}">
                    </div>
                    <ul class="text-lg text-neutral-500 !max-w-max">
                        <li>
                            <h1 class="text-black font-semibold max-w-max">{{ $info['title'] }}</h1>
                        </li>
                        <li>
                            <p>{{ $info['description'] }}</p>
                        </li>
                    </ul>
                </div>
            @endforeach
        </div> --}}
    <!-- Block Info End -->
    
    <div>
        <div class="border xl:my-10 sm:my-10 rounded-xl "></div>
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
                                    <a class="!text-black hover:border-b border-neutral-400" href="{{ route('account.index') }}">{{ __('template.account') }}</a>
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