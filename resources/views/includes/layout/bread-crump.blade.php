<div class="bg-neutral-100 py-5 mb-5">
    <div class="container flex  justify-between">
        <div>
            <nav class='inline-flex items-center text-[16px] mb-2'>
                <ul class="inline-flex items-center gap-2 font-medium">
                    <li>
                        <a href="{{ \App\Models\Navigation\Menu::getHomePageLink() }}">{{ __('template.home') }}</a>
                    </li>
                    <li>
                        <h1 class="text-neutral-500">|</h1>
                    </li>
                    <li>
                        <h1 class="text-blue-500">{{ $page['title'] ?? $title }}</h1>
                    </li>
                </ul>
            </nav>
            <h1 class="text-4xl font-semibold">{{ $page['title'] ?? $title }}</h1>
        </div>
        <div class="relative hidden lg:block mr-10">
            <div class="h-28 bg-white w-28 rounded-full"></div>
            <div class="absolute top-2 -right-8">
                <img src="{{ asset($templateSettings['bread-crump-image-image']) }}" class="h-28" alt="">
            </div>
        </div>
    </div>
</div>