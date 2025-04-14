<style>
    .ltn__shop-details-small-img .slick-current, .images-mobile .slick-current {
        border: 3px solid #3b82f6;
        border-radius: 15px;
    }

</style>

<div class="ltn__shop-details-img-gallery ltn__shop-details-img-gallery-2 w-full 2xl:gap-7 xl:gap-3 !flex xl:flex-row flex-col gap-2 relative" id="desc-gallery">
    <div class="ltn__shop-details-small-img slick-arrow-2  xl:!w-[100px] h-fit !w-full xl:flex !flex-col hidden" id='nav'></div>
    <div class="ltn__shop-details-large-img 2xl:!w-[540px] 2xl:!h-[570px] xl:!w-[500px] xl:!h-[600px] lg:!h-[650px] rounded-xl overflow-hidden " id="view-box"></div>

    <!-- Sale or New Start -->
        <div class="absolute font-bold top-5 -right-4 flex flex-col gap-2">
            <li class="text-white text-xs !bg-red-500 h-fit w-[75px] px-3 py-1 !rounded-lg items-center justify-center hidden" id="product_is_new">{{ __('template.new') }}</li>
            <li class="text-white text-xs !bg-blue-500 h-fit w-[75px] px-3 py-1 !rounded-lg items-center justify-center hidden" id="product_on_sale"></li>
        </div>
    <!-- Sale or New End -->
</div>