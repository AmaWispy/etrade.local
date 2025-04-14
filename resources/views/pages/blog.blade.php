<x-app-layout>

    @section('title', __('template.blog'))

    @include('includes.layout.bread-crump', ['title' => __('template.blog')])
                
    <div class="flex justify-between flex-row container mx-auto gap-5">
        <!-- Posts Start-->
            <div class="w-2/3 flex flex-col gap-4 space-y-3">
                @if (isset($posts) && !empty($posts))
                    @foreach($posts as $post) 
                        <!-- Quote Template Start -->
                            @if ($post['template'] === 'quote')
                                <div class="bg-pink-50 rounded-xl overflow-hidden w-full min-h-80 flex">
                                    <div class="h-full w-4 bg-pink-300"></div>
                                    <div class="p-4">
                                        @include('includes.blog.cards', ['post' => $post ])
                                    </div>
                                </div>
                            @endif
                        <!-- Quote Template End -->
                            
                        <!-- Image Template Start -->
                            @if ($post['template'] === 'image')
                                <div class="w-full min-h-[600px] flex flex-col justify-between gap-3">
                                    <div class="h-full w-full rounded-lg">
                                        <a href="{{ $post->link }}" class="h-full w-full">
                                            <img src="{{ $post->getImage() }}" alt="{{ $post->title }}" class="h-full w-full rounded-lg object-cover">
                                        </a>
                                    </div>
                                    @include('includes.blog.cards', ['post' => $post ])
                                </div>
                            @endif
                        <!-- Image Template End -->
                        
                        <!-- Video Template Start -->
                            @if ($post['template'] === 'video')
                                <div class="w-full min-h-[720px] flex flex-col justify-between gap-3">
                                    <div class="relative h-full w-full rounded-lg overflow-hidden">
                                        <iframe src="https://www.youtube.com/embed/{{ basename($post->url) }}" frameborder="0" class="w-full h-full absolute top-0 bottom-0 left-0 right-0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                    @include('includes.blog.cards', ['post' => $post ])
                                </div>
                            @endif
                        <!-- Video Template End -->
                        
                        <!-- Carousel Template Start -->
                            @if ($post['template'] === 'carousel')
                                <div class="w-full min-h-[600px] flex flex-col justify-between gap-3">
                                    <div class="relative slider-container">
                                        <button class="slick-prev absolute left-2 top-1/2 text-neutral-400 bg-white w-16 h-16 rounded-lg text-2xl -translate-y-1/2 z-10"><i class="bi bi-arrow-left"></i></button>
                                        <div class="slider w-full h-[550px] overflow-hidden">
                                            @foreach ($post->getImage('all') as $image)
                                                <div class="h-full w-full">
                                                    <img src="{{ asset($image->getUrl('')) }}" class="object-cover h-full w-full" alt="{{ $post->title }}">
                                                </div>
                                            @endforeach
                                        </div>
                                        <button class="slick-next absolute right-2 top-1/2 text-neutral-400 bg-white w-16 h-16 rounded-lg text-2xl -translate-y-1/2 z-10"><i class="bi bi-arrow-right"></i></button>
                                    </div>
                                    @include('includes.blog.cards', ['post' => $post ])
                                </div>
                            @endif
                        <!-- Carousel Template End -->


                        {{-- <a href="{{$post->link}}">
                            <img src="{{ $post->getImage() }}" alt="{{$post->title}}" />
                        </a>

                        <ul>
                            <li>{{ $post->publishedDate }}</li>
                        </ul>
                        <h3><a href="{{$post->link}}">{{$post->title}}</a></h3>
                        {!! $post->makeExcerpt($post->content, 300) !!}
                        <a href="{{$post->link}}">
                            {{ __('template.continue_reading') }}
                        </a>                    --}}
                    @endforeach
                @endif
            </div>
        <!-- Posts End-->

        <!-- Filters Start -->
            @include('includes.blog.rightInfos',['tags' => $tags, 'latestPostsBlog' => $latestPostsBlog])
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