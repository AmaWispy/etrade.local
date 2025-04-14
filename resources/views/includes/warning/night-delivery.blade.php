@if (isset($templateSettings['warning-night-delivery']))
    <div class="bg-pink-100 flex items-center py-2 px-3 gap-3 rounded-lg">
        <i class="bi bi-info-circle-fill text-pink-600 text-3xl"></i>
        <div class="text-stone-500">
            <p>{{ $templateSettings['warning-night-delivery'] }}</p>
        </div>
    </div>
@endif