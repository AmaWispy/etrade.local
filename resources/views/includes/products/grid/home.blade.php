<!-- PRODUCT AREA START -->
    <div class="grid sm:grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 2xl:gap-7 xl:gap-8 lg:gap-8 gap-4 items-center container">
        <!-- Products Start-->
            @foreach($products as $product)
                @include('includes.products.item.default', ['product' => $product])
            @endforeach
        <!-- Products End-->
    </div>
<!-- PRODUCT AREA END -->