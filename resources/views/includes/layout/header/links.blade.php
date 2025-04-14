{{-- <!-- Under Header Links Start-->
    <div class="flex flex-col sm:gap-1 border-b-neutral-300 border-b mb-2 mt-1">
        <!-- Florar Logo and Banner Start-->
            <div class="flex justify-center items-center xl:gap-2 2xl:gap-4">
                <!-- Logo Start-->
                    <a href="{{\App\Models\Navigation\Menu::getHomePageLink()}}" class="bg-florarColor xl:ml-2 xl:pt-6 2xl:pt-9 xl:w-32 xl:h-32 2xl:h-48 2xl:w-48 flex flex-col items-center justify-center rounded-full sm:hidden xl:inline">
                        <img src="{{ asset('template/images/VectorFlorar.png') }}" class="xl:h-12 2xl:h-20 2xl:ml-10 xl:ml-7" alt="Logo Florar">
                        <span class="flex flex-col text-center !text-white">
                            <span class="font-medium 2xl:text-xl">Florar.md</span>
                            <span class="text-[7px] font-medium 2xl:text-[10px]">Flowers shop</span>
                        </span>
                    </a>
                <!-- Logo End-->

                <!-- Banner Start-->
                    <div>
                        <div class="sm:px-1 lg:px-4 xl:w-[850px] 2xl:w-[1200px]">
                            <img src="{{ asset('template/images/Banner.png') }}" class="rounded-xl sm:h-18 !w-full 2xl:w-auto object-cover" alt="">
                        </div>
                    </div>
                <!-- Banner End-->
            </div>
        <!-- Florar Logo and Banner End-->

        <!-- Category Links Start-->
            <div class="xl:container xl:mx-auto flex lg:gap-10 sm:gap-2 2xl:justify-center w-full ltn_scrollbar_category py-3">
                @foreach(\App\Models\Shop\Category::getTree() as $category)
                        <a href="{{$category['link']}}" class="text-center flex items-center justify-center flex-col touch-manipulation">
                            <div class="rounded-full @if ('/'.request()->path() === $category['link'])  border-florarColor @endif duration-[.3s] border-dashed hover:border-florarColor border-2  p-1">
                                <img src="{{ asset( $category['icon']) }}" class="rounded-full object-cover lg:h-20 lg:w-20 sm:h-16 sm:w-16" alt="">
                            </div>
                            <div class="w-28">
                                <span class="block truncate @if ('/'.request()->path() === $category['link']) duration-[.3s] text-florarColor @endif">
                                    {{$category['name']}} 
                                </span>
                            </div>
                        </a>
                @endforeach
            </div>
        <!-- Category Links End-->
    </div>
<!-- Under Header Links End--> --}}