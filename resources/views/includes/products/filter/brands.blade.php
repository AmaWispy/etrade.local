<div x-data='{plus: false}' class="space-y-3 mt-5">
    <div class="space-y-2">
        <button type="button" x-on:click='plus = !plus' class="flex w-full text-xl font-semibold justify-between">
            <span>{{ __('template.brands') }}</span>
            <span x-cloak>
                <i class="bi bi-plus-lg" x-show='plus'></i>
                <i class="bi bi-dash-lg" x-show='!plus'></i>
            </span>
        </button>
        <div class="relative">
            <div class="h-[2px] bg-neutral-300 w-full"></div>
            <div x-cloak x-bind:class='plus ? "w-0" : "w-full"' class="absolute top-0 duration-300 h-[2px] bg-blue-500 w-full"></div>
        </div>
    </div>
    <div 
        x-ref="brandsContent"
        class="mt-3 overflow-hidden transition-all duration-500 ease-in-out" 
        x-bind:style="!plus ? 'max-height: ' + $refs.brandsContent.scrollHeight + 'px' : 'max-height: 0px'"
        x-cloak
    >
        <div id="brands-container" class="space-y-3 overflow-y-auto" style="max-height: 200px;">
            {{-- Сначала выводим выбранные бренды --}}
            @foreach($brands as $brand)
                @if(is_array($selectedBrands) && in_array($brand, $selectedBrands))
                    <label for="brand-{{ $brand }}" class="flex items-center gap-2 cursor-pointer bg-blue-50 p-1 rounded">
                        <input 
                            type="checkbox" 
                            id="brand-{{ $brand }}" 
                            name="brand[]" 
                            value="{{ $brand }}"
                            class="brand-checkbox"
                            checked
                        >
                        <span class="text-neutral-500 font-semibold">{{ $brand }}</span>
                    </label>
                @endif
            @endforeach
            
            {{-- Затем выводим невыбранные бренды --}}
            @foreach($brands as $brand)
                @if(!(is_array($selectedBrands) && in_array($brand, $selectedBrands)))
                    <label for="brand-{{ $brand }}" class="flex items-center gap-2 cursor-pointer">
                        <input 
                            type="checkbox" 
                            id="brand-{{ $brand }}" 
                            name="brand[]" 
                            value="{{ $brand }}"
                            class="brand-checkbox"
                        >
                        <span class="text-neutral-500 font-semibold">{{ $brand }}</span>
                    </label>
                @endif
            @endforeach
        </div>
    </div>
</div>



<style>
    #brands-container::-webkit-scrollbar {
        width: 5px;
    }
    
    #brands-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    #brands-container::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    
    #brands-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>