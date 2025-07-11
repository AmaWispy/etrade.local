@php
use App\Models\Shop\Currency;

// Получаем курсы USD и EUR
$usdCurrency = Currency::where('iso_alpha', 'USD')->where('is_active', true)->first();
$eurCurrency = Currency::where('iso_alpha', 'EUR')->where('is_active', true)->first();
@endphp

@if($usdCurrency || $eurCurrency)
<div class="flex items-center gap-3 text-sm text-gray-700 font-medium border-gray-200 pr-3 mr-1">
    @if($usdCurrency)
        <div class="flex items-center gap-1 bg-gray-50 px-2 py-1 rounded-md">
            <span class="text-green-600 font-bold">$</span>
            <span class="text-gray-800">{{ number_format($usdCurrency->rate, 2) }}</span>
            <span class="text-gray-500 text-xs">MDL</span>
        </div>
    @endif
    
    @if($eurCurrency)
        <div class="flex items-center gap-1 bg-gray-50 px-2 py-1 rounded-md">
            <span class="text-blue-600 font-bold">€</span>
            <span class="text-gray-800">{{ number_format($eurCurrency->rate, 2) }}</span>
            <span class="text-gray-500 text-xs">MDL</span>
        </div>
    @endif
</div>
@endif 