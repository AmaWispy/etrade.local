<x-app-layout>
    @section('title', __('template.account'))

    <div>
        <!-- Bread Crump Start-->
            @include('includes.layout.bread-crump', [
                'page' => ['title' => __('template.account')]
            ])
        <!-- Bread Crump End-->

        <div class="container">
            <!-- Account Header Start-->
                <div class="flex flex-col gap-1">
                    <!-- <div class="rounded-full border w-20 h-20">
                        <img src="{{ asset('template/images/avatar-template.png') }}" class="h-full w-full object-cover border rounded-full" alt="Avatar">
                    </div> -->
                    <ul>
                        <li>
                            <h1 class="text-lg font-semibold">{{ __('template.hello') . ', ' . \Auth::guard('client')->user()->name . '!' }}</h1>
                        </li>
                    </ul>
                </div>
            <!-- Account Header End-->

            <!-- Menus and show menu Start-->
                <div class="mt-5 flex xl:flex-row flex-col gap-6 xl:gap-14" @if(session()->has('AccountDetailsPage'))
                    x-data='{dashboard:false, orders:true, downloads:false, address:false, details:false}'
                @else
                    x-data='{dashboard:false, orders:true, downloads:false, address:false, details:false}'
                @endif>
                    <!-- Menu Start -->
                        <div class="border rounded-xl p-4 xl:w-96 h-fit">
                            <ul class="flex flex-col gap-3.5 text-lg  ">
                                <!-- <li x-bind:class="dashboard ? 'text-blue-500 bg-neutral-100' : 'text-neutral-600' " class="duration-500 py-2 rounded-lg pl-6">
                                    <button x-on:click='dashboard = true; orders = false; downloads = false; address = false; details = false' class="flex gap-2.5 items-center w-full"><i class="bi bi-border-all"></i> {{ __('template.dashboard') }}</button>
                                </li> -->
                                <li  x-bind:class="orders ? 'text-blue-500 bg-neutral-100' : 'text-neutral-600' " class="duration-500  py-2 rounded-lg pl-6 ">
                                    <button x-on:click='dashboard = false; orders = true; downloads = false; address = false; details = false' class="flex gap-2.5 items-center w-full"><i class="bi bi-basket2-fill"></i> {{ __('template.orders') }}</button>
                                </li>
                               <!--  <li  x-bind:class="downloads ? 'text-blue-500 bg-neutral-100' : 'text-neutral-600' " class="duration-500  py-2 rounded-lg pl-6">
                                    <button x-on:click='dashboard = false; orders = false; downloads = true; address = false; details = false' class="flex gap-2.5 items-center w-full"><i class="bi bi-file-earmark-arrow-down-fill"></i> {{ __('template.downloads') }}</button>
                                </li>
                                <li  x-bind:class="address ? 'text-blue-500 bg-neutral-100' : 'text-neutral-600' " class="duration-500  py-2 rounded-lg pl-6">
                                    <button x-on:click='dashboard = false; orders = false; downloads = false; address = true; details = false' class="flex gap-2.5 items-center w-full"><i class="bi bi-house-door-fill"></i> {{ __('template.address') }}</button>
                                </li>
                                <li  x-bind:class="details ? 'text-blue-500 bg-neutral-100' : 'text-neutral-600' " class="duration-500  py-2 rounded-lg pl-6">
                                    <button x-on:click='dashboard = false; orders = false; downloads = false; address = false; details = true' class="flex gap-2.5 items-center w-full"><i class="bi bi-person-fill"></i> {{ __('template.account_details') }}</button>
                                </li> -->
                                <li class="py-2 rounded-lg pl-6">
                                    <form action="{{ route('custom.logout') }}" method="POST">
                                        @csrf
                                        <button class="flex gap-2.5 items-center w-full xl:hover:text-red-500"><i class="bi bi-box-arrow-right"></i> {{ __('template.logout') }}</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    <!-- Menu End -->

                    <!-- Show Start -->
                        <div class="w-full p-1.5">
                            <!-- Dashboard show Start-->
                                <ul x-show='dashboard' class="flex flex-col gap-4" x-cloak>
                                    <li>
                                        <h1 class="text-lg flex gap-1.5">{{ __('template.hello') . ', ' . \Auth::guard('client')->user()->name }} {{ '(' . __('template.not') }} <span class="font-semibold">{{ \Auth::guard('client')->user()->name . '?' }}</span> 
                                            <form action="{{ route('logout') }}" method="POST">
                                                @csrf
                                                <button class="text-red-500 xl:hover:text-blue-500 cursor-pointer">{{ __('template.logout') }}</button>{{ ')' }}
                                            </form>
                                        </h1>
                                    </li>
                                    <li>
                                        <p class="text-neutral-500">{{ __('template.dashboard_info') }}</p>
                                    </li>
                                </ul>
                            <!-- Dashboard show End-->

                            <!-- Orders show Start -->
                                <div x-show='orders' class="flex flex-col gap-4">
                                    <ul class="xl:inline-flex hidden items-center text-lg font-medium justify-around w-full">
                                        <li class="w-20">
                                            <h1>{{ __('template.order') }}</h1>
                                        </li>
                                        <li class="w-20">
                                            <h1>GUID</h1>
                                        </li>
                                        <li class="w-24">
                                            <h1>{{ __('template.date') }}</h1>
                                        </li>
                                        <li class="w-32">
                                            <h1>{{ __('template.status') }}</h1>
                                        </li>
                                        <li class="w-64">
                                            <h1>{{ __('template.total') }}</h1>
                                        </li>
                                        <li class="w-[88px]">
                                            <h1>{{ __('template.actions') }}</h1>
                                        </li>
                                    </ul>

                                    <!-- Orders Start -->
                                    <div class="flex flex-col">
                                        @foreach ($orders as $order)
                                            <div class="flex flex-col">
                                            <ul class="flex xl:flex-row gap-2 flex-col xl:items-center xl:justify-around py-3 border-b text-base">
                                                <li class="xl:w-20 flex gap-2">
                                                    <h1 class="font-medium xl:hidden block">{{ __('template.order') }}:</h1>
                                                    <h1 class="text-red-500">#{{ $order->id }}</h1>
                                                </li>
                                                <li class="xl:w-20 flex gap-2">
                                                    <h1 class="font-medium xl:hidden block">GUID:</h1>
                                                    <h1 class="text-red-500">{{ $order->guid }}</h1>
                                                </li>
                                                <li class="xl:w-24 flex gap-2">
                                                    <h1 class="font-medium xl:hidden block">{{ __('template.date') }}:</h1>
                                                    <h1>{{ $order->created_at->format('d.m.Y') }}</h1>
                                                </li>
                                                <li class="xl:w-32 flex gap-2">
                                                    <h1 class="font-medium xl:hidden block">{{ __('template.status') }}:</h1>
                                                    <h1>{{ $order->status }}</h1>
                                                </li>
                                                <li class="xl:w-64 flex gap-2">
                                                    <h1 class="font-medium xl:hidden block">{{ __('template.total') }}:</h1>
                                                    <h1>{{ \App\Models\Shop\Currency::formatCustom($order->total, $order->currency) }}</h1>
                                                </li>
                                                <li class="py-2.5 px-4 border rounded-lg xl:hover:bg-blue-500 xl:hover:text-white duration-300 cursor-pointer w-fit xl:mt-0 mt-2">
                                                    <button x-on:click="$dispatch('open-modal', 'order-items-{{ $order->id }}')" class="lg:text-lg text-[18px] font-medium cursor-pointer">
                                                        {{ __('template.view') }}
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    @endforeach
                                    <!-- Orders End -->
                                </div>
                            <!-- Orders show End -->

                            <!-- Order Items Modals Start -->
                            @foreach ($orders as $order)
                                <div x-data="{ show: false }" 
                                     x-show="show" 
                                     x-on:open-modal.window="if ($event.detail === 'order-items-{{ $order->id }}') show = true"
                                     x-on:close-modal.window="show = false"
                                     x-on:keydown.escape.window="show = false"
                                     x-effect="show ? document.body.classList.add('overflow-hidden') : document.body.classList.remove('overflow-hidden')"
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     x-transition:leave="transition ease-in duration-200"
                                     x-transition:leave-start="opacity-100"
                                     x-transition:leave-end="opacity-0"
                                     class="fixed inset-0 z-50 overflow-y-auto" 
                                     style="display: none;">
                                    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                                        <div class="fixed inset-0 transition-opacity" aria-hidden="true" x-on:click="show = false">
                                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                        </div>
                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                <div class="sm:flex sm:items-start">
                                                    <div class="mt-3 sm:mt-0 sm:text-left w-full">
                                                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                                            {{ __('template.order') }} #{{ $order->id }}
                                                        </h3>
                                                        <div class="mt-2">
                                                            <div class="border-b pb-2 mb-4">
                                                                <p class="text-sm text-gray-500">GUID: {{ $order->guid }}</p>
                                                                <p class="text-sm text-gray-500">{{ __('template.date') }}: {{ $order->created_at->format('d.m.Y H:i') }}</p>
                                                                <p class="text-sm text-gray-500">{{ __('template.status') }}: {{ $order->status }}</p>
                                                                <p class="text-sm text-gray-500">{{ __('template.total') }}: {{ \App\Models\Shop\Currency::formatCustom($order->total, $order->currency) }}</p>
                                                                
                                                                @php
                                                                    $currencyData = is_string($order->currency) ? json_decode($order->currency, true) : $order->currency;
                                                                    $exchangeRate = 1 / $currencyData['exchange_rate'];
                                                                @endphp
                                                                @if($currencyData['sign'] != 'mdl')
                                                                    <p class="text-sm text-gray-500">{{ __('template.currency') }}: 1 {{ $currencyData['sign'] }} = {{ number_format($exchangeRate, 2) }} MDL</p>
                                                                @endif
                                                            </div>
                                                            <div class="space-y-4">
                                                                <h4 class="font-medium">{{ __('template.items_2') }}:</h4>
                                                                @foreach($order->items as $item)
                                                                    <div class="flex justify-between items-center border-b pb-2">
                                                                        <div>
                                                                            <p class="font-medium">{{ $item->product->name }}</p>
                                                                            <p class="text-sm text-gray-500">{{ __('template.quantity') }}: {{ $item->qty }}</p>
                                                                        </div>
                                                                        <p class="font-medium text-lg whitespace-nowrap ml-4">{{ \App\Models\Shop\Currency::formatCustom($item->unit_price * $item->qty, $order->currency) }}</p>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                <button type="button" 
                                                        x-on:click="show = false"
                                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                    {{ __('template.close') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <!-- Order Items Modals End -->

                            <!-- Download show Start-->
                                <ul x-show='downloads' class="flex flex-col gap-4" x-cloak>
                                    <li>
                                        <p class="text-neutral-500">{{ __('template.you_dont_have_any_download') }}</p>
                                    </li>
                                </ul>
                            <!-- Download show End-->
                            
                            <!-- Addresses show Start TODO::сдеалть через foreach чтобы было меньше кода -->
                                <div x-show='address' class="flex flex-col gap-5" x-cloak>
                                    <h1 class="text-[15px]">{{ __('template.the_following_addresses_will_be_used_on_the_checkout_page_by_default') }}</h1>
                                    <div class="flex xl:flex-row flex-col gap-5">
                                        <!-- Shipping Address Start -->
                                            <div x-data='{shipping:false}' class="flex flex-col gap-4 xl:w-1/2">
                                                <ul class="flex justify-between text-xl font-semibold border-b pb-3">
                                                    <li>
                                                        <h1>{{ __('template.shipping_address') }}</h1>
                                                    </li>
                                                    <li>
                                                        <button x-on:click='shipping = !shipping' class="text-lg"><i class="bi bi-pencil-square"></i></button>
                                                    </li>
                                                </ul>
                                                <div>
                                                    <ul x-show="!shipping" class="flex flex-col gap-4 text-[15px]">
                                                        <div class="flex flex-col gap-2">
                                                            <li>
                                                                <h1 class="truncate sm:w-11/12">{{ __('template.name') }}: Annie Mario</h1>
                                                            </li>
                                                            <li>
                                                                <h1 class="truncate sm:w-11/12">{{ __('template.email') }}: email@example.com</h1>
                                                            </li>
                                                            <li>
                                                                <h1 class="truncate sm:w-11/12">{{ __('template.phone') }}: 123123123123</h1>
                                                            </li>
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <li>
                                                                <h1 class="truncate sm:w-11/12">7398 Smoke Ranch Road</h1>
                                                            </li>
                                                            <li>
                                                                <h1 class="truncate sm:w-11/12">Las Vegas, Nevada 89128</h1>
                                                            </li>
                                                        </div>
                                                    </ul>
                                                    
                                                    <form x-show="shipping" class="flex flex-col gap-4 text-[15px]">
                                                        <div class="flex flex-col gap-3">
                                                            <label for="name" class="relative w-full">
                                                                <span class="text-black absolute bg-white -top-2.5 left-4 text-sm">{{ __('template.name') }}</span>
                                                                <input type="text" id="name" name="name" class="w-full border !border-neutral-400 rounded-md h-14" placeholder="Annie Mario">
                                                            </label>
                                                            <label for="email" class="relative w-full">
                                                                <span class="text-black absolute bg-white -top-2.5 left-4 text-sm">{{ __('template.email') }}</span>
                                                                <input type="email" id="email" name="email" class="w-full border !border-neutral-400 rounded-md h-14" placeholder="email@example.com">
                                                            </label>
                                                            <label for="phone" class="relative w-full">
                                                                <span class="text-black absolute bg-white -top-2.5 left-4 text-sm">{{ __('template.phone') }}</span>
                                                                <input type="text" id="phone" name="phone" class="w-full border !border-neutral-400 rounded-md h-14" placeholder="123123123123">
                                                            </label>
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <label for="street_address" class="relative w-full">
                                                                <span class="text-black absolute bg-white -top-2.5 left-4 text-sm">{{ __('template.street_address') }}</span>
                                                                <input type="text" id="street_address" name="house_number_street_name" class="w-full border !border-neutral-400 rounded-md h-14" placeholder="7398 Smoke Ranch Road">
                                                            </label>
                                                            <label for="appartament_suite_unit" class="relative w-full">
                                                                <input type="text" id="appartament_suite_unit" name="appartament_suite_unit" class="w-full border !border-neutral-400 rounded-md h-14" placeholder="Las Vegas, Nevada 89128">
                                                            </label>
                                                        </div>
                                                        <button class="text-center bg-blue-500 text-white font-semibold py-3 rounded-lg xl:hover:bg-blue-600">{{ __('template.save') }}</button>
                                                    </form>
                                                </div>  
                                            </div>
                                        <!-- Shipping Address End -->

                                        <!-- Billing Address Start -->
                                            <div x-data='{billing:false}' class="flex flex-col gap-4 xl:w-1/2">
                                                <ul class="flex justify-between text-xl font-semibold border-b pb-3">
                                                    <li>
                                                        <h1>{{ __('template.billing_address') }}</h1>
                                                    </li>
                                                    <li>
                                                        <button x-on:click='billing = !billing' class="text-lg"><i class="bi bi-pencil-square"></i></button>
                                                    </li>
                                                </ul>
                                                <div>
                                                    <ul x-show="!billing" class="flex flex-col gap-4 text-[15px]">
                                                        <div class="flex flex-col gap-2">
                                                            <li>
                                                                <h1 class="truncate sm:w-11/12">{{ __('template.name') }}: Annie Mario</h1>
                                                            </li>
                                                            <li>
                                                                <h1 class="truncate sm:w-11/12">{{ __('template.email') }}: email@example.com</h1>
                                                            </li>
                                                            <li>
                                                                <h1 class="truncate sm:w-11/12">{{ __('template.phone') }}: 123123123123</h1>
                                                            </li>
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <li>
                                                                <h1 class="truncate sm:w-11/12">7398 Smoke Ranch Road</h1>
                                                            </li>
                                                            <li>
                                                                <h1 class="truncate sm:w-11/12">Las Vegas, Nevada 89128</h1>
                                                            </li>
                                                        </div>
                                                    </ul>
                                                    
                                                    <form x-show="billing" class="flex flex-col gap-4 text-[15px]" x-cloak>
                                                        <div class="flex flex-col gap-3">
                                                            <label for="name" class="relative w-full">
                                                                <span class="text-black absolute bg-white -top-2.5 left-4 text-sm">{{ __('template.name') }}</span>
                                                                <input type="text" id="name" name="name" class="w-full border !border-neutral-400 rounded-md h-14" placeholder="Annie Mario">
                                                            </label>
                                                            <label for="email" class="relative w-full">
                                                                <span class="text-black absolute bg-white -top-2.5 left-4 text-sm">{{ __('template.email') }}</span>
                                                                <input type="email" id="email" name="email" class="w-full border !border-neutral-400 rounded-md h-14" placeholder="email@example.com">
                                                            </label>
                                                            <label for="phone" class="relative w-full">
                                                                <span class="text-black absolute bg-white -top-2.5 left-4 text-sm">{{ __('template.phone') }}</span>
                                                                <input type="text" id="phone" name="phone" class="w-full border !border-neutral-400 rounded-md h-14" placeholder="123123123123">
                                                            </label>
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <label for="street_address" class="relative w-full">
                                                                <span class="text-black absolute bg-white -top-2.5 left-4 text-sm">{{ __('template.street_address') }}</span>
                                                                <input type="text" id="street_address" name="house_number_street_name" class="w-full border !border-neutral-400 rounded-md h-14" placeholder="7398 Smoke Ranch Road">
                                                            </label>
                                                            <label for="appartament_suite_unit" class="relative w-full">
                                                                <input type="text" id="appartament_suite_unit" name="appartament_suite_unit" class="w-full border !border-neutral-400 rounded-md h-14" placeholder="Las Vegas, Nevada 89128">
                                                            </label>
                                                        </div>
                                                        <button class="text-center bg-blue-500 text-white font-semibold py-3 rounded-lg xl:hover:bg-blue-600">{{ __('template.save') }}</button>
                                                    </form>
                                                </div>  
                                            </div>
                                        <!-- Billing Address End -->
                                    </div>
                                </div>
                            <!-- Addresses show End-->
                            
                        </div>
                    <!-- Show End -->
                </div>
            <!-- Menus and show menu End-->
        </div>
    </div>
</x-app-layout>
<!-- Error Alert Start-->
    @foreach (['password', 'new_password', 'new_password_confirmation', 'name'] as $namesErrors)
        @error($namesErrors)
            <script type="module">
                toastr.error("{{ $message }}");
            </script>
        @enderror
    @endforeach
<!-- Error Alert End-->