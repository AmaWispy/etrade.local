<div class="flex flex-col w-32 ml-2" x-data='{check: false}' id="item-{{ $item->id }}-rec">
    <!-- Image Product Start-->
        <div class="lg:!h-40 w-full">
            <img src="{{ asset($item->getThumb()) }}" class="w-full h-full object-cover" alt="{{ $item->name }}">
        </div>
    <!-- Image Product End-->

    <!-- Sku and Title Product Start-->
    <div class="xl:w-36 lg:w-52 sm:w-32 md:w-44 mt-2">
        <h1 class="font-semibold text-sm">{{ $item->sku }}</h1>
        <p class="text-neutral-500 font-normal text-sm truncate">{{ $item->name }}</p>
    </div>
    <!-- Sku and Title Product End-->

    <!-- Price and Add To Cart Btn Start-->
        <div id="box-check-add-{{ $item->id }}" class="flex justify-between items-center bg-pink-100/40 px-3 py-2 rounded-lg mt-3">
            <h1 class="font-semibold">{{ $item->getExchangedPrice() }}</h1>

            <h1 id="check-rec-{{ $item->id }}" class="text-lg bg-florarColor rounded-full w-7 h-7  items-center  @if ($cartProduct->where('shop_product_id', $item->id)->first()) inline-flex @else hidden @endif justify-center pt-0.5 text-white">
                <i class="bi bi-check-lg"></i>
            </h1>
            <a href="#" 
                id="btn-rec"
                data-action="add-to-cart"
                data-action-2="update-cart-init"
                data-product="{{$item->id}}"
                data-type="{{$item->type}}"
                class="add-rec-{{ $item->id }} text-lg bg-pink-200 rounded-full w-7 h-7 cursor-pointer pt-0.5 justify-center items-center text-black @if ($cartProduct->where('shop_product_id', $item->id)->first()) hidden @else inline-flex @endif">
                <i class="bi bi-plus-lg !font-extrabold"></i>
            </a>
        </div>
    <!-- Price and Add To Cart Btn End-->
</div>