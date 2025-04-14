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
                    <div class="rounded-full border w-20 h-20">
                        <img src="{{ asset('template/images/avatar-template.png') }}" class="h-full w-full object-cover border rounded-full" alt="Avatar">
                    </div>
                    <ul>
                        <li>
                            <h1 class="text-lg font-semibold">{{ __('template.hello') . ' ' . auth()->user()->name . '!' }}</h1>
                        </li>
                        <li>
                            <p class="text-base text-neutral-500">{{ config('app.name') . ' ' . __("template.member_since") . ' ' . auth()->user()->created_at->format('d-m-Y') }}</p>
                        </li>
                    </ul>
                </div>
            <!-- Account Header End-->

            <!-- Menus and show menu Start-->
                <div class="mt-5 flex xl:flex-row flex-col gap-6 xl:gap-14" @if(session()->has('AccountDetailsPage'))
                    x-data='{dashboard:false, orders:false, downloads:false, address:false, details:true}'
                @else
                    x-data='{dashboard:true, orders:false, downloads:false, address:false, details:false}'
                @endif>
                    <!-- Menu Start -->
                        <div class="border rounded-xl p-4 xl:w-96 h-fit">
                            <ul class="flex flex-col gap-3.5 text-lg  ">
                                <li x-bind:class="dashboard ? 'text-blue-500 bg-neutral-100' : 'text-neutral-600' " class="duration-500 py-2 rounded-lg pl-6">
                                    <button x-on:click='dashboard = true; orders = false; downloads = false; address = false; details = false' class="flex gap-2.5 items-center w-full"><i class="bi bi-border-all"></i> {{ __('template.dashboard') }}</button>
                                </li>
                                <li  x-bind:class="orders ? 'text-blue-500 bg-neutral-100' : 'text-neutral-600' " class="duration-500  py-2 rounded-lg pl-6 ">
                                    <button x-on:click='dashboard = false; orders = true; downloads = false; address = false; details = false' class="flex gap-2.5 items-center w-full"><i class="bi bi-basket2-fill"></i> {{ __('template.orders') }}</button>
                                </li>
                                <li  x-bind:class="downloads ? 'text-blue-500 bg-neutral-100' : 'text-neutral-600' " class="duration-500  py-2 rounded-lg pl-6">
                                    <button x-on:click='dashboard = false; orders = false; downloads = true; address = false; details = false' class="flex gap-2.5 items-center w-full"><i class="bi bi-file-earmark-arrow-down-fill"></i> {{ __('template.downloads') }}</button>
                                </li>
                                <li  x-bind:class="address ? 'text-blue-500 bg-neutral-100' : 'text-neutral-600' " class="duration-500  py-2 rounded-lg pl-6">
                                    <button x-on:click='dashboard = false; orders = false; downloads = false; address = true; details = false' class="flex gap-2.5 items-center w-full"><i class="bi bi-house-door-fill"></i> {{ __('template.address') }}</button>
                                </li>
                                <li  x-bind:class="details ? 'text-blue-500 bg-neutral-100' : 'text-neutral-600' " class="duration-500  py-2 rounded-lg pl-6">
                                    <button x-on:click='dashboard = false; orders = false; downloads = false; address = false; details = true' class="flex gap-2.5 items-center w-full"><i class="bi bi-person-fill"></i> {{ __('template.account_details') }}</button>
                                </li>
                                <li class="py-2 rounded-lg pl-6">
                                    <form action="{{ route('logout') }}" method="POST">
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
                                        <h1 class="text-lg flex gap-1.5">{{ __('template.hello') . ' ' . auth()->user()->name }} {{ '(' . __('template.not') }} <span class="font-semibold">{{ auth()->user()->name . '?' }}</span> 
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
                                            <ul class="flex xl:flex-row gap-2 flex-col xl:items-center xl:justify-around py-3 border-b text-base">
                                                <li class="xl:w-20 flex gap-2">
                                                    <h1 class="font-medium xl:hidden block">{{ __('template.order') }}:</h1>
                                                    <h1 class="text-red-500">#5678</h1>
                                                </li>
                                                <li class="xl:w-24 flex gap-2">
                                                    <h1 class="font-medium xl:hidden block">{{ __('template.date') }}:</h1>
                                                    <h1>10.02.2029</h1>
                                                </li>
                                                <li class="xl:w-32 flex gap-2">
                                                    <h1 class="font-medium xl:hidden block">{{ __('template.status') }}:</h1>
                                                    <h1>{{ __('template.processing_v2') }}</h1>
                                                </li>
                                                <li class="xl:w-64 flex gap-2">
                                                    <h1 class="font-medium xl:hidden block">{{ __('template.total') }}:</h1>
                                                    <h1>$234.23 {{ __('template.for') . ' 2 ' . __('template.items') }}</h1>
                                                </li>
                                                <li class="py-2.5 px-4 border rounded-lg xl:hover:bg-blue-500 xl:hover:text-white duration-300 cursor-pointer w-fit xl:mt-0 mt-2">
                                                    <a class="lg:text-lg text-[18px] font-medium cursor-pointer">
                                                        {{ __('view') }}
                                                    </a>
                                                </li>
                                            </ul>

                                            <ul class="flex xl:flex-row gap-2 flex-col xl:items-center xl:justify-around py-3 border-b text-base">
                                                <li class="xl:w-20 flex gap-2">
                                                    <h1 class="font-medium xl:hidden block">{{ __('template.order') }}:</h1>
                                                    <h1 class="text-red-500">#5679</h1>
                                                </li>
                                                <li class="xl:w-24 flex gap-2">
                                                    <h1 class="font-medium xl:hidden block">{{ __('template.date') }}:</h1>
                                                    <h1>12.02.2029</h1>
                                                </li>
                                                <li class="xl:w-32 flex gap-2">
                                                    <h1 class="font-medium xl:hidden block">{{ __('template.status') }}:</h1>
                                                    <h1>{{ __('template.on_hold') }}</h1>
                                                </li>
                                                <li class="xl:w-64 flex gap-2">
                                                    <h1 class="font-medium xl:hidden block">{{ __('template.total') }}:</h1>
                                                    <h1>$231234.23 {{ __('template.for') . ' 200 ' . __('template.items') }}</h1>
                                                </li>
                                                <li class="py-2.5 px-4 border rounded-lg xl:hover:bg-blue-500 xl:hover:text-white duration-300 cursor-pointer w-fit xl:mt-0 mt-2">
                                                    <a class="lg:text-lg text-[18px] font-medium cursor-pointer">
                                                        {{ __('view') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    <!-- Orders End -->
                                </div>
                            <!-- Orders show End -->

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
                            
                            <!-- Account Details Start-->
                                <form action="{{ route('account.update.password') }}" method="POST" x-show='details' class="flex flex-col gap-4" x-cloak>
                                    @csrf
                                    <div class="flex flex-col gap-3">
                                        <label for="name" class="relative w-full">
                                            <span class="text-neutral-500 absolute bg-white -top-2.5 left-4 text-sm">{{ __('template.first_name') }}</span>
                                            <input type="text" id="name" name="name" class="w-full border !border-neutral-300 rounded-md h-14" value="{{ auth()->user()->name }}">
                                        </label>
                                        <label for="country_region" class="relative w-full">
                                            <span class="text-neutral-500 absolute bg-white -top-2.5 left-4 text-sm">{{ __('template.country_region') }}</span>
                                            <select name="country_region" class="w-full border !border-neutral-300 rounded-md h-14">
                                                <option value="">2</option>
                                                <option value="">2</option>
                                            </select>
                                            <p class="text-sm mt-1">{{ __('template.display_name_info') }}</p>
                                        </label>
                                    </div>
                                    <div class="flex flex-col gap-4">
                                        <h1 class="text-xl font-semibold">{{ __('template.password_change') }}</h1>
                                        <div class="flex flex-col gap-3">
                                            <label for="password" class="relative w-full">
                                                <span class="password text-neutral-500 absolute bg-white -top-2.5 left-4 text-sm @error('password') !text-red-500 @enderror">{{ __('template.password') }}</span>
                                                <input type="password" id="password" name="password" 
                                                    class="password w-full border !border-neutral-300 @error('password') !border-red-500 @enderror rounded-md h-14" 
                                                    onfocus="document.querySelector('.password').classList.remove('!text-red-500'); document.querySelector('#password').classList.remove('!border-red-500')" value="{{ old('password') }}">
                                            </label>
                                            <label for="new_password" class="relative w-full">
                                                <span class="text-neutral-500 absolute bg-white -top-2.5 left-4 text-sm @error('new_password') !text-red-500 @enderror">{{ __('template.new_password') }}</span>
                                                <input type="password" id="new_password" name="new_password" 
                                                    class="new_password w-full border !border-neutral-300 rounded-md h-14 @error('new_password') !border-red-500 @enderror"
                                                    onfocus="document.querySelector('.new_password').classList.remove('!text-red-500'); document.querySelector('#new_password').classList.remove('!border-red-500')">
                                            </label>
                                            <label for="new_password_confirmation" class="relative w-full">
                                                <span class="new_password_confirmation text-neutral-500 absolute bg-white -top-2.5 left-4 text-sm @error('new_password') !text-red-500 @enderror">{{ __('template.confirm_new_password') }}</span>
                                                <input type="password" id="new_password_confirmation" name="new_password_confirmation" 
                                                class="new_password_confirmation w-full border !border-neutral-300 rounded-md h-14 @error('new_password') !border-red-500 @enderror"
                                                onfocus="document.querySelector('.new_password_confirmation').classList.remove('!text-red-500'); document.querySelector('#new_password_confirmation').classList.remove('!border-red-500')">
                                            </label>
                                        </div>
                                    </div>
                                    <button class="bg-blue-500 xl:hover:bg-blue-500 font-semibold py-3.5 text-center w-40 text-white rounded-md">{{ __('template.save') }}</button>
                                </form>
                            <!-- Account Details End-->
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