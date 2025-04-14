<x-app-layout>

    @section('title', '404')

    <!-- 404 Page Start-->
        <div class="container mx-auto xl:flex-row flex-col flex my-22 items-center justify-between">
            <div class="xl:w-1/2 flex flex-col gap-6 text-center items-center justify-center xl:justify-normal xl:items-start xl:!text-start">
                <div class="flex flex-col gap-2">
                    <p class="text-florarColor font-medium"><i class="bi bi-info-circle bg-florarColor rounded-full p-1.5 inline-flex text-white"></i></span> {{ __('template.oops_something_missing') }}</p>
                    <h1 class="text-4xl font-bold">{{ __('template.page_not_found') }}</h1>
                </div>
                <p class="text-neutral-500">{{ __('template.page_not_found_message') }}</p>
                <a href="/" class="text-white bg-florarColor inline-flex gap-1 w-fit md:px-[25px] md:py-3 py-2 px-[20px] rounded-md items-center font-semibold md:text-xl text-lg">{{ __('template.back_to_home') }} <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="mt-10">
                <img src="{{ asset('template/images/404.png') }}" class="lg:h-96 md:h-72 h-60 " alt="404 page not found">
            </div>
        </div>
    <!-- 404 Page End-->

</x-app-layout>