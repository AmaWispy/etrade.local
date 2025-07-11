<!-- Stock Availability Toggle Start -->
<div class="mt-5">
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-lg font-medium text-gray-900">{{ __('template.availability') }}</h3>
    </div>
    
    <div class="flex items-center space-x-3">
        <label class="relative inline-flex items-center cursor-pointer">
            <!-- Hidden field to send "0" when checkbox is unchecked -->
            <input type="hidden" name="in_stock_only" value="0">
            <input 
                type="checkbox" 
                name="in_stock_only" 
                value="1" 
                class="sr-only peer"
                {{ request('in_stock_only') ? 'checked' : '' }}
                onchange="this.form.submit()"
            >
            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
        </label>
        <span class="text-sm text-gray-700">{{ __('template.only_available_stock') }}</span>
    </div>
    
    <p class="text-xs text-gray-500 mt-2">{{ __('template.show_only_products_with_available_stock') }}</p>
</div>
<!-- Stock Availability Toggle End -->