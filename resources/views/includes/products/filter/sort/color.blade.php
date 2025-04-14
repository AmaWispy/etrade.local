
<button id="dropdownSearchButton" data-dropdown-toggle="dropdownSearchColor" class="nice-select inline-flex items-center px-4 py-2 text-sm font-medium text-center border rounded-lg" type="button">{{ __('template.color_palette') }}</button>

<!-- Dropdown menu -->
<div id="dropdownSearchColor" class="z-10 hidden bg-white rounded-lg shadow w-60">
    <ul class="h-48 px-3 pb-3 overflow-y-auto text-sm text-gray-700 border-b" aria-labelledby="dropdownSearchButton">
        @foreach ($attributeColor->managedAttributeValues()->where('is_active', 1)->get() as $color)
            <li>
                <div class="flex items-center p-2 rounded hover:bg-gray-100">
                <input id="checkbox-item-{{ $color['id'] }}" type="checkbox" value="{{ $color['attr_key'] }}" @if (in_array($color['attr_key'], $colors)) checked @endif class="w-4 h-4 text-black bg-gray-100 border-gray-300 rounded">
                <label for="checkbox-item-{{ $color['id'] }}" class="w-full ms-2 text-sm font-medium text-gray-900 rounded">{{ $color['attr_value'] }}</label>
                </div>
            </li>
        @endforeach
    </ul>
    <div class="flex justify-center items-center p-3">
        <button id="applyFilterButton" class="py-2 px-3 w-full text-white bg-black rounded-lg">
        {{ __('template.show') }}
        </button>
    </div>
</div>

<script type="module">
$(document).ready(function () {
    $('#applyFilterButton').on('click', function () {
    var selectedColors = [];
        $('#dropdownSearchColor input[type="checkbox"]:checked').each(function () {
            selectedColors.push($(this).val());
        });
        
        var currentUrl = window.location.href;

        // Delete old params COLOR -> URL if use
        currentUrl = currentUrl.replace(/[?&]color=[^&]*/, '');

        // If have selected colors, add url
        if (selectedColors.length !== 0) {
            // Generate new url to params
            var newUrl = currentUrl + (currentUrl.includes('?') ? '&' : '?') + 'color=' + selectedColors.join(',');

            // Redirect to new URl
            window.location.href = newUrl;
        } else {
            // If not select color, redirect to url without params color 
            window.location.href = currentUrl;
        }

    });
});
</script>