<style>
    .nice-select{
        z-index: 9999;
        background-color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        padding-left: 1px;
    }
</style>
@php
use Illuminate\Support\Facades\Cookie;
use App\Models\Shop\Cart;
use App\Models\CategoryCustom;
use Illuminate\Support\Facades\Auth;

$HeaderMenu = \App\Models\Navigation\Menu::where('key', 'header-nav')->first();
$cart = null;

if(session()->has('cart')){
    $cartData = session()->get('cart');
    $cart = Cart::where('code', $cartData['code'])->first();
}

// Получаем все родительские категории (без parent_code)
$parentCategories = CategoryCustom::whereNull('parent_code')->orderBy('some_order')->get();
// Получаем все дочерние категории
$childCategories = CategoryCustom::whereNotNull('parent_code')->orderBy('some_order')->get();

// Оптимизированная подготовка структуры категорий
$categoriesTree = [];
$allCategories = $childCategories->keyBy('code');

foreach($parentCategories as $category) {
    if($category->name == null) continue;
    
    $categoriesTree[$category->code] = [
        'category' => $category,
        'children' => []
    ];
    
    $children = $childCategories->where('parent_code', $category->code);
    foreach($children as $child) {
        $categoriesTree[$category->code]['children'][] = [
            'category' => $child,
            'children' => $childCategories->where('parent_code', $child->code)->values()->all()
        ];
    }
}
@endphp

