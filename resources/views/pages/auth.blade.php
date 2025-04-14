<x-app-layout>
    @section('title', 'Auth')

    <div class="flex w-screen h-screen overflow-hidden" x-data='{signIn:true, signUp:false, forget:false}' x-cloak>
        <!-- Left block Start-->
            <div class="relative">
                <img x-show='signIn' src="{{ asset('template/images/bg-image-9.jpg') }}" class="h-screen 2xl:w-[700px] xl:w-[800px] object-cover xl:block hidden" alt="Auth logo">
                <img x-show='signUp' src="{{ asset('template/images/bg-image-10.jpg') }}" class="h-screen 2xl:w-[700px] xl:w-[800px] object-cover xl:block hidden" alt="Auth logo">
                <img x-show='forget' src="{{ asset('template/images/bg-image-10.jpg') }}" class="h-screen 2xl:w-[700px] xl:w-[800px] object-cover xl:block hidden" alt="Auth logo">
                <div class="absolute top-0 items-center xl:inline-flex hidden w-full  justify-center pt-16">
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
            <div class="px-3 w-screen border">
                <!-- Iinfo Start-->
                    <div class="flex-col gap-12 xl:hidden inline-flex justify-center items-center w-full mt-5">
                        <div>
                            <img src="{{ asset('template/images/logo-large.png') }}" class="h-10" alt="logo">
                        </div>
                    </div>
                <!-- Iinfo End-->

                <!-- Btn Sign Start-->
                    <div class="flex xl:justify-end justify-center w-full ">
                        <ul class="flex items-center gap-4 mt-10 xl:flex-row flex-col">
                            <li class="xl:text-base text-lg">
                                <h1 x-show='signIn'>{{ __('template.not_a_member') }}</h1>
                                <h1 x-show='signUp'>{{ __('template.already_a_member') }}</h1>
                                <h1 x-show='forget'>{{ __('template.already_a_member') }}</h1>
                            </li>
                            <li>
                                <button x-show='!forget' x-on:click='signIn = !signIn; signUp = !signUp;' class="bg-florarColor w-fit h-fit text-xl font-semibold px-5 py-3 text-white rounded-lg">
                                    <span x-show='signIn'>{{ __('template.sign_up_now') }}</span>
                                    <span x-show='signUp'>{{ __('template.sign_in') }}</span>
                                    <span x-show='forget'>{{ __('template.sign_in') }}</span>
                                </button>
                                <button x-show='forget' x-on:click='signIn = true; forget = false' class="bg-florarColor w-fit h-fit text-xl font-semibold px-5 py-3 text-white rounded-lg">
                                    <span x-show='forget'>{{ __('template.sign_in') }}</span>
                                </button>
                            </li>
                        </ul>
                    </div>
                <!-- Btn Sign End-->
                
                <!-- Form Start-->
                    <div class="flex justify-center items-center xl:mt-0 mt-10 xl:h-screen">
                        <div class="flex flex-col gap-4 xl:mb-48 ">
                            <ul class="flex flex-col gap-2 mx-4 xl:mx-0 xl:text-start text-center">
                                <li>
                                    <h1 class="text-3xl font-bold">
                                        <span x-show='signIn'>{{ __('template.sign_in_to_etrade') }}</span>
                                        <span x-show='signUp'>{{ __('template.im_new_here') }}</span>
                                        <span x-show='forget'>{{ __('template.Forgot_Password') }}</span>
                                    </h1>
                                </li>
                                <li>
                                    <p x-show='!forget' class="text-neutral-400 text-base">{{ __('template.enter_your_detail_below') }}</p>
                                    <p x-show='forget' class="text-neutral-400 text-base 2xl:w-[600px] xl:w-[450px]">{{ __('template.Enter_Email_Password_Reset') }}</p>
                                </li>
                            </ul>

                                <form method="POST" 
                                    x-bind:action="signIn ? '{{ route('login') }}' : signUp ? '{{ route('register') }}' : forget ? '{{ route('password.email') }}' : ''"  
                                    class="flex flex-col xl:gap-5 gap-3 xl:w-96 w-screen xl:px-0 px-4">
                                    @csrf
                                    <input id="remember_me" type="checkbox" checked class="hidden" name="remember" >

                                    <label for="name" class="relative w-full" x-show='signUp'>
                                        <span class="name text-neutral-400 absolute bg-white -top-2.5 left-4 text-sm @error('name') !text-red-500 @enderror">{{ __('template.User_Name') }}<span class="text-red-500">*</span></span>
                                        <input type="text" id="name" name="name" x-bind:required='signUp' placeholder="User123"
                                            class="w-full border !border-neutral-400 rounded-md h-14 @error('name') !border-red-500 @enderror"  
                                            onfocus="document.querySelector('.name').classList.remove('!text-red-500'); document.querySelector('#name').classList.remove('!border-red-500')">
                                    </label>

                                    <label for="email" class="relative w-full">
                                        <span class="email text-neutral-400 absolute bg-white -top-2.5 left-4 text-sm @error('email') !text-red-500 @enderror">E-mail<span class="text-red-500">*</span></span>
                                        <input type="email" id="email" name="email" required placeholder="user@example.com"
                                            onfocus="document.querySelector('.email').classList.remove('!text-red-500'); document.querySelector('#email').classList.remove('!border-red-500')"
                                            class="w-full border !border-neutral-400 rounded-md h-14 @error('email') !border-red-500 @enderror">
                                    </label>

                                    <label for="password" x-show='!forget' class="relative w-full">
                                        <span class="password text-neutral-400 absolute bg-white -top-2.5 left-4 text-sm @error('password') !text-red-500 @enderror">{{ __('template.password') }} <span class="text-red-500">*</span></span>
                                        <input type="password" id="password" name="password"  x-bind:required='!forget' placeholder="••••••••"
                                            class="w-full border !border-neutral-400 rounded-md h-14 @error('password') !border-red-500 @enderror"
                                            onfocus="document.querySelector('.password').classList.remove('!text-red-500'); document.querySelector('#password').classList.remove('!border-red-500')">
                                    </label>

                                    <ul class="flex justify-between items-center">
                                        <li>
                                            <button type="submit" class="w-fit h-fit px-5 py-3 text-white bg-blue-500 font-semibold rounded-lg">
                                                <span x-show='signIn'>{{ __('template.sign_in') }}</span>
                                                <span x-show='signUp'>{{ __('template.create_account') }}</span>
                                                <span x-show='forget'>{{ __('template.Send_Reset_Instructions') }}</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" x-on:click='forget = !forget; signIn = false; singUp = false' class="text-blue-500" x-show='signIn'>{{ __('template.forget_password') }}</button>
                                        </li>
                                    </ul>
                                </form>
                        </div>
                    </div>
                </div>
            <!-- Form End-->
        </div>
    <!-- Right Block Start-->
</x-app-layout>
<!-- Error Alert Start-->
    @foreach (['password', 'name', 'email'] as $namesErrors)
        @error($namesErrors)
            <script type="module">
                toastr.error("{{ $message }}");
            </script>
        @enderror
    @endforeach
<!-- Error Alert End-->