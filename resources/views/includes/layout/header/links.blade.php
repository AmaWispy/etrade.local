<ul class="flex items-center lg:w-auto w-full lg:justify-normal justify-end gap-2 text-xl">
    <li>
        <button x-on:click='searchOpen = !searchOpen; body = !body'
            class="duration-500 text-center xl:hover:bg-florarColor xl:hover:text-white rounded-full flex items-center justify-center p-2 w-9 h-9">
            <i class="bi bi-search "></i>
        </button>
    </li>
    @if(Auth::guard('client')->check())
        <li>
            <a href="{{ route('follow.view') }}" 
                class="duration-500 xl:hover:bg-florarColor xl:hover:text-white rounded-full flex items-center text-center justify-center p-2 w-9 h-9">
                <i class="bi bi-heart"></i>
            </a>
        </li>
    @endif
    @if(Auth::guard('client')->check())
        <li class="relative">
            <button type="button" 
                id="cart" 
                x-on:click="$dispatch('toggle-cart-drawer')"
                data-drawer-target="drawer-right-example" 
                data-drawer-show="drawer-right-example" 
                data-drawer-placement="right" 
                aria-controls="drawer-right-example" 
                class="duration-500 text-center xl:hover:bg-florarColor xl:hover:text-white rounded-full flex items-center justify-center p-2 w-9 h-9">
                <i class="bi bi-cart3"></i>
            </button>
            @if(isset($cart))
                <div class="cart-count cart-count-indicator" id="cart-count-header">
                    {{ session('cart') ? session('cart')['totalItems'] : 0 }}
                </div>
            @endif
        </li>
    @endif
    <li>
        <a href="{{ Auth::guard('client')->check() ? route('custom.profile') : route('custom.login') }}"
            class="duration-500 text-center xl:hover:bg-florarColor xl:hover:text-white rounded-full flex items-center justify-center p-2 w-9 h-9">
            <i class="bi bi-person"></i>
        </a>
    </li>
    <li class="lg:hidden">
        <button type="button" id="menu" 
            x-on:click='body = !body'
            data-drawer-target="drawer-menu" 
            data-drawer-show="drawer-menu" 
            data-drawer-placement="right" 
            aria-controls="drawer-menu" 
            class="duration-500 text-center xl:hover:bg-florarColor xl:hover:text-white rounded-full flex items-center justify-center p-2 w-9 h-9">
            <i class="bi bi-list text-xl"></i>
        </button>
    </li>
</ul>