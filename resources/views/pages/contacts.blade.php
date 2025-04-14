<x-app-layout>

    @section('title', $page->title)

	<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- BREADCRUMB AREA START -->
        @include('includes.layout.bread-crump')
    <!-- BREADCRUMB AREA END -->

    <!-- Form and Info Start-->
        <div class="container mx-auto flex justify-between xl:flex-row flex-col gap-7 xl:gap-0">
            <!-- Form Start-->
                <div class="flex flex-col gap-6 xl:w-1/2">
                    <div class="flex flex-col gap-2">
                        <h1 class="text-3xl font-semibold">{{ __('template.we_would_love_to_hear_from_you') }}</h1>
                        <p class="text-[15px] text-neutral-500">{{ __('template.if_you_have_great_products') }}</p>
                    </div>
                    <form action="" class="flex flex-col gap-3">
                        @csrf
                        <div class="flex gap-3 w-full xl:flex-row flex-col">
                            <label for="name" class="relative w-full">
                                <span class="text-black absolute bg-white -top-2.5 left-4 text-sm">{{ __('template.name') }} <span class="text-red-500">*</span></span>
                                <input type="text" id="name" name="name" required class="w-full border !border-neutral-400 rounded-md h-14">
                            </label>
                            <label for="phone" class="relative w-full">
                                <span class="text-black absolute bg-white -top-2.5 left-4 text-sm">{{ __('template.phone') }} <span class="text-red-500">*</span></span>
                                <input type="text" id="phone" name="phone" required class="w-full border !border-neutral-400 rounded-md h-14">
                            </label>
                            <label for="email" class="relative w-full">
                                <span class="text-black absolute bg-white -top-2.5 left-4 text-sm">E-mail<span class="text-red-500">*</span></span>
                                <input type="email" id="email" name="email" required class="w-full border !border-neutral-400 rounded-md h-14">
                            </label>
                        </div>
                        <label for="message" class="relative w-full">
                            <span class="text-black absolute bg-white -top-2.5 left-4 text-sm">{{ __('template.message') }}<span class="text-red-500">*</span></span>
                            <textarea name="message" id="message" maxlength="225" minlength="10" class="resize-none w-full h-40 border !border-neutral-400 rounded-md"></textarea>
                        </label>
                        <button class="inline-flex justify-start w-fit bg-blue-500 py-3 px-4 text-white rounded-md font-semibold text-lg">{{ __('template.send_mail') }}</button>
                    </form>
                </div>
            <!-- Form Start-->

            <!-- Blocks Info Start-->
                <div class="flex flex-col gap-5 2xl:w-96 xl:w-2/5">
                    <div class="text-neutral-500 text-[15px] flex flex-col gap-2">
                        <h1 class="text-3xl font-semibold text-black">{{ __('template.our_store') }}</h1>
                        <p>{{ $templateSettings['company-legal-address'] }}</p>
                        <ul>
                            <li>
                                <p>{{ __('template.phone') . ': ' . $templateSettings['company-telephone'] }}:</p>
                            </li>
                            <li>
                                <p>{{ __('template.email') . ': ' . $templateSettings['company-email'] }}</p>
                            </li>
                        </ul>
                    </div>

                    <div class="text-neutral-500 text-[15px] flex flex-col gap-2">
                        <h1 class="text-3xl font-semibold text-black">{{ __('template.our_store') }}</h1>
                        <p class="break-words">{{ __('template.instead_of_buying_six') }}</p>
                    </div>

                    <div class="text-neutral-500 text-[15px] flex flex-col gap-2">
                        <h1 class="text-3xl font-semibold text-black">{{ __('template.opening_hours') }}</h1>
                        <ul>
                            <li>
                                <p>{{ $templateSettings['mon-sat-work'] }}</p>
                            </li>
                            <li>
                                <p>{{ $templateSettings['sat-work'] }}</p>
                            </li>
                        </ul>
                    </div>
                </div>
            <!-- Blocks Info Start-->
        </div>
    <!-- Form and Info End-->

    <!-- Address and Map Box Start-->
        <div class="flex flex-col gap-5 md:container md:mx-auto px-1 mt-5">
            <div id="map" class="w-full md:h-[55vh] sm:h-[200px] z-10"></div>
        </div>
    <!-- Address and Map Box Start-->
</x-app-layout>

<script type="module" src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script type="module">
    var map = L.map('map').setView([47.02708, 28.83271], 18);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    L.marker([47.02708, 28.83271]).addTo(map)
    // .bindPopup(@JS($templateSettings['company-legal-name'] . "<br/>" . str_replace(",", "<br/>", $templateSettings['company-legal-address'])))
    .openPopup();
</script>