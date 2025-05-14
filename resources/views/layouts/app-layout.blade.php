 @php 
    //@vite fails, builded assets loaded in alternative way
     $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
     $cssFile = $manifest['resources/css/app.css']['file'] ?? '';
     $jsFile = $manifest['resources/js/app.js']['file'] ?? '';

@endphp 
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="application-name" content="{{ config('app.name') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <title>{{ config('app.name') }} - @yield('title')</title>
        <link rel="icon" type="image/png" href="{{ asset($templateSettings['homepage-meta-image-image']) }}">

        @yield('meta')

        <!-- Template CSS Assets -->
            <link rel="stylesheet" href="{{ asset('template/css/font-icons.css') }}">
            <link rel="stylesheet" href="{{ asset('template/css/plugins.css') }}">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.css" integrity="sha512-oe8OpYjBaDWPt2VmSFR+qYOdnTjeV9QPLJUeqZyprDEQvQLJ9C5PCFclxwNuvb/GQgQngdCXzKSFltuHD3eCxA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css" integrity="sha512-f0tzWhCwVFS3WeYaofoLWkTP62ObhewQ1EZn65oSYDZUg1+CyywGKkWzm8BxaJj5HGKI72PnMH9jYyIFz+GH7g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
            {{-- <link rel="stylesheet" href="{{ asset('template/css/style.css') }}"> --}}
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" integrity="sha512-dPXYcDub/aeb08c63jRq/k6GaKccl256JQy/AnOq7CAnEZ9FzSL9wSbcZkMp4R26vBsMLFYH4kQ67/bbV8XaCQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
            <link rel="stylesheet" href="{{ asset('template/css/responsive.css') }}">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
            <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
            <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
        <!-- End Template CSS Assets -->
        <style>[x-cloak] { display: none !important; }</style>
        
        @livewireStyles
        @filamentStyles
        @vite('resources/css/app.css')

        <link rel="stylesheet" href="{{ asset('build/' . $cssFile) }}">
        @yield('microdata')
    </head>
    <body class="relative" x-data="{body:false, searchOpen:false}" x-bind:class="body ? 'overflow-y-hidden' : 'overflow-y-auto'">
        <!-- Body main Start-->
            <!-- Header Start-->
                @if (!in_array(request()->path(), ['auth/view']))
                    @include('includes.layout.header.index')
                @endif
                <!-- Header End-->

            <!-- Show Elements Start-->
                {{ $slot }}
            <!-- Show Elements End-->

            <!-- Footer Start-->
                @if (!in_array(request()->path(), ['auth/view']))
                    @include('includes.layout.footer.index')
                @endif
            <!-- Footer End-->


            <!-- Search Popup Start -->
                @include('includes.products.item.search-popup')
            <!-- Search Popup End -->

            <!-- Modal Show Product Start-->
                <div 
                    {{-- x-bind:class=" open ? 'opacity-100' : 'opacity-0' "  --}}
                    {{-- x-bind:style="open ? 'visibility: visible' : 'visibility: hidden'"  --}}
                    class="fixed h-full top-[env(safe-area-inset-top)] right-0 left-0 bottom-[env(safe-area-inset-bottom)] duration-500 bg-black bg-opacity-50 items-center justify-center z-50 xl:flex hidden"
                    style="visibility: hidden; opacity: 0;"
                    x-cloak
                    id="popup">
                    @include('includes.products.item.popap')
                </div>
                
            <!-- Modal Show Product End-->

        <!-- Body main end -->

        <!-- preloader area start -->
            <div class="preloader inset-0 fixed w-full h-full bg-white z-[9999] flex items-center justify-center text-black" id="preloader">
                <div class="2xl:w-80 2xl:h-80 xl:w-52 xl:h-52 lg:w-48 lg:h-48  w-40 h-40">
                    <img src="{{ asset($templateSettings['logo-image']) }}" class="h-full w-full object-contain" alt="Logo Florar">
                </div>                       
            </div>
        <!-- preloader area end -->

        <!-- Success Alert Start -->
        @if(session('success'))
            <script type="module">
                toastr.success("{{ session('success') }}");
            </script>
        @endif
        <!-- Success Alert End -->

        @livewire('notifications')
        @auth
            @include('includes.scripts.cart-scripts')
        @endauth
        @include('includes.scripts.product')
        @include('includes.scripts.add-to-cart')
        @include('includes.scripts.add-to-follow')
        
        <!-- Template JS Assets -->
            <script src="https://cdn.jsdelivr.net/npm/countdown"></script>
            <script src="{{ asset('template/js/plugins.js') }}"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script src="{{ asset('template/js/main.js') }}"></script>
            <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
            <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js" integrity="sha512-lbwH47l/tPXJYG9AcFNoJaTMhGvYWhVM9YI43CT+uteTRRaiLCui8snIgyAN8XWgNjNhCqlAUdzZptso6OCoFQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script>
                AOS.init();

                toastr.options.positionClass = "toast-bottom-right";
                toastr.options.progressBar = true;
            </script>
            <!--Start of Tawk.to Script-->
                {{-- <script type="text/javascript">
                var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
                (function(){
                var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
                s1.async=true;
                s1.src='https://embed.tawk.to/679baee4825083258e0df205/1iis26bra';
                s1.charset='UTF-8';
                s1.setAttribute('crossorigin','*');
                s0.parentNode.insertBefore(s1,s0);
                })();
                </script> --}}
            <!--End of Tawk.to Script-->
        <!-- End Template JS Assets -->

        @livewireScripts
        @filamentScripts
        @vite('resources/js/app.js')

        <script src="{{ asset('build/' . $jsFile) }}" defer></script>
        <script src="//unpkg.com/alpinejs" defer></script>
    </body>
</html>
