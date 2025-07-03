<div class="bg-neutral-100 py-5 mb-5">
    <div class="container flex  justify-between">
        <div>
            <nav class='inline-flex items-center text-[16px] mb-2'>
                <ul class="inline-flex items-center gap-2 font-medium">
                    @if(isset($category) && !empty($category->code))
                        <li>
                            <a href="{{ route('shop.home') }}">{{ __('template.store') }}</a>
                        </li>
                        <li>
                            <h1 class="text-neutral-500">|</h1>
                        </li>
                        @foreach($category->breadcrumbs as $index => $breadcrumb)
                            <li>
                                @if($loop->last)
                                    <h1 class="text-blue-500">{{ $breadcrumb->localized_name }}</h1>
                                @else
                                    <a href="{{ route('shop.home', ['parent_category' => $breadcrumb->code]) }}" class="hover:text-blue-500">
                                        {{ $breadcrumb->localized_name }}
                                    </a>
                                @endif
                            </li>
                            @if(!$loop->last)
                                <li>
                                    <h1 class="text-neutral-500">|</h1>
                                </li>
                            @endif
                        @endforeach
                    @else
                        <li>
                            <h1 class="text-blue-500">{{ __('template.store') }}</h1>
                        </li>
                    @endif
                </ul>
            </nav>
            <h1 class="text-4xl font-semibold">
                @if(isset($category) && $category)
                    {{ $category->localized_name }}
                @else
                    {{ $page['title'] ?? $title }}
                @endif
            </h1>
        </div>
        <div class="relative hidden lg:block mr-10">
            <div class="h-28 bg-white w-28 rounded-full"></div>
            <div class="absolute top-2 -right-8">
                <img src="{{ asset($templateSettings['bread-crump-image-image']) }}" class="h-28" alt="">
            </div>
        </div>
    </div>
</div>

<style>
    .bread-crump-image {
    }
</style>