<!-- header-bottom-area start -->
<div class="header-bottom-area ltn__border-top ltn__header-sticky  ltn__sticky-bg-white ltn__primary-bg---- menu-color-white---- d-none d-lg-block">
    <div class="container">
        <div class="row">
            <div class="col header-menu-column">
                <div class="sticky-logo">
                    <div class="site-logo">
                        <a href="{{\App\Models\Navigation\Menu::getHomePageLink()}}">
                            <img src="{{ asset('template/images/logo.png') }}" class="main-logo" alt="Logo"> 
                            <div>
                                <span>Florar.md</span>
                                <span>{{ __('template.flower_delivery') }}</span>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="header-menu header-menu-2">
                    <nav>
                        <div class="ltn__main-menu">
                            <ul>
                                <li class="menu-icon">
                                    <a href="{{route('shop.home')}}" class="highlited">
                                        {{ __('template.shop') }}
                                    </a>
                                    <ul class="mega-menu">
                                        @foreach(\App\Models\Shop\Category::getTree() as $category)
                                            <li class="mt-1">
                                                <a href="{{$category['link']}}">
                                                    {{$category['name']}}
                                                </a>
                                                @if(!empty($category['children']))
                                                <ul>
                                                    @foreach($category['children'] as $subcategory)
                                                        <li>
                                                            <a href="{{$subcategory['link']}}">
                                                                {{$subcategory['name']}}
                                                                @if(!empty($subcategory['children']))<i class="icon-arrow-right"></i>@endif
                                                            </a>
                                                            @if(!empty($subcategory['children']))
                                                                <ul>
                                                                    @foreach($subcategory['children'] as $subsubcategory)
                                                                        <li>
                                                                            <a href="{{$subsubcategory['link']}}">
                                                                                {{$subsubcategory['name']}}
                                                                            </a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                                @endif
                                            </li> 
                                        @endforeach
                                    </ul>
                                </li>
                                @foreach(\App\Models\Navigation\Menu::getByKey('main-menu')->getLinks() as $link)
                                    <li class="menu-icon">
                                        <a href="{{$link['link']}}">{{$link['label']}}</a>
                                        @if(!empty($link['children']))
                                            <ul>
                                                @foreach($link['children'] as $child)
                                                    <li>
                                                        <a href="{{$child['link']}}">{{$child['label']}}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                                
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- header-bottom-area end -->