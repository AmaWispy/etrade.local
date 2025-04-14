<style>
    .blog-quote blockquote {
        border-left: 4px solid rgba(255, 65, 118, 1);
        border-radius: .50rem;
        background-color: #fdf2f8;
        padding: 18px;
        font-size: 18px;
        font-weight: 500;
        color: #5f5f5f;
        margin-top: 20px;
        margin-bottom: 20px;
        
    }

    strong {
        font-size: 25px;
        color: rgba(0, 0, 0, 0.829); 
        font-weight: 600;
    }

    figcaption{
        display: none;
    }

    .attachment-gallery{
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 25px;
        margin-bottom: 25px;
    }

    .attachment-gallery img{
        height: 230px;
        width: 400px;
        object-fit: cover
    }

    @media (max-width: 1440px) {
        .attachment-gallery img{
            height: 160px;
            width: 250px;
        }
    }
    @media (max-width: 1024px) {
        .attachment-gallery img{
            height: 200px;
            width: 350px;
        }
    }


    .slick-slide {
        display: flex !important;
        justify-content: space-between !important;
    }

    
</style>
<x-app-layout>

    @section('title', __('template.blog') . ' - ' . $post->title)

    @section('meta')
        <meta name="description" content="{{strip_tags($post->makeExcerpt($post->content, 150))}}">
        <!-- Facebook Open Graph Meta Tags -->
        <meta property="og:type" content="article">
        <meta property="og:title" content="{{$post->title}}">
        <meta property="og:description" content="{{strip_tags($post->makeExcerpt($post->content, 150))}}">
        <meta property="og:url" content="{{$post->link}}">
        <meta property="og:image" content="{{$post->getImage()}}">
        <meta property="og:site_name" content="{{config('app.url')}}">
    @endsection

    @php
        $images = $post->getImage('all');
    @endphp

    @section('microdata')
    <!-- Schema.org Microdata -->
    <script type="application/ld+json">
        {
            "@context": "http://schema.org",
            "@type": "BlogPosting",
            "headline": "{{$post->title}}",
            "description": "{{strip_tags($post->makeExcerpt($post->content, 150))}}",
            "datePublished": "{{\Carbon\Carbon::parse($post->published_at)->format('Y-m-d\TH:i:sO')}}",
            "dateModified": "{{\Carbon\Carbon::parse($post->updated_at)->format('Y-m-d\TH:i:sO')}}",
            "image": {
                "@type": "ImageObject",
                "url": "{{$post->getImage()}}"
            },
            "publisher": {
                "@type": "Organization",
                "name": "{{$templateSettings['company-legal-name']}}",
                "logo": {
                    "@type": "ImageObject",
                    "url": "{{$templateSettings['logo']}}"
                }
            }
        }
    </script>
    @endsection

    <div class="xl:container xl:!mx-auto mx-2.5 flex flex-col gap-2">
        <!-- Show Media Start -->
            <div class="my-10">
                <!-- Main Media Start-->
                    @if ($post->template === 'quote')
                        <div class="bg-pink-50 rounded-xl overflow-hidden w-full h-52 inline-flex items-center">
                            <div class="!h-full !w-2 block bg-pink-500"></div>
                            <div class="p-4">
                                @include('includes.blog.cards', ['post' => $post ])
                            </div>
                        </div>
                    @endif

                    @if ($post->template === 'image')
                        <div class='rounded-xl xl:h-[650px] h-auto w-full'>
                            <img src="{{ asset($post->getImage()) }}" class="object-cover rounded-lg h-full w-full" alt="{{ $post->title }}">
                        </div>
                    @endif

                    @if ($post->template === 'carousel')
                        <div class="relative rounded-lg overflow-hidden">
                            <div class="gallery-images rounded-lg xl:h-[670px] lg:h-[500px] md:h-[400px] sm:h-[370px]">
                                @foreach ($images as $image)
                                    <div class="w-full xl:h-[670px] lg:h-[500px] md:h-[400px] sm:h-[370px]">
                                        <img src="{{ asset($image->getUrl('')) }}" class="object-cover h-full w-full" alt="{{ $post->title }}">
                                    </div>
                                @endforeach
                            </div>
                        
                            <!-- Кнопки вынесены наружу, чтобы не мешать Slick -->
                            <button class="gallery-images-prev absolute left-2 top-1/2 text-neutral-400 bg-white lg:w-16 lg:h-16 h-12 w-12 rounded-lg text-2xl -translate-y-1/2 z-10">
                                <i class="bi bi-arrow-left"></i>
                            </button>
                            <button class="gallery-images-next absolute right-2 top-1/2 text-neutral-400 bg-white lg:w-16 lg:h-16 h-12 w-12 rounded-lg text-2xl -translate-y-1/2 z-10">
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    @endif

                    @if ($post->template === 'video')
                        <div class="w-full xl:h-[670px] lg:h-[500px] md:h-[400px] sm:h-[370px]">
                            <div class="relative h-full w-full rounded-lg overflow-hidden">
                                <iframe src="https://www.youtube.com/embed/{{ basename($post->url) }}" frameborder="0" class="w-full h-full absolute top-0 bottom-0 left-0 right-0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </div>
                    @endif
                <!-- Main Media End-->
            </div>
        <!-- Show Media End -->

        <!-- Infos Block Start -->
            <div class="flex xl:flex-row flex-col xl:gap-10 gap-4 justify-between relative">

                <!-- Share Blog Start -->
                    <div class="h-full xl:flex-col flex-row items-center xl:!sticky xl:!top-32 gap-2 text-neutral-400">
                        <h1 class="font-semibold">{{ __('template.share') }}:</h1>
                        <ul class="flex xl:!flex-col flex-row gap-1 text-[20px]">
                            <li>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class=" duration-700 xl:hover:bg-florarColor w-10 h-10 xl:hover:text-white flex justify-center items-center text-center rounded-full"><i class="bi bi-facebook"></i></a>
                            </li>
                            <li>
                                <a href="https://www.instagram.com" target="_blank" class=" duration-700 xl:hover:bg-florarColor w-10 h-10 xl:hover:text-white flex justify-center items-center text-center rounded-full"><i class="bi bi-instagram"></i></a>
                            </li>
                            <li>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" target="_blank" class=" duration-700 xl:hover:bg-florarColor w-10 h-10 xl:hover:text-white flex justify-center items-center text-center rounded-full"><i class="bi bi-twitter-x"></i></a>
                            </li>
                            <li>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" class=" duration-700 xl:hover:bg-florarColor w-10 h-10 xl:hover:text-white flex justify-center items-center text-center rounded-full"><i class="bi bi-linkedin"></i></a>
                            </li>
                            <li>
                                <a href="https://discord.com/channels/@me" target="_blank" class="duration-700 xl:hover:bg-florarColor w-10 h-10 xl:hover:text-white flex justify-center items-center text-center rounded-full"><i class="bi bi-discord"></i></a>
                            </li>
                        </ul>
                    </div>
                <!-- Share Blog End -->

                <!-- Main Blog Start -->
                    <div class="flex flex-col gap-4 w-full" x-data="{ comment_id: null, comment_reply_user_id: null, user_name: null }">
                        <!-- Blog Content Start -->
                            <div class="blog-quote text-neutral-500 space-y-5 w-full">
                                {!! $post->content !!}
                            </div>
                        <!-- Blog Content End -->
                        
                        <!-- Coments Start -->
                            <div>
                                @include('includes.buttons.comments')
                            </div>
                        <!-- Coments End -->

                        <!-- Send Comment Start -->
                            <div>
                                <h1 class="text-2xl font-semibold my-5">{{ __('template.leave_a_comment') }}</h1>
                                <ul class="flex items-center justify-end gap-2 my-2" x-show="user_name !== null" x-cloak>
                                    <li>
                                        <h1 class="text-lg font-medium">{{ __('template.answer') }}: </h1>
                                    </li>
                                    <li class="flex items-center text-base border rounded-xl bg-blue-500 text-white px-2.5 py-1.5 gap-2 w-fit">
                                        <h1 x-text="user_name"></h1>
                                        <button
                                        @click="comment_id = null; user_name = null; comment_reply_user_id = null">x</button>
                                    </li>
                                </ul>
                                
                                <form class="flex flex-col gap-3" id="form_send_comment">
                                    @csrf
                                    <input type="hidden" x-model="comment_id" name="comment_id" id="comment_id">
                                    <input type="hidden" x-model="comment_reply_user_id" name="comment_reply_user_id" id="comment_reply_user_id">
                                    <label for="message" class="relative w-full">
                                        <span class="text-neutral-500 absolute bg-white -top-2.5 left-4 text-sm">{{ __('template.message') }}<span class="text-red-500">*</span></span>
                                        <textarea name="message" id="message" maxlength="225" minlength="10" class="resize-none w-full h-40 border !border-neutral-300 rounded-md"></textarea>
                                    </label>
                                    @auth
                                        <button 
                                            id='send-btn' 
                                            class="text-white bg-blue-500 rounded-lg w-fit h-fit px-4 py-3 font-semibold"
                                        >{{ __('template.send_message') }}</button>
                                    @endauth

                                    @guest
                                        <a href="{{ route('auth.index') }}" class="text-white bg-blue-500 rounded-lg w-fit h-fit px-4 py-3 font-semibold">{{ __('template.send_message') }}</a>
                                    @endguest
                                </form>
                            </div>
                        <!-- Send Comment End -->
                    </div>
                <!-- Main Blog End -->

                <!-- Others Links Start -->
                    <div class="2xl:w-1/2 xl:w-2/5 w-full">
                        @include('includes.blog.rightInfos',['tags' => $tags, 'latestPostsBlog' => $latestPostsBlog])
                    </div>
                <!-- Others Links End -->
            </div>
        <!-- Infos Block End -->

        <!-- Realtion Blog Block Start -->
            <div class="mt-5 flex flex-col gap-3">
                <ul>
                    <li class="flex gap-1 items-center">
                        <span class="text-white w-fit h-fit p-1.5 bg-violet-500 rounded-full flex items-center"><i class="bi bi-bell"></i></span>
                        <h1 class="text-violet-500 font-semibold">{{ __('template.hot_news') }}</h1>
                    </li>
                    <li class="flex justify-between items-center h-12 ">
                        <h1 class="text-3xl font-bold">{{ __('template.related_blog') }}</h1>
                        <ul class="@if (count($relatedPosts) > 3) !flex @endif sm:flex 2xl:hidden xl:gap-3.5 lg:gap-4 md:gap-3 gap-2">
                            <li>
                                <button class="related-blogs-carousel-btn-prev bg-neutral-100 text-neutral-500 text-lg rounded-lg h-12 w-12 xl:hover:h-[50px] xl:hover:w-[50px] duration-200"><i class="bi bi-arrow-left"></i></button>
                            </li>
                            <li>
                                <button class="related-blogs-carousel-btn-next bg-neutral-100 text-neutral-500 text-lg rounded-lg h-12 w-12 xl:hover:h-[50px] xl:hover:w-[50px] duration-200"><i class="bi bi-arrow-right"></i></button>
                            </li>
                        </ul>
                    </li>
                </ul>

                <div class="flex related-blogs-carousel">
                    @foreach ($relatedPosts as $related)
                        @include('includes.blog.cards', ['post'=> null,'related' => $related])
                    @endforeach
                </div>
            </div>
        <!-- Realtion Blog Block End -->
    </div>
