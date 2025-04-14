<!-- FOOTER FIRST SECTION START-->
    <div class="flex items-center container flex-wrap xl:flex-nowrap gap-3">
        <div class="flex xl:gap-4 sm:gap-1 " data-aos='fade-up'>
            @if (!empty($templateSettings['company-legal-address-image']))
                <div class="2xl:h-80 xl:h-72 sm:h-40 md:h-60 md:w-1/2 sm:w-40">
                    <img src="{{ asset($templateSettings['company-legal-address-image']) }}" class="h-full w-full object-cover rounded-lg" alt="">
                </div>
            @endif
            @if (!empty($templateSettings['address']))
                <div class="2xl:h-80 xl:h-72 sm:h-40 md:h-60 md:w-1/2 sm:w-40">
                    <img src="{{ asset($templateSettings['address-image']) }}" class="h-full w-full object-cover rounded-lg" alt="">
                </div>
            @endif
        </div>
        <div class="flex flex-col gap-4" data-aos='fade-up'>
            <h1 class="text-xl font-semibold">{{ __('template.working_hours') }}</h1>
            <div>
                <p><span class="font-semibold">{{ __('template.online_orders_24_7') }} </span><span class="text-neutral-500"> 24/7</span></p>
                @if (!empty($templateSettings['address']))
                    <p><span class="font-semibold">{{ __('template.salon_address') }} </span> <span class="text-neutral-500">{{ $templateSettings['address'] }}</span></p>
                @endif
                <p class="text-neutral-500"><span>{{ __('template.monday_saturday') }}: </span> {{ $templateSettings['working-hours'] }}</p>
            </div>
            <div class="">
                <h1 class="text-3xl font-semibold">{{ str_replace('-',' ',$templateSettings['company-telephone']) }}</h1>
                @include('includes.links.messangers')
            </div>
            <div>
                <p class="text-neutral-400">{{ $templateSettings['company-email'] }}</p>
            </div>
        </div>
    </div>
<!-- FOOTER FIRST SECTION END-->