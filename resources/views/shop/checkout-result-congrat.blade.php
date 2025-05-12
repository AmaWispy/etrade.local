<x-app-layout>

    @section('title', __('template.checkout'))

    <!-- BREADCRUMB AREA START -->
        <div class="container mb-4">
            <nav class='inline-flex items-center text-sm'>
                <ul class="inline-flex items-center gap-2 ">
                    <li>
                        <h1 class="font-semibold">{{ __('template.you_are_here') }}</h1>
                    </li>
                    <li>
                        <a class="hover:text-black hover:font-medium" href="{{ \App\Models\Navigation\Menu::getHomePageLink() }}">{{ __('template.home') }}</a>
                    </li>
                    <li>
                        <h1 class="text-neutral-500">/</h1>
                    </li>
                    <li>
                        <a class="hover:text-black hover:font-medium" href="{{ route('shop.home') }}">{{__('template.shop')}}</a>
                    </li>
                    <li>
                        <h1 class="text-neutral-500">/</h1>
                    </li>
                    <li>
                        <h1 class="hover:text-black hover:font-medium">{{__('template.checkout')}}</h1>
                    </li>
                </ul>
            </nav>
        </div>
    <!-- BREADCRUMB AREA END  -->

    <!-- CHECKOUT AREA START -->
    <div class="ltn__checkout-area mb-100">
        <div class="container">
            <div class="flex justify-center text-xl flex-col items-center">
                        <div class="mb-100">
                            <h1>{{__('template.order_paid', ['order' => $cart->orderCustom->id])}}</h1>
                            <h3>{{__('template.we_will_contact_you')}}</h3>
                            <h3>{{__('template.order_thanks')}}</h3>
                        </div>
                @include('includes.buttons.shopping')
            </div>
        </div>
    </div>
    
    <!-- <script type="module">
        // Function to refresh the page every 5 seconds
        function autoRefresh() {
            setTimeout(function () {
                location.reload();
            }, 5000); // 5000 milliseconds = 5 seconds
        }

        // Call the function when the page is ready
        document.addEventListener('DOMContentLoaded', autoRefresh);
    </script> -->

</x-app-layout>