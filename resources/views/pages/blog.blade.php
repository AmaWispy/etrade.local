<x-app-layout class="flex flex-col gap-3">

    @section('title', __('template.blog'))

    @include('includes.layout.bread-crump', ['title' => __('template.blog')])
                
    <div class="flex justify-between xl:flex-row flex-col xl:!container xl:!mx-auto gap-5 mx-2 mt-5">
        <!-- Posts Start-->
            <div class="2xl:w-2/3 xl:w-[57%] w-full flex flex-col gap-4 space-y-3" id="blogs-posts">
                @include('includes.layout.blog.blogs', ['posts' => $posts])
            </div>
        <!-- Posts End-->

        <!-- Filters Start -->
        <div class="2xl:w-1/2 xl:w-2/5 w-full">
            @include('includes.blog.rightInfos',['tags' => $tags, 'latestPostsBlog' => $latestPostsBlog])
        </div>
        <!-- Filters End -->
    </div>

    <!-- Pagination Start -->
        <div class="container flex justify-start my-6">
            {!! $posts->links('pagination.default') !!}
        </div>
    <!-- Pagination End -->
</x-app-layout>

<script type="module">
    $(document).ready(function () {
        $('.slider-container').each(function (index) {
            const slider = $(this).find('.slider');
            const prevButton = $(this).find('.slick-prev');
            const nextButton = $(this).find('.slick-next');

            slider.slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                adaptiveHeight: true,
                arrows: false, // Отключаем стандартные кнопки
                dots: false,
            });

            // Обработчики для каждой карусели
            prevButton.on('click', function () {
                slider.slick('slickPrev');
            });

            nextButton.on('click', function () {
                slider.slick('slickNext');
            });
        });
    });
</script>