</x-app-layout>

<script type="module">
    $(document).ready(function(){
        /**
         * Slick
         */
        const buttons = {
            'gallery-images': '.gallery-images',
            'related-blogs-carousel-btn': '.related-blogs-carousel'
        };

        let totalSlides = $('.related-blogs-carousel').children().length; // Считаем элементы
        let slidesToShow = totalSlides === 2 ? 2 : 3;

        $('.gallery-images').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            adaptiveHeight: true,
            arrows: false, // Отключаем стандартные кнопки
            dots: false,
            prevArrow: $('.gallery-images-prev'),
            nextArrow: $('.gallery-images-next')
        })
        

        $('.related-blogs-carousel').slick({
            slidesToShow: slidesToShow,
            slidesToScroll: 1,
            adaptiveHeight: false,

            
            arrows: false, // Отключаем стандартные кнопки
            dots: false,
            infinity:true,
            prevArrow: $('.related-blogs-carousel-btn-prev'),
            nextArrow: $('.related-blogs-carousel-btn-next'),
            responsive: [
                {
                    breakpoint: 1440,
                    settings: {
                        slidesToShow: 2,
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                    }
                }
            ]
        })

        Object.entries(buttons).forEach(([btn, slider]) => {
            $(`.${btn}-prev`).on('click', function () {
                $(slider).slick('slickPrev');
            });

            $(`.${btn}-next`).on('click', function () {
                $(slider).slick('slickNext');
            });
        });

    })
</script>