@php
use App\Models\Shop\Currency;

// Получаем курсы USD и EUR
$usdCurrency = Currency::where('iso_alpha', 'USD')->where('is_active', true)->first();
$eurCurrency = Currency::where('iso_alpha', 'EUR')->where('is_active', true)->first();
@endphp

@if($usdCurrency || $eurCurrency)
<div class="w-full px-4">
    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
        <h3 class="text-sm font-semibold text-gray-600 mb-3 text-center">{{ __('template.exchange_rates') }}</h3>
        <div class="flex justify-center gap-6">
            @if($usdCurrency)
                <div class="flex flex-col items-center">
                    <div class="flex items-center gap-1 mb-1">
                        <span class="text-green-600 font-bold text-lg">$</span>
                        <span class="text-xs text-gray-500 uppercase">USD</span>
                    </div>
                    <div class="text-gray-800 font-semibold">{{ number_format($usdCurrency->rate, 2) }}</div>
                    <div class="text-gray-500 text-xs">MDL</div>
                </div>
            @endif
            
            @if($eurCurrency)
                <div class="flex flex-col items-center">
                    <div class="flex items-center gap-1 mb-1">
                        <span class="text-blue-600 font-bold text-lg">€</span>
                        <span class="text-xs text-gray-500 uppercase">EUR</span>
                    </div>
                    <div class="text-gray-800 font-semibold">{{ number_format($eurCurrency->rate, 2) }}</div>
                    <div class="text-gray-500 text-xs">MDL</div>
                </div>
            @endif
        </div>
    </div>
</div>
@endif 