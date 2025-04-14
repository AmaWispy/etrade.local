<!-- PRODUCT AREA START -->
    <div class="grid xl:gap-4 2xl:gap-2 sm:gap-2 sm:grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 items-center container mb-4 !px-0">
        @foreach($products as $product)
            @include('includes.products.item.default', ['product' => $product])
        @endforeach
    </div>
<!-- PRODUCT AREA END -->