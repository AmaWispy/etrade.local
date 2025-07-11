<form method="GET" action="{{ request()->url() }}" class="w-full p-2 gap-1 {{ $filterType ?? '' }}">
    {{-- Preserve existing query parameters --}}
    @foreach(request()->query() as $key => $value)
        @if($key !== 'attributes' && $key !== 'brand' && $key !== 'min' && $key !== 'max' && $key !== 'in_stock_only')
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endif
    @endforeach
    
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
            @include('includes.products.filter.stock-toggle')
        </li>
        <li>
            @include('includes.products.filter.brands', [
                'brands' => $brands,
                'selectedBrands' => $selectedBrand
            ])
        </li>
        @if(!empty($availableAttributes))
        <li>
            @include('includes.products.filter.attributes', [
                'availableAttributes' => $availableAttributes,
                'selectedAttributes' => $selectedAttributes
            ])
        </li>
        @endif
    </ul>
</form>

