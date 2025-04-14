<x-app-layout>
    @php
        $blockInfo = [
            [
                'image' => 'template/images/shape-01.png',
                'title' => __('template.happy_customers'),
            ],
            [
                'image' => 'template/images/shape-02.png',
                'title' => __('template.years_experience'),
            ],
            [
                'image' => 'template/images/shape-03.png',
                'title' => __('template.12_awards_won'),
            ],
        ];

        $teams = [
            [
                'image' => 'template/images/team-01.png',
                'name' => 'Rosalina D. Wilson',
                'job_name' => __('template.founder'),
            ],
            [
                'image' => 'template/images/team-02.png',
                'name' => 'Ukolilix X. Xilanorix',
                'job_name' => __('template.ceo'),
            ],
            [
                'image' => 'template/images/team-03.png',
                'name' => 'Alonso M. Miklonax',
                'job_name' => __('template.designer'),
            ],
            [
                'image' => 'template/images/team-04.png',
                'name' => 'Alonso M. Miklonax',
                'job_name' => __('template.designer'),
            ],
        ];
    @endphp
    @section('title', $page->title)

    <!-- BREADCRUMB AREA START -->
        @include('includes.layout.bread-crump')
    <!-- BREADCRUMB AREA END -->

    <!-- ABOUT US AREA START -->
        <div>
            <!-- 1 Block Start -->
                <div class="flex xl:flex-row flex-col items-center gap-10 justify-between mt-5 container">
                    <div class="xl:w-auto xl:h-auto w-full lg:h-[800px] md:h-[550px]">
                        <img src="{{ asset('template/images/about-01.png') }}" class="h-full w-full object-cover rounded-xl" alt="About Us Image">
                    </div>
                    <div class="xl:w-3/4 flex flex-col gap-4">
                        <div class="flex-col flex gap-4">
                            <ul class="flex flex-col gap-1 font-semibold">
                                <li>
                                    <h1 class="text-blue-500 text-lg "><span class="bg-blue-500 text-white w-fit h-fit px-2 py-1 rounded-full "><i class="bi bi-basket"></i></span> {{ __('template.about_store') }}</h1>
                                </li>
                                <li>
                                    <h1 class="xl:text-3xl lg:text-4xl md:text-3xl text-2xl text-bold">{{ __('template.online_shopping') }}</h1>
                                </li>
                            </ul>
                            <p class="text-neutral-500 xl:text-lg lg:text-xl md:text-lg">{{ __('template.salesforce_b2c_commerce') }}</p>
                        </div>

                        <ul class="text-neutral-500 flex justify-between">
                            <li>
                                <p class="xl:w-2/3 lg:w-3/4 xl:text-lg lg:text-xl md:text-lg">{{ __('template.empower_sales_teams') }}</p>
                            </li>
                            <li>
                                <p class="xl:w-3/4 lg:w-3/4 xl:text-lg lg:text-xl md:text-lg">{{ __('template.salesforce_b2b_commerce') }}</p>
                            </li>
                        </ul>
                    </div>
                </div>
            <!-- 1 Block End -->

            <!-- Blocks info Start-->
                <div class="container relative mt-20">
                    <div class="flex xl:flex-row flex-col xl:gap-0 gap-5 justify-between absolute top-0 w-full">
                        @foreach ($blockInfo as $info )
                            <div class="flex bg-white rounded-md flex-col gap-2 shadow-lg 2xl:w-96 xl:w-80 p-5">
                                <div>
                                    <img src="{{ asset($info['image']) }}" alt="{{ $info['title'] }}">
                                </div>
                                <h1 class="font-semibold xl:text-xl lg:text-2xl md:text-xl text-lg">{{ $info['title'] }}</h1>
                                <p class="text-neutral-500 lg:text-xl xl:text-base md:text-lg text-base">{{ __('template.empower_sales_teams') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            <!-- Blocks info End-->

            <!-- Teams Start-->
                <div class="bg-neutral-200 xl:mt-56 xl:pt-48 lg:mt-[450px] md:mt-[520px] mt-[540px] pt-[480px] pb-14">
                    <div class="container">
                        <div class="flex flex-col gap-5">   
                            <div class="flex justify-between items-center">
                                <ul class="flex flex-col gap-2">
                                    <li>
                                        <p class="text-violet-500 font-semibold"><span><i class="bi bi-people w-fit h-fit py-1.5 px-2 bg-violet-500 rounded-full text-white"></i></span> {{ __('template.our_team') }}</p>
                                    </li>
                                    <li>
                                        <h1 class="text-3xl font-semibold">{{ __('template.expert_management_team') }}</h1>
                                    </li>
                                </ul>
                                <ul class="flex gap-4 md:text-2xl text-xl">
                                    <li><button id="prev-slide"><i class="bi bi-arrow-left"></i></button></li>
                                    <li><button id="next-slide"><i class="bi bi-arrow-right"></i></button></li>
                                </ul>
                            </div>
                            <div class="slick-images">
                                @foreach ( $teams as $team)
                                    <div class="flex flex-col">
                                        <div class="w-80 h-96">
                                            <img src="{{ asset($team['image']) }}" class="rounded-lg object-cover h-full w-full" alt="{{ $team['name'] }}">
                                        </div>
                                        <ul class="flex flex-col mt-3">
                                            <li>
                                                <p class="text-neutral-500">{{ $team['job_name'] }}</p>
                                            </li>
                                            <li>
                                                <p class="text-xl font-medium">{{ $team['name'] }}</p>
                                            </li>
                                        </ul>
                                    </div>
                                @endforeach
                                @foreach ( $teams as $team)
                                    <div class="flex flex-col">
                                        <div class="w-80 h-96">
                                            <img src="{{ asset($team['image']) }}" class="rounded-lg object-cover h-full w-full" alt="{{ $team['name'] }}">
                                        </div>
                                        <ul class="flex flex-col mt-3">
                                            <li>
                                                <p class="text-neutral-500">{{ $team['job_name'] }}</p>
                                            </li>
                                            <li>
                                                <p class="text-xl font-medium">{{ $team['name'] }}</p>
                                            </li>
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            <!-- Teams End-->

            <!-- Info block Start-->
                <div class="container mt-16 flex flex-col gap-16">
                    <div class="flex xl:flex-row flex-col gap-5 items-center">
                        <div class="xl:h-[350px] xl:w-[800px] w-full lg:h-[450px]">
                            <img src="{{ asset('template/images/about-02.png') }}" class="h-full w-full object-cover rounded-md" class="rounded-md" alt="">
                        </div>
                        <div class="flex flex-col gap-4">
                            <ul class="flex flex-col gap-4">
                                <li>
                                    <h1 class="xl:text-2xl lg:text-3xl md:text-2xl text-xl font-bold ">Lorem ipsum dolor sit.</h1>
                                </li>
                                <li>
                                    <p class="text-neutral-500 2xl:w-2/3 xl:1/2 xl:text-base lg:text-lg md:text-base">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ratione ducimus, eum id laudantium enim possimus ad laboriosam iste a quam quas accusamus nesciunt, temporibus doloremque ipsa tempora maxime, assumenda tenetur!</p>
                                </li>
                            </ul>
                            <a href="" class="border font-semibold w-fit h-fit px-5 py-3.5 rounded-md xl:hover:bg-blue-500 xl:hover:text-white duration-300 xl:text-lg lg:text-xl md:text-lg">{{ __('template.get_in_touch') }}</a>
                        </div>
                    </div>

                    <div class="flex xl:flex-row-reverse flex-col gap-5 items-center">
                        <div class="xl:h-[350px] xl:w-[800px] w-full lg:h-[450px]">
                            <img src="{{ asset('template/images/about-02.png') }}" class="h-full w-full object-cover rounded-md" class="rounded-md" alt="">
                        </div>
                        <div class="flex flex-col gap-4">
                            <ul class="flex flex-col gap-4">
                                <li>
                                    <h1 class="xl:text-2xl lg:text-3xl font-bold md:text-2xl text-xl">Lorem ipsum dolor sit.</h1>
                                </li>
                                <li>
                                    <p class="text-neutral-500 2xl:w-2/3 xl:1/2 xl:text-base lg:text-lg md:text-base">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ratione ducimus, eum id laudantium enim possimus ad laboriosam iste a quam quas accusamus nesciunt, temporibus doloremque ipsa tempora maxime, assumenda tenetur!</p>
                                </li>
                            </ul>
                            <a href="" class="border font-semibold w-fit h-fit px-5 py-3.5 xl:text-lg lg:text-xl md:text-lg rounded-md xl:hover:bg-blue-500 xl:hover:text-white duration-300">{{ __('template.get_in_touch') }}</a>
                        </div>
                    </div>
                </div>
            <!-- Info block End-->
        </div>
    <!-- ABOUT US AREA END -->
</x-app-layout>

<script type="module">
    $(document).ready(function(){
        $('.slick-images').slick({
            infinite:true,
            slidesToShow:4,
            slidesToScroll:1,
            autoplay:true,
            arrows:false,
            centerMode: true,
            centerPadding: '20px', 
            responsive: [
            {
                breakpoint: 1440, // Когда экран меньше 1024px
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 1024, // Когда экран меньше 768px
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 768, // Когда экран меньше 480px
                settings: {
                    slidesToShow: 1
                }
            }
        ]
        })

        $('#prev-slide').click(function() {
            $('.slick-images').slick('slickPrev'); // Переход к предыдущему слайду
        });

        $('#next-slide').click(function() {
            $('.slick-images').slick('slickNext'); // Переход к следующему слайду
        });
    })
</script>