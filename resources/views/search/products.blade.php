<x-app-layout>

    @section('title', __('template.search_results_query') . ': ' . $sq)
    
    <!-- BREADCRUMB AREA START -->
    <div class="ltn__breadcrumb-area ltn__breadcrumb-area-4 ltn__breadcrumb-color-white---">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner text-center">
                        <h1 class="ltn__page-title">{{ __('template.search_results') }}</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="{{\App\Models\Navigation\Menu::getHomePageLink()}}">{{__('template.home')}}</a></li>
                                <li>{{ __('template.search_results_query') }}: "{{$sq}}"</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BREADCRUMB AREA END -->

        <!-- PRODUCT DETAILS AREA START -->
        <div class="ltn__product-area mb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    @if($result->total() > 0)
                        <div class="ltn__shop-options">
                            <ul>
                                <li>
                                @include('includes.results', [
                                        'on_page' => $result->count(),
                                        'total' => $result->total()
                                    ])
                                </li>
                                <li>
                                    @include('includes.products.filter.sort.', [
                                        'sorting' => $sorting
                                    ])
                                </li>
                            </ul>
                        </div>
                        
                        <div class="ltn__product-tab-content-inner ltn__product-grid-view">
                            <div class="row">
                                @foreach($result as $product)
                                    <!-- ltn__product-item -->
                                    <div class="col-xl-3 col-lg-4 col-sm-6 col-12">
                                        @include('includes.products.item.default', [
                                            'product' => $product    
                                        ])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        @if($result->hasPages() > 0)
                            <div class="ltn__pagination-area text-center">
                                <div class="ltn__pagination ltn__pagination-2">
                                    {!! $result->links() !!}
                                    <ul>
                                        <li><a href="#"><i class="icon-arrow-left"></i></a></li>
                                        <li><a href="#">1</a></li>
                                        <li class="active"><a href="#">2</a></li>
                                        <li><a href="#">3</a></li>
                                        <li><a href="#">...</a></li>
                                        <li><a href="#"><i class="icon-arrow-right"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        @endif
                    @else
                        <h1 class="ltn__page-title">{{ __('template.nothing_found') }}</h1>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- PRODUCT DETAILS AREA END -->
        
</x-app-layout>