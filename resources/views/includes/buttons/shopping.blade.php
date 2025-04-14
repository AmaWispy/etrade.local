<a href="{{ route('shop.home') }}" class="flex gap-2 bg-florarColor xl:hover:bg-black p-2 rounded-full items-center justify-center sm:w-32 md:w-48 lg:w-[37vw] xl:!px-6 xl:w-auto">
    <img src="{{ asset('template/images/logo.png') }}" class="xl:h-8 md:h-[2vh] md:inline hidden" alt="{{ __('template.to_shopping') }}">
    <span class="text-white font-medium sm:text-[11px] xl:text-[18px] lg:text-[15px] hidden lg:inline">{{ __('template.back_to_shopping') }}</span>
    <span class="text-white font-medium sm:text-[11px] xl:text-[18px] lg:text-[15px] inline lg:hidden">{{ __('template.to_shopping') }}</span>
</a>

{{-- route('shop.home') or use this --}}