<header class="@if(!in_array(request()->route()->getName(), ['home.default', 'home.localized'])) sticky top-0 border-2 border-blue-500 z-[9999] @endif bg-white shadow-sm" x-bind:class='body ? "!z-0" : ""'>
    <div class="flex flex-col">
        <!-- Switchers, logo and search Start -->
            <div class="xl:container xl:!mx-auto px-2 flex justify-between items-center w-full h-[62px] py-2.5">
                <a href="{{\App\Models\Navigation\Menu::getHomePageLink()}}" class="w-40 h-full items-center flex">
                    <img src="{{ asset($templateSettings['logo-image']) }}" class="w-full" alt="Logo Site">
                </a>
                <div class="flex lg:justify-end gap-3 w-full h-full !items-center">
                    <div class="lg:w-[80%] w-[95%]  mb-0 h-full items relative cursor-text lg:flex hidden" x-on:click='searchOpen = !searchOpen; body = !body'>   
                        <div class="absolute inset-y start-0 flex items-center ps-3 pointer-events-none w-full h-full">
                            <i class="bi bi-search "></i>
                        </div>
                        <button id="default-search" class="block w-full cursor-text h-full ps-10 text-sm text-start text-gray-900 border border-gray-300 rounded-lg bg-white focus:ring-blue-400 focus:border-blue-400">{{ __('template.what_are_you_looking_for') }}</button>
                    </div>

                    <div class="lg:hidden w-full">
                        @include('includes.layout.header.links')
                    </div>
            
                    <div class="lg:flex gap-3 h-full z-0 hidden">
                        @include('includes.swichers.currency')
                        @include('includes.swichers.language')
                    </div>
                </div>
            </div>
        <!-- Switchers, logo and search End -->
    
        <!-- Links Pages And Menus Start-->
            <nav class="@if(in_array(request()->route()->getName(), ['home.default', 'home.localized'])) bg-gray-100 @endif items-center h-14 lg:block hidden">
                <div class="xl:container xl:!mx-auto w-full h-full flex items-center gap-5">
                    @if (in_array(request()->route()->getName(), ['home.default', 'home.localized']))
                        <ul class="h-full">
                            <li class="h-full lg:w-52 md:w-40 w-[118px] relative">
                                <button type="button" 
                                    id="cart" 
                                    data-drawer-target="drawer-navigation" 
                                    data-drawer-show="drawer-navigation" 
                                    aria-controls="drawer-navigation"
                                    class="flex  lg:gap-3 gap-1.5 py-2 text-base font-medium h-full w-full justify-center items-center text-white bg-blue-500">
                                    <i class="bi bi-list text-xl md:block hidden"></i> {{ __('template.categories') }}
                                </button>
                                <!-- <div id="cart" class="2xl:flex hidden lg:gap-3 gap-1.5 py-2 text-base font-medium h-full w-full justify-center items-center text-white bg-blue-500">
                                    <i class="bi bi-list text-xl md:block hidden"></i> {{ __('template.categories') }}
                                </div> -->
                                <!-- paste here cat-item-backup.php 2xl:hidden-->
                                <nav class="2xl:flex hidden department-nav-menu">
                                    <ul class="nav-menu-list">
                                        @foreach($parentCategories as $category)
                                            @if($category->name == null)
                                                @continue
                                            @endif
                                            @php
                                                $categoryChildren = $childCategories->where('parent_code', $category->code)->values();
                                                $hasChildren = $categoryChildren->count() > 0;
                                                if ($hasChildren) {
                                                    $chunks = $categoryChildren->chunk(ceil($categoryChildren->count() / 3));
                                                }
                                            @endphp
                                            <li>
                                                <a href="{{ route('shop.home', ['category_parent' => $category->code]) }}" class="nav-link {{ $hasChildren ? 'has-megamenu' : '' }}">
                                                    <!-- <span class="menu-icon">
                                                        <i class="bi bi-box text-blue-400"></i>
                                                    </span> -->
                                                    <span class="menu-text">{{ $category->name }}</span>
                                                </a>
                                                @if($hasChildren)
                                                    <div class="department-megamenu">
                                                        <div class="department-megamenu-wrap">
                                                            <div class="department-submenu-wrap max-h-[70vh] overflow-y-auto scrollbar scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                                                                @foreach($chunks as $chunk)
                                                                    <div class="department-submenu dep-sub-2">
                                                                        <div class="submenu-column">
                                                                            <ul class="space-y-2 dep-sub-ul-2">
                                                                                @foreach($chunk as $child)
                                                                                    <li class="text-neutral-400">
                                                                                        <a href="{{ route('shop.home', ['category_parent' => $child->code]) }}" 
                                                                                           class="hover:text-blue-500 transition-colors duration-200">
                                                                                            {{ $child->name }}
                                                                                        </a>
                                                                                        @php
                                                                                            $subChildren = $childCategories->where('parent_code', $child->code);
                                                                                        @endphp
                                                                                        @if($subChildren->count() > 0)
                                                                                            <ul class="pl-4 mt-2 space-y-2">
                                                                                                @foreach($subChildren as $subChild)
                                                                                                    <li class="text-neutral-400">
                                                                                                        <a href="{{ route('shop.home', ['category' => $subChild->code]) }}" 
                                                                                                           class="hover:text-blue-500 transition-colors duration-200">
                                                                                                            {{ $subChild->name }}
                                                                                                        </a>
                                                                                                    </li>
                                                                                                @endforeach
                                                                                            </ul>
                                                                                        @endif
                                                                                    </li>
                                                                                @endforeach
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </nav>
                            </li>
                        </ul>
                    @endif
                    <div class="flex items-center lg:justify-between w-full">
                        <ul class="flex gap-3 font-semibold">
                            <li class="flex flex-col gap-[.5px] group">
                                <a href="{{ route('home.default') }}" class="!text-black">{{ __('template.home') }}</a>
                                <div class="h-[2px] group-hover:w-full group-hover:opacity-100 duration-500  bg-black w-1 opacity-0"></div>
                            </li>
                            <li class="flex flex-col gap-[.5px] group">
                                <a href="{{ route('shop.home') }}" class="!text-black">{{ __('template.store') }}</a>
                                <div class="h-[2px] group-hover:w-full group-hover:opacity-100 duration-500  bg-black w-1 opacity-0"></div>
                            </li>
                            @foreach($HeaderMenu->items as $link)
                                @if ($link->is_active === 1 )
                                    <li class="flex flex-col gap-[.5px] group">
                                        <a class="!text-black" href="{{ (App\Models\Page\Page::find($link->entity_id))->link }}">{{ $link->label}}</a>
                                        <div class="h-[2px] group-hover:w-full group-hover:opacity-100 duration-500  bg-black w-1 opacity-0"></div>
                                    </li>       
                                @endif
                            @endforeach 
                        </ul>
                        
                        @include('includes.layout.header.links')
                    </div>
                </div>
            </nav>
        <!-- Links Pages And Menus Start-->
    </div>
