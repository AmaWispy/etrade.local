<x-app-layout class="xl:mx-0 mx-4">
    @section('title', $product->name . ' - ' . __('template.not_available'))
    
    <div class="mt-16">
        <div class="container">
            <!-- Product Suspended Message Start -->
            <div class="bg-gradient-to-br from-orange-50 to-red-50 border-orange-200 rounded-xl p-8 text-center mb-8">
                <div class="flex flex-col items-center gap-4">
                    <!-- Icon -->
                    <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    
                    <!-- Product Info -->
                    <div class="flex flex-col gap-2">
                        <h1 class="text-2xl font-bold text-gray-800">{{ $product->name }}</h1>
                        <p class="text-orange-600 font-medium text-lg">
                            {{ $message[app()->getLocale()] ?? $message['en'] }}
                        </p>
                        <p class="text-gray-600 text-sm">
                            {{ __('template.sku') }}: {{ $product->sku }}
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 flex-wrap justify-center mt-4">
                        <a href="{{ route('shop.home') }}" class="bg-blue-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-600 transition-colors">
                            {{ __('template.back_to_shopping') }}
                        </a>
                        @if($alternatives->count() > 0)
                            <button onclick="document.getElementById('alternatives').scrollIntoView({behavior: 'smooth'})" class="bg-green-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-green-600 transition-colors">
                                {{ __('template.view_alternatives') }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Product Suspended Message End -->

            <!-- Alternative Products Start -->
            @if($alternatives->count() > 0)
                <div id="alternatives" class="mb-12">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-800 mb-2">{{ __('template.alternative_products') }}</h2>
                        <p class="text-gray-600">{{ __('template.you_might_also_like') }}</p>
                    </div>

                    <div class="grid xl:grid-cols-3 lg:grid-cols-2 md:grid-cols-2 sm:grid-cols-1 gap-6">
                        @foreach($alternatives as $alternative)
                            <div class="group w-full h-fit rounded-lg transition-all duration-300 hover:shadow-lg">
                                @include('includes.products.item.default', ['product' => $alternative])
                            </div>
                        @endforeach
                    </div>

                    @if($alternatives->count() >= 6)
                        <div class="text-center mt-8">
                            <a href="{{ route('shop.home') }}?category={{ $product->category_code }}" class="bg-gray-100 text-gray-700 px-8 py-3 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                                {{ __('template.view_all_products') }}
                            </a>
                        </div>
                    @endif
                </div>
            @endif
            <!-- Alternative Products End -->

            <!-- Help Section Start -->
            
            <!-- Help Section End -->
        </div>
    </div>
</x-app-layout> 