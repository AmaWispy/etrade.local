{{-- Add more if necessary --}}
@php(
    $labels = [
        'short' => [
            'en' => 'EN',
            'ru' => 'RU',
            'ro' => 'RO'
        ],
        'long' => [
            'en' => 'English',
            'ru' => 'Русский',
            'ro' => 'Română'
        ] 
    ]
)

<form id="locale-form" class="px-2 h-full w-[80px] border border-neutral-100 text-center rounded-lg" action="{{ route('locale.set') }}" method="POST">
    @csrf
    <select id="countries" name="locale" class="nice-select text-gray-900 text-sm border-none bg-transparent block w-full p-2.5" onchange="submitForm()">
        <option selected value="{{ app()->getLocale() }}">{{$labels['short'][app()->getLocale()]}}</option>
        @foreach(config('app.locales') as $locale)
            @if ($labels['short'][$locale] !== $labels['short'][app()->getLocale()])
                <option value="{{ $locale }}">
                    {{$labels['short'][$locale]}}
                </option>
            @endif
        @endforeach
    </select>
</form>

<script>
    /* When the user clicks on the button,
        toggle between hiding and showing the dropdown content */
    function submitForm() {
        document.getElementById('locale-form').submit();
    }
</script>