</header>


<!-- Menu Start -->
    <div id="drawer-menu" class="fixed top-0 right-0 h-screen p-4 overflow-y-auto transition-transform translate-x-full bg-white lg:w-[500px] w-72 z-[9999]" tabindex="-1" aria-labelledby="drawer-right-label">
        <div class="flex justify-between items-center border-b border-neutral-300 py-1.5 mb-3">
            <a href="{{\App\Models\Navigation\Menu::getHomePageLink()}}" class="w-40 h-full items-center flex">
                <img src="{{ asset($templateSettings['logo-image']) }}" class="w-full" alt="Logo Site">
            </a>
            <button x-on:click='body = !body' type="button" data-drawer-hide="drawer-menu" aria-controls="drawer-menu" class="bg-gray-200 rounded-full h-7 w-7 p-1.5 inline-flex items-center justify-center" >
                <i class="bi bi-x"></i>
            </button>
        </div>
        
        <div class="flex flex-col gap-9">
            <ul class="space-y-4 text-lg font-medium text-center items-center">
                <li><a href="{{ route('home.default') }}" class="!text-black hover:border-b border-neutral-400">{{ __('template.home') }}</a></li>
                <li><a href="{{ route('shop.home') }}" class="!text-black hover:border-b border-neutral-400">{{ __('template.store') }}</a></li>
                @foreach($HeaderMenu->items as $link)
                    @if ($link->is_active === 1 )
                        <li class="">
                            <a class="!text-black hover:border-b border-neutral-400" href="{{ (App\Models\Page\Page::find($link->entity_id))->link }}">{{ $link->label}}</a>
                        </li>       
                    @endif
                @endforeach 
            </ul>
            <div class="flex justify-center gap-4 w-full">
                @include('includes.swichers.currency')
                @include('includes.swichers.language')
            </div>
        </div>
    </div>
<!-- Menu End -->

