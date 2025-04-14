@if ($product->composition !== null)
    @php
        $showMessage = false;

        foreach ($product->composition as $item) {
            $outOfStockProduct = App\Models\Shop\Product::where('id', $item['product'])
                ->where('manage_stock', 1)
                ->where('qty', 0)
                ->first();

            if ($outOfStockProduct !== null) {
                $showMessage = true;
                break; // Прерываем цикл, так как условие выполнено
            }
        }
    @endphp

    @if ($showMessage || $product->manage_stock && !$product->qty)
        <li class="text-white !bg-yellow-400 px-2 py-2 !rounded-sm inline-flex gap-1 !items-center !justify-center text-center sm:!text-[10px] md:!text-[12px] 2xl:text-lg">
            <i class="bi bi-clock"></i>
            <span class="hidden xl:inline">{{ __('template.pre_order_10_days') }}</span> 
            <span class="inline xl:hidden">{{ __('template.pre_order') }}</span>
        </li>

    @endif
@else
    @if ($product->manage_stock && !$product->qty)
        <li class="text-white !bg-yellow-400 px-2 py-2 !rounded-sm inline-flex gap-1 !items-center !justify-center text-center sm:!text-[10px] md:!text-[12px] 2xl:text-lg">
            <i class="bi bi-clock"></i> 
            <span class="hidden xl:inline">{{ __('template.pre_order_10_days') }}</span> 
            <span class="inline xl:hidden">{{ __('template.pre_order') }}</span></li>
    @endif
@endif