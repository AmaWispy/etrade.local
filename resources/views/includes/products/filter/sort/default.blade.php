<button id="dropdownSearchButton" data-dropdown-toggle="dropdownSearchSort" class="nice-select inline-flex items-center px-2.5 py-2 text-sm font-medium text-center border rounded-lg w-60" type="button">{{ __('template.'.$sorting) }}</button>
<!-- Dropdown menu -->
<div id="dropdownSearchSort" class="z-10 hidden bg-white rounded-lg shadow w-64">
    <ul class="h-auto p-3 overflow-y-auto text-sm text-gray-700 border-b" aria-labelledby="dropdownSearchButton">
        <li class="mb-3">
            <label class="flex items-center cursor-pointer">
                <input 
                    type="radio" 
                    name="sorting" 
                    value="sorting=latest" 
                    class="hidden peer" 
                    {{ $sorting == 'latest' ? 'checked' : '' }}
                >
                <span class="ml-2 peer-checked:text-florarColor">{{ __('template.latest') }}</span>
            </label>
        </li>
        <li class="mb-3">
            <label class="flex items-center cursor-pointer">
                <input 
                    type="radio" 
                    name="sorting" 
                    value="sorting=low_to_high" 
                    class="hidden peer" 
                    {{ $sorting == 'low_to_high' ? 'checked' : '' }}
                >
                <span class="ml-2 peer-checked:text-florarColor">{{ __('template.low_to_high') }}</span>
            </label>
        </li>
        <li class="mb-3">
            <label class="flex items-center cursor-pointer">
                <input 
                    type="radio" 
                    name="sorting" 
                    value="sorting=high_to_low" 
                    class="hidden peer" 
                    {{ $sorting == 'high_to_low' ? 'checked' : '' }}
                >
                <span class="ml-2 peer-checked:text-florarColor">{{ __('template.high_to_low') }}</span>
            </label>
        </li>
    </ul>
</div>

<script type="module">
$(document).ready(function () {
    $('input[name="sorting"]').on('change', function () {
        var sorting = $(this).val();

        // Get the current URL and remove the existing sorting parameter (if any)
        var currentUrl = window.location.href.replace(/[?&]sorting=[^&]+/, '');

        // Construct the new URL with the selected sorting option
        var newUrl = currentUrl + (currentUrl.includes('?') ? '&' : '?') + sorting;

        // Redirect to the new URL
        window.location.href = newUrl;
    });
});
</script>
