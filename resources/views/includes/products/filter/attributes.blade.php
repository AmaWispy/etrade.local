@if(!empty($availableAttributes))
<div class="space-y-4 mt-5">
    @php $attributeIndex = 0; @endphp
    @foreach($availableAttributes as $group)
        @foreach($group['attributes'] as $attribute)
            @php
                // Check if this attribute has selected values
                $hasSelectedValues = !empty($selectedAttributes[$attribute['id']]);
                $attributeIndex++;
            @endphp
            
            <div x-data='{plus: false}' class="space-y-3">
                <div class="space-y-2">
                    <button type="button" x-on:click='plus = !plus' class="flex w-full text-xl font-semibold justify-between">
                        <span>{{ $attribute['name'] }}</span>
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
                    x-ref="attributeContent{{ $attributeIndex }}"
                    class="mt-3 overflow-hidden transition-all duration-500 ease-in-out" 
                    x-bind:style="!plus ? 'max-height: ' + $refs.attributeContent{{ $attributeIndex }}.scrollHeight + 'px' : 'max-height: 0px'"
                    x-cloak
                >
                    <div class="space-y-1 overflow-y-auto" style="max-height: 200px;">
                        @foreach($attribute['values'] as $value)
                            @php
                                $isChecked = in_array($value['id'], $selectedAttributes[$attribute['id']] ?? []);
                            @endphp
                            <label for="attr-{{ $value['id'] }}" class="flex items-center gap-2 cursor-pointer hover:bg-blue-50 p-1 rounded {{ $isChecked ? 'bg-blue-50' : '' }}">
                                <input 
                                    type="checkbox" 
                                    id="attr-{{ $value['id'] }}"
                                    name="attributes[{{ $attribute['id'] }}][]" 
                                    value="{{ $value['id'] }}"
                                    class="attribute-checkbox"
                                    {{ $isChecked ? 'checked' : '' }}
                                    onchange="this.form.submit()"
                                >
                                <span class="text-neutral-500 font-semibold text-sm">{{ $value['value'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach
</div>

<style>
    .space-y-1::-webkit-scrollbar {
        width: 5px;
    }
    
    .space-y-1::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .space-y-1::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    
    .space-y-1::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
@endif 