<div class="w-full p-2 gap-1 {{ $filterType ?? '' }}">
    <ul class="flex flex-col justify-between xl:gap-0">
        <div class="flex flex-col  gap-2">
            {{-- <li>
                @include('includes.products.filter.sort.default', [
                    'sorting' => $sorting
                ])
            </li> --}}
        </div>
        <li>
            @include('includes.products.filter.range-price')
        </li>
        <li>
            @include('includes.products.filter.brands', [
                'brands' => $brands,
                'selectedBrands' => $selectedBrand
            ])
        </li>
    </ul>
</div>

