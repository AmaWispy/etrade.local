<div class="container my-5">
    <div class="text-center">
        <h1 class="mb-3 md:text-xl text-lg font-semibold">{{__('template.cart_is_empty')}}!</h1>
        <a class="border bg-blue-500 xl:hover:bg-blue-600 text-semibold text-white md:text-lg text-sm px-3 py-2 rounded-lg " href="{{ route('shop.home') }}">
            {{__('template.continue_shopping')}}
        </a>
    </div>
</div>