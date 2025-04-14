@if (isset($templateSettings['warning-flower-avilability']))
    <div class="bg-pink-100 flex items-center py-2 px-3 gap-3 rounded-lg">
        <i class="bi bi-info-circle-fill text-pink-600 text-3xl"></i>
        <div class="w-[80vw] text-stone-500">
            <p>{{ $templateSettings['warning-flower-avilability'] }}</p>
        </div>
    </div>
@endif