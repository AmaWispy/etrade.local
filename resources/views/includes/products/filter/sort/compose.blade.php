
<button id="dropdownSearchButton" data-dropdown-toggle="dropdownSearchFlower" class="nice-select inline-flex items-center px-4 py-2 text-sm font-medium text-center border rounded-lg" type="button">{{ __('template.flowers_in_composition') }}</button>

<!-- Dropdown menu -->
<div id="dropdownSearchFlower" class="z-10 hidden bg-white rounded-lg shadow w-60">
    <ul class="h-48 px-3 pb-3 overflow-y-auto text-sm text-gray-700 border-b" aria-labelledby="dropdownSearchButton">
        @foreach ($flowersVariations as $flower)
            <li>
                <div class="flex items-center p-2 rounded hover:bg-gray-100">
                    <input id="checkbox-item-{{ $flower['id'] }}" type="checkbox" value="{{ $flower['slug'] }}" @if(in_array($flower['id'], $filterFlowers)) checked @endif class="w-4 h-4 text-black bg-gray-100 border-gray-300 rounded">
                    <label for="checkbox-item-{{ $flower['id'] }}" class="w-full ms-2 text-sm font-medium text-gray-900 rounded">{{ $flower['name'] }}</label>
                </div>
            </li>
        @endforeach
    </ul>
    <div class="flex justify-center items-center p-3">
        <button id="applyFilterButtonFlower" class="py-2 px-3 w-full text-white bg-black rounded-lg">
            {{ __('template.show') }}
        </button>
    </div>
</div>

<script type="module">
$(document).ready(function () {
    $('#applyFilterButtonFlower').on('click', function () {
      // Собираем отмеченные значения чекбоксов
        var selectedFlowers = [];
        $('#dropdownSearchFlower input[type="checkbox"]:checked').each(function () {
            selectedFlowers.push($(this).val());
        });
        
        var currentUrl = window.location.href;

        // Убираем старый параметр "color" из URL, если он есть
        currentUrl = currentUrl.replace(/[?&]flower=[^&]*/, '');

        // Если есть выбранные цвета, добавляем их в URL
        if (selectedFlowers.length !== 0) {
            // Формируем новый URL с параметрами
            var newUrl = currentUrl + (currentUrl.includes('?') ? '&' : '?') + 'flower=' + selectedFlowers.join(',');

            // Перенаправляем пользователя на новый URL
            window.location.href = newUrl;
        } else {
            // Если нет выбранных цветов, просто перенаправляем на URL без параметра "color"
            window.location.href = currentUrl;
        }

    });
});
</script>