<x-app-layout>

    @section('title', __('template.sale'))

    <!-- BREADCRUMB AREA START -->
    <div class="ltn__breadcrumb-area ltn__breadcrumb-area-4 ltn__breadcrumb-color-white---">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner text-center">
                        <h1 class="ltn__page-title">{{__('template.sale')}}</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="{{\App\Models\Navigation\Menu::getHomePageLink()}}">{{__('template.home')}}</a></li>
                                <li>{{__('template.sale')}}</li>
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
                    <div class="ltn__shop-options">
                        <ul>
                            <li>
                                @include('includes.results', [
                                    'on_page' => $products->count(),
                                    'total' => $products->total()
                                ])
                            </li>
                            <li>
                                @include('includes.products.filter.sort.default', [
                                    'sorting' => $sorting
                                ])
                            </li>
                        </ul>
                    </div>
                    
                    <div class="ltn__product-tab-content-inner ltn__product-grid-view">
                        <div class="row">
                            @foreach($products as $product)
                                <!-- ltn__product-item -->
                                <div class="col-xl-3 col-lg-4 col-sm-6 col-12">
                                    @include('includes.products.item.default', [
                                        'product' => $product    
                                    ])
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    @if($products->hasPages() > 0)
                        {!! $products->links('pagination.default') !!}
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
    <!-- PRODUCT DETAILS AREA END -->
        
</x-app-layout>