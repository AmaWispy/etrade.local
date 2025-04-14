<x-app-layout>

    @section('title', __('template.shop'))

    <!-- BREADCRUMB AREA START -->
        @include('includes.layout.bread-crump', ['title' => __('template.shop')])
    <!-- BREADCRUMB AREA END -->

    <!-- PRODUCT DETAILS AREA START -->
        <div class="flex flex-col gap-5 items-center xl:container xl:!mx-auto mx-2">
            <!-- Products and Filters Start -->
                <div class="flex w-full gap-10">
                    <!-- Filter Block Start -->
                        <div class="2xl:w-[20%] xl:w-[25%] xl:block hidden">
                            {{-- #TODO:: Добавить фильтры после обновления дб  --}}
                            {{-- @include('includes.products.filter.layout') --}}

                            {{-- Пример Дб --}}
                            <div class="space-y-3 ">
                                <div x-data='{plus:true}'>
                                    <div class="space-y-2">
                                        <button x-on:click='plus = !plus' class="flex w-full text-xl font-semibold justify-between">
                                            <span>
                                                {{ __('template.categories') }}
                                            </span>
                                            <span x-cloak>
                                                <i class="bi bi-plus-lg" x-show='plus'></i>
                                                <i class="bi bi-dash-lg" x-show='!plus'></i>
                                            </span>
                                        </button>
                                        <div class="relative">
                                            <div class="h-[2px] bg-neutral-300 w-full"></div>
                                            <div x-cloak x-bind:class='plus ? "w-0" : "w-full"  ' class="absolute top-0 duration-300 h-[2px] bg-blue-500 w-full"></div>
                                        </div>
                                    </div>
                                    <div class="mt-3 overflow-hidden duration-300 " x-bind:class="!plus ? 'h-40' : 'h-0' " x-cloak>
                                        <form action="" class="text-neutral-500 font-semibold text-lg space-y-3 overflow-y-auto h-full">
                                            <label for="Example" class="flex items-center gap-2">
                                                <input type="radio" id="Example" name="Example" value="Example">
                                                Example
                                            </label>
                                            <label for="Example" class="flex items-center gap-2">
                                                <input type="radio" id="Example" name="Example" value="Example">
                                                Example
                                            </label>
                                            <label for="Example" class="flex items-center gap-2">
                                                <input type="radio" id="Example" name="Example" value="Example">
                                                Example
                                            </label>
                                            <label for="Example" class="flex items-center gap-2">
                                                <input type="radio" id="Example" name="Example" value="Example">
                                                Example
                                            </label>
                                            <label for="Example" class="flex items-center gap-2">
                                                <input type="radio" id="Example" name="Example" value="Example">
                                                Example
                                            </label>
                                            <label for="Example" class="flex items-center gap-2">
                                                <input type="radio" id="Example" name="Example" value="Example">
                                                Example
                                            </label>
                                        </form>
                                    </div>
                                </div>
                                <div x-data='{plus:true}'>
                                    <div class="space-y-2">
                                        <button x-on:click='plus = !plus' class="flex w-full text-xl font-semibold justify-between">
                                            <span>
                                                {{ __('template.categories') }}
                                            </span>
                                            <span x-cloak>
                                                <i class="bi bi-plus-lg" x-show='plus'></i>
                                                <i class="bi bi-dash-lg" x-show='!plus'></i>
                                            </span>
                                        </button>
                                        <div class="relative">
                                            <div class="h-[2px] bg-neutral-300 w-full"></div>
                                            <div x-cloak x-bind:class='plus ? "w-0" : "w-full"  ' class="absolute top-0 duration-300 h-[2px] bg-blue-500 w-full"></div>
                                        </div>
                                    </div>
                                    <div class="mt-3 overflow-hidden duration-300 " x-bind:class="!plus ? 'h-40' : 'h-0' " x-cloak>
                                        <form action="" class="text-neutral-500 font-semibold text-lg space-y-3 overflow-y-auto h-full">
                                            <label for="Example" class="flex items-center gap-2">
                                                <input type="radio" id="Example" name="Example" value="Example">
                                                Example
                                            </label>
                                            <label for="Example" class="flex items-center gap-2">
                                                <input type="radio" id="Example" name="Example" value="Example">
                                                Example
                                            </label>
                                            <label for="Example" class="flex items-center gap-2">
                                                <input type="radio" id="Example" name="Example" value="Example">
                                                Example
                                            </label>
                                            <label for="Example" class="flex items-center gap-2">
                                                <input type="radio" id="Example" name="Example" value="Example">
                                                Example
                                            </label>
                                            <label for="Example" class="flex items-center gap-2">
                                                <input type="radio" id="Example" name="Example" value="Example">
                                                Example
                                            </label>
                                            <label for="Example" class="flex items-center gap-2">
                                                <input type="radio" id="Example" name="Example" value="Example">
                                                Example
                                            </label>
                                        </form>
                                    </div>
                                </div>
                                <div x-data='{plus:true}'>
                                    <div class="space-y-2">
                                        <button x-on:click='plus = !plus' class="flex w-full text-xl font-semibold justify-between">
                                            <span>
                                                {{ __('template.categories') }}
                                            </span>
                                            <span x-cloak>
                                                <i class="bi bi-plus-lg" x-show='plus'></i>
                                                <i class="bi bi-dash-lg" x-show='!plus'></i>
                                            </span>
                                        </button>
                                        <div class="relative">
                                            <div class="h-[2px] bg-neutral-300 w-full"></div>
                                            <div x-cloak x-bind:class='plus ? "w-0" : "w-full"  ' class="absolute top-0 duration-300 h-[2px] bg-blue-500 w-full"></div>
                                        </div>
                                    </div>
                                    <div class="mt-3 overflow-hidden duration-300 " x-bind:class="!plus ? 'h-40' : 'h-0' " x-cloak>
                                        <form action="" class="text-neutral-500 font-semibold text-lg space-y-3 overflow-y-auto h-full">
                                            <label for="Example" class="flex items-center gap-2">
                                                <input type="radio" id="Example" name="Example" value="Example">
                                                Example
                                            </label>
                                            <label for="Example" class="flex items-center gap-2">
                                                <input type="radio" id="Example" name="Example" value="Example">
                                                Example
                                            </label>
                                            <label for="Example" class="flex items-center gap-2">
                                                <input type="radio" id="Example" name="Example" value="Example">
                                                Example
                                            </label>
                                            <label for="Example" class="flex items-center gap-2">
                                                <input type="radio" id="Example" name="Example" value="Example">
                                                Example
                                            </label>
                                            <label for="Example" class="flex items-center gap-2">
                                                <input type="radio" id="Example" name="Example" value="Example">
                                                Example
                                            </label>
                                            <label for="Example" class="flex items-center gap-2">
                                                <input type="radio" id="Example" name="Example" value="Example">
                                                Example
                                            </label>
                                        </form>
                                    </div>
                                </div>
                                <div x-data='{plus:true}'>
                                    <div class="space-y-2">
                                        <button x-cloak x-on:click='plus = !plus' class="flex w-full text-xl font-semibold justify-between">
                                            <span>
                                                {{ __('template.categories') }}
                                            </span>
                                            <span x-cloak>
                                                <i class="bi bi-plus-lg" x-show='plus'></i>
                                                <i class="bi bi-dash-lg" x-show='!plus'></i>
                                            </span>
                                        </button>
                                        <div class="relative">
                                            <div class="h-[2px] bg-neutral-300 w-full"></div>
                                            <div x-cloak x-bind:class='plus ? "w-0" : "w-full"  ' class="absolute top-0 duration-300 h-[2px] bg-blue-500 w-full"></div>
                                        </div>
                                    </div>
                                    <div class="mt-3 overflow-hidden duration-300 " x-bind:class="!plus ? 'h-40' : 'h-0' " x-cloak>
                                        <form action="" class="text-neutral-500 font-semibold text-lg space-y-3 overflow-y-auto h-full">
                                            <label for="Example" class="flex items-center gap-2">
                                                <input type="radio" id="Example" name="Example" value="Example">
                                                Example
                                            </label>
                                            <label for="Example" class="flex items-center gap-2">
                                                <input type="radio" id="Example" name="Example" value="Example">
                                                Example
                                            </label>
                                            <label for="Example" class="flex items-center gap-2">
                                                <input type="radio" id="Example" name="Example" value="Example">
                                                Example
                                            </label>
                                            <label for="Example" class="flex items-center gap-2">
                                                <input type="radio" id="Example" name="Example" value="Example">
                                                Example
                                            </label>
                                            <label for="Example" class="flex items-center gap-2">
                                                <input type="radio" id="Example" name="Example" value="Example">
                                                Example
                                            </label>
                                            <label for="Example" class="flex items-center gap-2">
                                                <input type="radio" id="Example" name="Example" value="Example">
                                                Example
                                            </label>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    <!-- Filter Block End -->

                    <!-- Products Block Start -->
                        <div class="flex flex-col gap-5 z-0 w-full">
                            <div class="flex xl:items-center gap-4 justify-end xl:flex-row flex-col w-full xl:w-auto">
                                @include('includes.results', [
                                    'on_page' => $products->count(),
                                    'total' => $products->total()
                                ])

                                @include('includes.products.filter.sort.default', [
                                    'sorting' => $sorting
                                ])
                                
                                <div class="xl:hidden block mx-4">
                                    <button                                 
                                        type="button" 
                                        id="menu" 
                                        data-drawer-target="filter_menu" 
                                        data-drawer-show="filter_menu" 
                                        data-drawer-placement="left" 
                                        aria-controls="filter_menu"
                                        class="duration-500 text-center text-base font-semibold xl:hover:bg-florarColor xl:hover:text-white rounded-full flex items-center justify-center p-2 w-9 h-9"
                                        >
                                        <i class="bi bi-funnel"></i>{{ __('template.filter') }}
                                    </button>
                                </div>
                            </div>
                            @include('includes.products.grid.default', [
                                'product' => $products    
                            ])
                        </div>
                    <!-- Products Block End -->
                </div>
            <!-- Products and Filters Ent -->

            <!-- Products Pages Start -->
                @if($products->hasPages() > 0)
                    <div class="inline lg:hidden">
                        {!! $products->links('pagination.default') !!}
                    </div>
                    <div class="hidden lg:inline xl:hidden">
                        {!! $products->onEachSide(4)->links('pagination.default') !!}
                    </div>
                    <div class="hidden xl:inline">
                        {!! $products->onEachSide(5)->links('pagination.default') !!}
                    </div>
                @endif
            <!-- Products Pages End -->
        </div>
    <!-- PRODUCT DETAILS AREA START -->

    <!-- Menu Start -->
        <div id="filter_menu" class="fixed top-0 left-0 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-white lg:w-[500px] w-72 z-[9999]" tabindex="-1" aria-labelledby="drawer-right-label">
            <div class="flex justify-between items-center py-1.5 mb-3">
                <button x-on:click='body = !body' type="button" data-drawer-hide="filter_menu" aria-controls="filter_menu" class="bg-gray-200 rounded-full h-7 w-7 p-1.5 inline-flex items-center justify-center" >
                    <i class="bi bi-x"></i>
                </button>
            </div>
            
            <div class="flex flex-col gap-9 mt-5">
                <div x-data='{plus:true}'>
                    <div class="space-y-2">
                        <button x-on:click='plus = !plus' class="flex w-full text-xl font-semibold justify-between">
                            <span>
                                {{ __('template.categories') }}
                            </span>
                            <span x-cloak>
                                <i class="bi bi-plus-lg" x-show='plus'></i>
                                <i class="bi bi-dash-lg" x-show='!plus'></i>
                            </span>
                        </button>
                        <div class="relative">
                            <div class="h-[2px] bg-neutral-300 w-full"></div>
                            <div x-cloak x-bind:class='plus ? "w-0" : "w-full"  ' class="absolute top-0 duration-300 h-[2px] bg-blue-500 w-full"></div>
                        </div>
                    </div>
                    <div class="mt-3 overflow-hidden duration-300 " x-bind:class="!plus ? 'h-40' : 'h-0' " x-cloak>
                        <form action="" class="text-neutral-500 font-semibold text-lg space-y-3 overflow-y-auto h-full">
                            <label for="Example" class="flex items-center gap-2">
                                <input type="radio" id="Example" name="Example" value="Example">
                                Example
                            </label>
                            <label for="Example" class="flex items-center gap-2">
                                <input type="radio" id="Example" name="Example" value="Example">
                                Example
                            </label>
                            <label for="Example" class="flex items-center gap-2">
                                <input type="radio" id="Example" name="Example" value="Example">
                                Example
                            </label>
                            <label for="Example" class="flex items-center gap-2">
                                <input type="radio" id="Example" name="Example" value="Example">
                                Example
                            </label>
                            <label for="Example" class="flex items-center gap-2">
                                <input type="radio" id="Example" name="Example" value="Example">
                                Example
                            </label>
                            <label for="Example" class="flex items-center gap-2">
                                <input type="radio" id="Example" name="Example" value="Example">
                                Example
                            </label>
                        </form>
                    </div>
                </div>
                <button class="w-full font-semibold text-base h-10 rounded-lg text-white bg-blue-500">{{ __('template.view_all') }}</button>
            </div>
        </div>
    <!-- Menu End -->
</x-app-layout>