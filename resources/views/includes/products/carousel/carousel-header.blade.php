<ul class="flex flex-col gap-1 mx-2.5 xl:mx-0">
    <li class="flex items-center gap-2">
        <p class="h-6 w-6 bg-{{ $color }} text-center items-center text-sm flex justify-center rounded-full text-white"><i class="{{ $nameIcon }} ml-[.9px]"></i></p>
        <h1 class="text-base text-{{ $color }} font-medium">{{ $when }}</h1>
    </li>
    <li>
        <ul class="flex items-center justify-between">
            <li>
                <h1 class="text-3xl font-bold">{{ $title }}</h1>
            </li>
            <li class="flex items-center gap-3 h-[55px]">
                <button id="{{ $id_name }}-prev-btn" class="border duration-100 !border-gray-50 xl:h-[50px] xl:w-[50px] h-[40px] w-[40px] xl:hover:h-[52px] xl:hover:w-[52px] rounded-lg bg-gray-100"><i class="bi bi-arrow-left"></i></button>
                <button id="{{ $id_name }}-next-btn" class="border duration-100 !border-gray-50 xl:h-[50px] xl:w-[50px] h-[40px] w-[40px] xl:hover:h-[52px] xl:hover:w-[52px] rounded-lg bg-gray-100"><i class="bi bi-arrow-right"></i></button>
            </li>
        </ul>
    </li>
</ul>