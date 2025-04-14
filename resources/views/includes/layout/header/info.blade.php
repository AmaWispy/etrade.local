<ul class="flex gap-2 items-center text-[15px]">
    <li><h1>{{ __('template.flower_delivery') }}</h1></li>
    <li><span class="font-extrabold">•</span></li>
    <li><h1>{{ __('template.online_payment') }}</h1></li>
    <li><span class="font-extrabold">•</span></li>
    <li><h1>Viber/ Whatsapp <span class="duration-[.4s] hover:text-pink-600 cursor-pointer">{{ (App\Models\Settings::where('key','company-telephone')->value('content')) }}</span></h1></li>
</ul>