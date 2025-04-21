<x-auth-layout>
    <div class="flex w-screen h-screen overflow-hidden">
        <!-- Left block Start-->
            <div class="relative">
                <img src="{{ asset('template/images/bg-image-9.jpg') }}" class="h-screen 2xl:w-[700px] xl:w-[800px] object-cover xl:block hidden" alt="Auth logo">
                <div class="absolute top-0 items-center xl:inline-flex hidden w-full justify-center pt-16">
                    <div class="flex flex-col gap-12">
                        <div>
                            <img src="{{ asset('template/images/logo-large.png') }}" class="h-10" alt="logo">
                        </div>
                        <h1 class="font-semibold text-3xl">{{ __('template.we_offer_the_best_products') }}</h1>
                    </div>
                </div>
            </div>
        <!-- Left block End-->

        <!-- Right Block Start-->
            <div class="px-3 w-screen">
                <!-- Iinfo Start-->
                    <div class="flex-col gap-12 xl:hidden inline-flex justify-center items-center w-full mt-5">
                        <div>
                            <img src="{{ asset('template/images/logo-large.png') }}" class="h-10" alt="logo">
                        </div>
                    </div>
                <!-- Iinfo End-->

                <!-- Form Start-->
                    <div class="flex justify-center items-center xl:mt-0 mt-10 xl:h-screen">
                        <div class="flex flex-col gap-4 xl:mb-48">
                            <ul class="flex flex-col gap-2 mx-4 xl:mx-0 xl:text-start text-center" style="padding-left: 0;">
                                <li>
                                    <h1 class="text-3xl font-bold">
                                        {{ __('template.sign_in_to_etrade') }}
                                    </h1>
                                </li>
                                <li>
                                    <p class="text-neutral-400 text-base">{{ __('template.enter_your_detail_below') }}</p>
                                </li>
                            </ul>

                            <form method="POST" action="{{ route('custom.login') }}" class="flex flex-col xl:gap-5 gap-3 xl:w-96 w-screen xl:px-0 px-4">
                                @csrf

                                <label for="access_code" class="relative w-full">
                                    <span class="text-neutral-400 absolute bg-white -top-2.5 left-4 text-sm @error('access_code') !text-red-500 @enderror">
                                        {{ __('template.email') }}<span class="text-red-500">*</span>
                                    </span>
                                    <input type="email" id="email" name="email" 
                                        class="w-full border !border-neutral-400 rounded-md h-14 @error('email') !border-red-500 @enderror" 
                                        onfocus="document.querySelector('.email').classList.remove('!text-red-500'); document.querySelector('#email').classList.remove('!border-red-500')"
                                        required autocomplete="off">
                                </label>

                                <label for="access_code" class="relative w-full">
                                    <span class="text-neutral-400 absolute bg-white -top-2.5 left-4 text-sm @error('access_code') !text-red-500 @enderror">
                                        {{ __('template.access_code') }}<span class="text-red-500">*</span>
                                    </span>
                                    <input type="password" id="access_code" name="access_code" 
                                        class="w-full border !border-neutral-400 rounded-md h-14 @error('access_code') !border-red-500 @enderror" 
                                        onfocus="document.querySelector('.access_code').classList.remove('!text-red-500'); document.querySelector('#access_code').classList.remove('!border-red-500')"
                                        required autocomplete="off">
                                </label>

                                <button type="submit" class="w-full h-fit px-5 py-3 text-white bg-blue-500 font-semibold rounded-lg">
                                    {{ __('template.sign_in') }}
                                </button>
                            </form>
                        </div>
                    </div>
                <!-- Form End-->
            </div>
        <!-- Right Block End-->
    </div>

    @error('access_code')
        <script type="module">
            toastr.error("{{ $message }}");
        </script>
    @enderror
</x-auth-layout> 