<div class="bg-neutral-100 py-5 mb-5">
    <div class="container flex justify-between">
        <div class="w-full">
            <!-- Modern Breadcrumbs Navigation -->
            <nav class='flex items-center text-sm mb-4 bg-white px-4 py-2 rounded-lg shadow-sm border'>
                <div class="flex items-center flex-wrap gap-1">
                    @if(isset($category) && !empty($category->code))
                        <a href="{{ route('shop.home') }}" class="text-gray-600 hover:text-blue-600 transition-colors duration-200 font-medium flex items-center">
                            <i class="bi bi-house-door mr-1"></i>{{ __('template.store') }}
                        </a>
                        
                        @foreach($category->breadcrumbs as $index => $breadcrumb)
                            <i class="bi bi-chevron-right text-gray-400 text-xs mx-1"></i>
                            @if($loop->last)
                                <span class="text-blue-600 font-semibold truncate max-w-[120px] sm:max-w-none" 
                                      title="{{ $breadcrumb->localized_name }}">
                                    {{ $breadcrumb->localized_name }}
                                </span>
                            @else
                                <a href="{{ route('shop.home', ['parent_category' => $breadcrumb->code]) }}" 
                                   class="text-gray-600 hover:text-blue-600 transition-colors duration-200 font-medium truncate max-w-[120px] sm:max-w-none"
                                   title="{{ $breadcrumb->localized_name }}">
                                    {{ $breadcrumb->localized_name }}
                                </a>
                            @endif
                        @endforeach
                    @else
                        <span class="text-blue-600 font-semibold flex items-center">
                            <i class="bi bi-house-door mr-1"></i>{{ __('template.store') }}
                        </span>
                    @endif
                </div>
            </nav>
            
            <!-- Page Title -->
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-semibold text-gray-800">
                @if(isset($category) && $category)
                    {{ $category->localized_name }}
                @else
                    {{ $page['title'] ?? $title }}
                @endif
            </h1>
        </div>
        
        <!-- Decorative Image (Desktop Only) -->
        <!-- <div class="relative hidden lg:block mr-10">
            <div class="h-28 bg-white w-28 rounded-full shadow-lg"></div>
            <div class="absolute top-2 -right-8">
                <img src="{{ asset($templateSettings['bread-crump-image-image']) }}" class="h-28" alt="">
            </div>
        </div> -->
    </div>
</div>