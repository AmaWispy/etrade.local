<style>
    .ltn__shop-details-small-img .slick-current, .images-mobile .slick-current {
        border: 3px solid #3b82f6;
        border-radius: 15px;
    }

</style>

<div class="ltn__shop-details-img-gallery ltn__shop-details-img-gallery-2  2xl:w-1/2 xl:w-3/5 2xl:gap-4 xl:gap-3 inline-flex xl:flex-row flex-col gap-2 relative" id="desc-gallery">
    <div class="ltn__shop-details-small-img  slick-arrow-2 flex-col xl:!w-[94px] h-fit !w-full xl:inline-flex hidden">
        @if ($images && count($images) > 0)
            @foreach ($images as $image )
                <div class="2xl:!w-[85px] 2xl:!h-[82px] xl:!h-[78px] xl:!w-[80px]  mr-6 xl:mb-2 rounded-xl">
                    <img src="{{ $image->getUrl('medium') }}" class="h-full w-full object-cover rounded-xl" alt="Product">
                </div>
            @endforeach
        @else
            <div class="2xl:!w-[85px] 2xl:!h-[82px] xl:!h-[78px] xl:!w-[80px]  mr-6 xl:mb-2 rounded-xl">
                <img src="{{ asset('storage/no-image.png') }}" class="h-full w-full object-cover rounded-xl" alt="Product">
            </div>
        @endif
    </div>

    <div class="ltn__shop-details-large-img 2xl:!w-[540px] 2xl:!h-[570px] xl:!w-[480px] xl:!h-[500px] lg:!h-[650px] lg:!w-full  rounded-xl  overflow-hidden">
        @if ($images && count($images) > 0)
            @foreach ($images as $image )
                <div class="single-large-img !h-full !w-full rounded-xl overflow-hidden">
                    <a href="{{ $image->getUrl('medium') }}" data-rel="lightcase:myCollection" class="!w-full !h-full rounded-xl">
                        <img src="{{ $image->getUrl('medium') }}" class="!h-full !w-full object-cover" alt="Product">
                    </a>
                </div>
            @endforeach
        @else
            <div class="single-large-img !h-full !w-full rounded-xl overflow-hidden">
                <img src="{{ asset('storage/no-image.png') }}" class="!h-full !w-full object-cover" alt="Product">
            </div>
        @endif
    </div>
    
    @if (isset($product))
        <div class="absolute font-bold top-5 right-8 flex flex-col gap-2">
            @if($product->isNew())
                <li class="text-white text-xs !bg-red-500 h-fit w-[75px] px-3 py-1 !rounded-lg !inline-flex items-center justify-center">{{ __('template.new') }}</li>
            @endif
            @if($product->onSale())
                <li class="text-white text-xs !bg-blue-500 h-fit w-[75px] px-3 py-1 !rounded-lg !inline-flex items-center justify-center">{{$product->getSaleBadge()}}</li>
            @endif
        </div>
    @endif
    <!-- Mobile Images Start -->
        <div class="images-mobile flex-row lg:!h-[68px] md:!h-[60px] xl:hidden block w-fit">
            @if ($images && count($images) > 0)
                @foreach ($images as $image )
                    <div class="block !h-full lg:!w-[68px] md:!w-[60px] lg:mr-8 mr-4 xl:mb-2 rounded-xl">
                        <img src="{{ $image->getUrl('medium') }}" class="h-full w-full object-cover rounded-xl" alt="Product">
                    </div>
                @endforeach
            @else
                <div class="block !h-full lg:!w-[68px] md:!w-[60px] lg:mr-8 mr-4 xl:mb-2 rounded-xl">
                    <img src="{{ asset('storage/no-image.png') }}" class="h-full w-full object-cover rounded-xl" alt="Product">
                </div>
            @endif
        </div>
    <!-- Mobile Images Start -->
</div>

<script type="module">
    $(document).ready(function(){
        $(".ltn__shop-details-large-img").on("init", function(event, slick){
        $(".slick-track").css("display", "flex"); // Выравниваем слайды по центру
        $(".slick-slide").css("display", "flex").css("align-items", "center").css("justify-content", "center");
    }).slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: false,
        cssEase: "linear",
        asNavFor: ".ltn__shop-details-small-img, .images-mobile",
    });

    $(".ltn__shop-details-small-img").slick({
        vertical: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        asNavFor: ".ltn__shop-details-large-img",
        dots: false,
        arrows: false,
        focusOnSelect: true,
    });

        
    $(".images-mobile").slick({
        vertical: false,
        slidesToShow: 5,
        slidesToScroll: 1,
        asNavFor: ".ltn__shop-details-large-img",
        dots: false,
        arrows: false,
        focusOnSelect: true,
        centerMode:false,
    });

    $("a[data-rel^=lightcase]").lightcase({
        transition:
            "elastic" /* none, fade, fadeInline, elastic, scrollTop, scrollRight, scrollBottom, scrollLeft, scrollHorizontal and scrollVertical */,
        swipe: true,
        maxWidth: 1170,
        maxHeight: 600,
    });
})
</script>