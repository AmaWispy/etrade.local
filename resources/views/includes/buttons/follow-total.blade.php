<span class="mini-cart-icon flex items-center">
    <i class="duration-[.4s] bi bi-heart hover:!text-florarColor"></i>
    @if (request()->path() !== 'follow/view')
        <p class="duration-[.4s]" data-counter="follow-total-items">{{ session()->has('follow') ? session()->get('follow')['totalItems'] : 0 }}</p> 
    @endif
</span>