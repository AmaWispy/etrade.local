@if (isset($post) && $post !== null)
    <!-- Default Post Start -->
        <div class="flex flex-col justify-between gap-3">
            <ul class="flex flex-col gap-1">
                <!-- Title Slug Start -->
                    <li>
                        @if (Route::currentRouteName() === 'blog.show')
                            <h1 class="xl:text-3xl lg:text-2xl md:text-xl text-lg font-semibold">{{ $post->title }}</h1>
                        @else
                            <a href="{{ route('blog.show', ['slug' => $post->slug]) }}" class="duration-500 xl:hover:text-blue-500 !text-bold md:text-2xl sm:text-xl ">{{ $post->title }}</a>
                        @endif
                    </li>
                <!-- Title Slug End -->

                <!-- Author Info Viewed and Date Start -->
                    <li>
                        <div class="flex gap-3 items-center my-3">
                            <div class="w-12 h-12 rounded-full">
                                <img src="{{ asset( $post->author->getImage()) }}" alt="{{ $post->author->name }}" class="rounded-full object-cover h-full w-full">
                            </div>
                            <ul class="flex flex-col gap-1">
                                <li class="text-base font-medium">{{ $post->author->name }}</li>
                                <div class="flex items-center lg:text-sm text-xs text-neutral-500">
                                    <li>{{ $post->publishedDate }}</li>
                                    <li class="mx-2">|</li>
                                    <li>{{ $post->viewed . ' ' . __('template.views') }}</li>
                                </div>
                            </ul>
                        </div>
                    </li>
                <!-- Author Info Viewed and Date End -->

                <!-- Preview Start -->
                    @if($post->preview !== null && Route::currentRouteName() !== 'blog.show')
                        <li>
                            <p class="text-neutral-500">
                                {!! $post->preview !!}
                            </p>
                        </li>
                    @endif
                <!-- Preview End -->
            </ul>

            <!-- Post Link Start -->
                @if ($post->link !== null && Route::currentRouteName() !== 'blog.show')
                    <a href="{{ route('blog.show', ['slug' => $post->slug]) }}" class="bg-blue-500 py-3 px-4 w-fit text-white lg:text-lg font-medium rounded-lg md:text-base text-sm cursor-pointer">
                        {{ __('template.read_more') }}
                    </a>
                @endif
            <!-- Post Link End -->
        </div>
    <!-- Default Post End -->
@elseif (isset($related) && $related !== null) 
    <!-- Related Posts Start -->
        <div class="flex flex-col 2xl:min-w-[400px] lg:w-[400px] 2xl:h-[400px] xl:h-[430px] lg:h-[400px] md:h-[380px] h-[355px] justify-between overflow-hidden xl:mr-5 mr-3 ">
            <!-- Image Viedo or Quote Start -->
                @if (in_array($related->template, ['carousel', 'image']))
                    <!-- Image Start -->
                        <div class="w-full  overflow-hidden rounded-lg xl:h-[300px] lg:h-[250px] md:h-[230px] h-[200px]">
                            <a href="{{ route('blog.show', ['slug' => $related->slug]) }}" class="w-full h-full">
                                <img src="{{ asset($related->getImage()) }}" class="w-full h-full object-cover" alt="{{ $related['title'] }}">
                            </a>
                        </div> 
                    <!-- Image End -->
                @elseif ($related->template === 'quote')
                    <!-- Quote Start -->
                        <div class="bg-pink-50 rounded-xl overflow-hidden w-full xl:h-[300px] lg:h-[250px] md:h-[230px] h-[200px] flex items-center xl:text-2xl text-xl font-medium ">
                            <div class="h-full w-4 bg-pink-300 mr-4"></div>
                            {!! $related->makeExcerpt($related->preview, 100) !!}
                        </div>
                    <!-- Quote End -->
                @elseif ($related->template === 'video')
                    <!-- Video Start -->
                        <div class="w-full xl:h-[300px] lg:h-[250px] md:h-[230px] h-[200px] flex justify-center items-center">
                            <div class="relative h-full w-full rounded-lg overflow-hidden">
                                <iframe src="https://www.youtube.com/embed/{{ basename($related->url) }}" frameborder="0" class="w-full h-full absolute top-0 bottom-0 left-0 right-0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </div>
                    <!-- Video End -->
                @endif
            <!-- Image Viedo or Quote End -->

            <a href="{{ route('blog.show', ['slug' => $related->slug]) }}" class="xl:text-lg lg:text-xl text-xl font-semibold ">{!! $related->makeExcerpt($related->title, 85) !!}</a>

            <!-- Author Start -->
                <div class="flex gap-3 items-center">
                    <div class="w-12 h-12 rounded-full">
                        <img src="{{ asset( $related->author->getImage()) }}" alt="{{ $related->author->name }}" class="rounded-full object-cover h-full w-full">
                    </div>
                    <ul class="flex flex-col gap-1">
                        <li class="text-base font-medium">{{ $related->author->name }}</li>
                        <div class="flex items-center text-sm text-neutral-500">
                            <li>{{ $related->publishedDate }}</li>
                            <li class="mx-2">|</li>
                            <li>{{ $related->viewed . ' ' . __('template.views') }}</li>
                        </div>
                    </ul>
                </div>
            <!-- Author End -->
        </div>
    <!-- Related Posts End -->
@endif