@php
    if(isset($category)){
        $filters = $category->managedAttrtibute->pluck('key')->toArray();
    } else { 
        $filters = null;
    }
@endphp
<div class="w-full p-2 gap-1">
    <ul class="flex flex-col justify-between xl:gap-0 gap-20">
        <div class="flex flex-col  gap-2">
            {{-- <li>
                @include('includes.products.filter.sort.default', [
                    'sorting' => $sorting
                ])
            </li> --}}
            @if (isset($attributeColor) && $attributeColor !== null && (empty($filters) || in_array('color', $filters)))
                <li>
                    @include('includes.products.filter.sort.color',[
                        'attributeColor' => $attributeColor,
                        'colors' => $colors
                    ])
                </li>
            @endif
            @if (isset($attributeSize) && $attributeSize !== null && (empty($filters) || in_array('size',    $filters)))
                <li>
                    @include('includes.products.filter.sort.size',[
                        'attributeSize' => $attributeSize,
                        'sizes' => $sizes
                    ])
                </li>
            @endif
            @if (isset($flowersVariations ) && $flowersVariations !== null && (empty($filters) || in_array('variatii',    $filters)))
                <li>
                    @include('includes.products.filter.sort.compose',[
                        'flowersVariations' => $flowersVariations,
                        'filterFlowers' => $filterFlowers
                    ])
                </li>
            @endif
        </div>
        <li>
            @include('includes.products.filter.range-price')
        </li>
    </ul>
</div>