
<button id="dropdownSearchButton" data-dropdown-toggle="dropdownSearchSize" class="nice-select inline-flex items-center px-4 py-2 text-sm font-medium text-center border rounded-lg" type="button">{{ __('template.sizes') }}</button>

<!-- Dropdown menu -->
<div id="dropdownSearchSize" class="z-10 hidden bg-white rounded-lg shadow w-60">
    <ul class="h-48 px-3 pb-3 overflow-y-auto text-sm text-gray-700 border-b" aria-labelledby="dropdownSearchButton">
        @foreach ($attributeSize->managedAttributeValues()->where('is_active', 1)->get() as $size)
            <li>
                <div class="flex items-center p-2 rounded hover:bg-gray-100">
                <input id="checkbox-item-{{ $size['id'] }}" type="checkbox" value="{{ $size['attr_key'] }}" @if (in_array($size['attr_key'], $sizes)) checked @endif class="w-4 h-4 text-black bg-gray-100 border-gray-300 rounded">
                <label for="checkbox-item-{{ $size['id'] }}" class="w-full ms-2 text-sm font-medium text-gray-900 rounded">{{ $size['attr_value'] }}</label>
                </div>
            </li>
        @endforeach
    </ul>
    <div class="flex justify-center items-center p-3">
        <button id="applyFilterButtonSize" class="py-2 px-3 w-full text-white bg-black rounded-lg">
        {{ __('template.show') }}
        </button>
    </div>
</div>

<script type="module">
$(document).ready(function () {
    $('#applyFilterButtonSize').on('click', function () {
        var selectedSizes = [];
        $('#dropdownSearchSize input[type="checkbox"]:checked').each(function () {
            selectedSizes.push($(this).val());
        });
        
        var currentUrl = window.location.href;

        // Delete old params size -> URL if use
        currentUrl = currentUrl.replace(/[?&]size=[^&]*/, '');

        // If have selected size, add url
        if (selectedSizes.length !== 0) {
            // Generate new url to params
            var newUrl = currentUrl + (currentUrl.includes('?') ? '&' : '?') + 'size=' + selectedSizes.join(',');

            // Redirect to new URl
            window.location.href = newUrl;
        } else {
            // If not select size, redirect to url without params color 
            window.location.href = currentUrl;
        }

    });
});
</script>