<form id="currency-form" class="px-2 w-[80px] h-full border border-neutral-100 text-center rounded-lg" action="{{ route('currency.set') }}" method="POST">
    @csrf
    <select name="currency" class="nice-select text-gray-900 text-sm border-none bg-transparent block w-full p-2.5" >
        <option selected value="{{ session('currency')['iso_alpha'] }}">{{session('currency')['iso_alpha']}}</option>
        @foreach(\App\Models\Shop\Currency::where('is_active', true)->get() as $currency)
            @if (session('currency')['iso_alpha'] !== $currency->iso_alpha)
                <option value="{{ $currency->iso_alpha }}">
                    {{$currency->iso_alpha}}
                </option>
            @endif
        @endforeach
    </select>
</form>
