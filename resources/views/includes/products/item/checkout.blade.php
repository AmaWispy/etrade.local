<div class="flex gap-2 items-center w-full mt-2">
    <div class="h-[70px] !w-20">
        <img src="{{ $item->product->getImage() }}" class="h-full w-full object-cover" alt="{{ $item->product->name }}">
    </div>
    <div class="flex justify-between items-center w-full">
        <ul class="flex flex-col">
            <li>
                <p class="text-sm">{{ $item->product->sku }}</p>
            </li>
            <li>
                <h1 class="font-semibold 2xl:w-64 xl:w-36 lg:w-96 md:w-52 sm:w-40 truncate">
                    @if($item->product->type === \App\Models\Shop\Product::VARIABLE)
                        {{$item->variation->name}}
                    @else
                        {{$item->product->name}}
                    @endif
                </h1>
            </li>
            <li>
                <p class="text-sm"> {{$item->qty}} {{ __('template.pcs') }}</p>
            </li>
        </ul>
        <div>
            <h1>{{$item->getUnitSubtotal()}}</h1>
        </div>
    </div>
</div>