<!-- SLIDER AREA START (slider-6) -->
<div class="ltn__slider-area ltn__slider-3 ltn__slider-6 section-bg-1">
    <div class="ltn__slide-one-active slick-slide-arrow-1 slick-slide-dots-1 arrow-white---">
        @foreach($slider->items as $key => $item)
        <!-- ltn__slide-item  -->
        <div class="ltn__slide-item ltn__slide-item-8 text-color-white---- bg-image bg-overlay-theme-black-80---" data-bs-bg="{{ url('storage/'.$item->image) }}">
            <div class="ltn__slide-item-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 align-self-center">
                            <div class="slide-item-info-inner ltn__slide-animation">
                                <div class="slide-item-info">
                                    <div class="slide-item-info-inner ltn__slide-animation">
                                        <h1 class="slide-title animated ">{{$item->title}}</h1>
                                        <h6 class="slide-sub-title slide-title-line animated">{{$item->subtitle}}</h6>
                                        <div class="slide-brief animated">
                                            {!! $item->content !!}
                                        </div>
                                        <div class="btn-wrapper animated">
                                            <a href="{{route('shop.home')}}" class="theme-btn-1 btn btn-round">{{__('template.shop_now')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- // -->
        @endforeach
    </div>
</div>
<!-- SLIDER AREA END -->