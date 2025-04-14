<span class="mini-cart-icon flex items-center">
    <i class="duration-[.4s] bi bi-bag hover:!text-florarColor"></i>
    @if (request()->path() !== 'cart/view')
        {{-- <sup data-counter="cart-total-items">
            {{ session()->has('cart') ? session()->get('cart')['totalItems'] : 0 }}
        </sup> --}}
        <p class="duration-[.4s]" data-counter="cart-total-price">{{ $total }}</p> 
    @endif
</span>
