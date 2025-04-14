@if (isset($posts) && !empty($posts))
    @foreach($posts as $post) 
        <!-- Quote Template Start -->
            @if ($post['template'] === 'quote')
                <div class="bg-pink-50 rounded-xl overflow-hidden w-full xl:min-h-80  h-auto flex relative">
                    <div class="absolute left-0 h-full w-2 bg-pink-300"></div>
                    <div class="p-4">
                        @include('includes.blog.cards', ['post' => $post ])
                    </div>
                </div>
            @endif
        <!-- Quote Template End -->
            
        <!-- Image Template Start -->
            @if ($post['template'] === 'image')
                <div class="w-full xl:min-h-[600px] lg:h-[500px] md:h-[500px] h-auto flex flex-col justify-between gap-3">
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
                <div class="w-full h-auto flex flex-col justify-between gap-3">
                    <div class="relative 2xl:h-[550px] xl:h-[400px] lg:h-[500px] md:h-[380px] sm:h-[300px] w-full rounded-lg overflow-hidden">
                        <iframe src="https://www.youtube.com/embed/{{ basename($post->url) }}" frameborder="0" class="w-full h-full absolute top-0 bottom-0 left-0 right-0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    @include('includes.blog.cards', ['post' => $post ])
                </div>
            @endif
        <!-- Video Template End -->
        
        <!-- Carousel Template Start -->
            @if ($post['template'] === 'carousel')
                <div class="w-full h-auto flex flex-col justify-between gap-3">
                    <div class="relative slider-container rounded-lg overflow-hidden">
                        <button class="slick-prev absolute left-2 top-1/2 text-neutral-400 bg-white lg:w-16 lg:h-16 h-12 w-12 rounded-lg text-2xl -translate-y-1/2 z-10"><i class="bi bi-arrow-left"></i></button>
                        <div class="slider w-full 2xl:h-[580px] xl:h-[400px] lg:h-[480px] md:h-[360px] sm:h-[350px] overflow-hidden">
                            @foreach ($post->getImage('all') as $image)
                                <div class="2xl:h-[670px] xl:h-[400px] lg:h-[480px] md:h-[360px] sm:h-[350px] w-full">
                                    <img src="{{ asset($image->getUrl('')) }}" class="object-cover h-full w-full" alt="{{ $post->title }}">
                                </div>
                            @endforeach
                        </div>
                        <button class="slick-next absolute right-2 top-1/2 text-neutral-400 bg-white lg:w-16 lg:h-16 h-12 w-12 rounded-lg text-2xl -translate-y-1/2 z-10"><i class="bi bi-arrow-right"></i></button>
                    </div>
                    @include('includes.blog.cards', ['post' => $post ])
                </div>
            @endif
        <!-- Carousel Template End -->
    @endforeach
@endif