<!-- Menu Cart Start -->
    <div id="drawer-right-example" 
        x-data="{ 
            isOpen: false,
            toggleDrawer() {
                this.isOpen = !this.isOpen;
                document.body.classList.toggle('overflow-y-hidden', this.isOpen);
            }
        }"
        @toggle-cart-drawer.window="toggleDrawer()"
        x-init="$watch('isOpen', value => {
            if (!value) {
                document.body.classList.remove('overflow-y-hidden');
            }
        })"
        class="fixed top-0 right-0 h-screen p-4 overflow-y-auto transition-transform translate-x-full bg-white lg:w-[500px] w-screen z-[9999]" 
        tabindex="-1" 
        aria-labelledby="drawer-right-label">
        <div class="flex justify-between items-center border-b border-neutral-300 py-4 !my-4">
            <h1 class="text-xl font-bold">{{ __('template.cart_review') }}</h1>
            <button 
                type="button" 
                data-drawer-hide="drawer-right-example" 
                aria-controls="drawer-right-example" 
                class="bg-gray-200 rounded-full h-7 w-7 p-1.5 inline-flex items-center justify-center">
                <i class="bi bi-x"></i>
            </button>
        </div>
        
        <div>
            <!-- Products Start-->
                <div id="cart_menu" class="my-3"></div>
            <!-- Products End-->
            <div class="flex flex-col gap-8">
                <ul class="flex justify-between text-xl font-bold">
                    <li>
                        <h1>{{ __('template.cart_subtotal') }}:</h1>
                    </li>
                    <li>
                        <h1 class="cart-total-price">
                            @if (session()->has('checkout.totalCost'))
                                {{ str_replace([',', '.00'], [' ', ''], Currency::format(session()->get('checkout.totalCost'))) }}
                            @else
                                @if ($cart !== null)
                                    {{null !== $cart->order ? $cart->order->total : $cart->getTotal() }}
                                @else 
                                    0
                                @endif
                            @endif
                        </h1>
                    </li>
                </ul>
                <ul class="flex lg:justify-between lg:flex-row flex-col gap-3">
                    <li class="w-full">
                        <a href="{{ route('cart.view') }}" class="block text-center bg-blue-500 text-white font-semibold  py-3 h-fit !w-full rounded-lg">{{ __('template.view_cart') }}</a>
                    </li>
                    <li class="w-full">
                        <a href="{{ route('checkout.custom.place-order') }}" class="block text-center bg-florarColor text-white font-semibold py-3 h-fit w-full rounded-lg">{{ __('template.proceed_to_checkout') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
<!-- Menu Cart End -->

<!-- Categories Menu Start -->
    <div id="drawer-navigation" class="fixed top-0 left-0 z-40 lg:w-96 md:w-80 w-72 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-white" tabindex="-1" aria-labelledby="drawer-navigation-label">
        <button x-on:click="$dispatch('toggle-cart-drawer')" x-on:click='body = !body' type="button" data-drawer-hide="drawer-navigation" aria-controls="drawer-navigation" class="text-gray-400 bg-gray-100 w-8 h-8 !text-center hover:bg-blue-500 hover:text-white rounded-lg text-base absolute justify-center top-2.5 end-2.5 flex items-center" ><i class="bi bi-x"></i></button>
        <div class="py-4 mt-4">
            <ul class="space-y-2 font-medium w-full px-4">
                @foreach($parentCategories as $category)
                    @if($category->name == null)
                        @continue
                    @endif
                    @php
                        $hasChildren = $childCategories->where('parent_code', $category->code)->count() > 0;
                    @endphp
                    <li class="flex w-full flex-col gap-3 border-t border-t-neutral-200" x-data='{open:false}'>
                        @if($hasChildren)
                            <button x-on:click='open = !open' class="h-12 w-full text-lg flex items-center justify-between xl:hover:text-black xl:group-hover:text-black">
                                <span class="flex gap-3 items-center">
                                    <i class="bi bi-box text-blue-400 xl:group-hover:text-black"></i> 
                                    {{ $category->name }}
                                </span>
                                <span x-bind:class='open ? "rotate-180" : "rotate-0" ' class="duration-300">
                                    <i class="bi bi-chevron-up text-sm"></i>
                                </span>
                            </button>
                            <div class="px-1.5 columns-1" x-show='open'>
                                @php
                                    $categoryChildren = $childCategories->where('parent_code', $category->code);
                                @endphp
                                <ul class="gap-1 break-inside-avoid mb-3 lg:text-base md:text-lg text-base space-y-2">
                                    @foreach($categoryChildren as $child)
                                        <li class="text-neutral-400">
                                            <a href="{{ route('shop.home', ['category' => $child->code]) }}">{{ $child->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <a href="{{ route('shop.home', ['category_parent' => $category->code]) }}" class="w-full h-12 flex items-center gap-3 xl:hover:text-black text-lg">
                                <i class="bi bi-box text-blue-400"></i> {{ $category->name }}
                            </a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
<!-- Categories Menu End -->

<script type="module">
    $(document).ready(function () {
        $('select[name="currency"]').on('change', function () {
            $(this).closest('form').submit();
        });

        $('select[name="locale"]').on('change', function () {
            $(this).closest('form').submit();
        });
    });
</script>