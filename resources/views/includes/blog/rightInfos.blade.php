<style>
    .list {
        width: 100%;
        -webkit-box-shadow: -10px 4px 52px 0px rgba(34, 60, 80, 0.2) !important;
        -moz-box-shadow: -10px 4px 52px 0px rgba(34, 60, 80, 0.2) !important;
        box-shadow: -10px 4px 52px 0px rgba(34, 60, 80, 0.2) !important;
    }
</style>
<div class="w-full flex flex-col gap-5">
    <!-- Latest Posts Start -->
        <div class="border p-3 flex flex-col gap-4 items-start font-medium xl:text-lg text-xl w-full h-fit rounded-lg">
            <h1>{{ __('template.latest_posts') }}</h1>
            <div class="flex flex-col xl:gap-3 gap-4 w-full">
                @foreach ($latestPostsBlog as $postLatest )
                    <div class="flex group lg:flex-row flex-col gap-3 items-center lg:w-auto w-full">
                        @if ($postLatest->getImage() !== null || $postLatest->template === 'video')
                            <div class="xl:w-32 xl:h-20 lg:w-40 lg:h-24 md:h-auto  w-full rounded-lg overflow-hidden">
                                <a href="{{ route('blog.show', ['slug' => $postLatest->slug]) }}" class="h-full w-full overflow-hidden">
                                    <img 
                                    src="{{ $postLatest->template === 'video' 
                                        ? 'https://img.youtube.com/vi/' . (isset(explode('/', parse_url($postLatest->url, PHP_URL_PATH))[2]) 
                                        ? explode('/', parse_url($postLatest->url, PHP_URL_PATH))[2] 
                                        : '') . '/hqdefault.jpg' 
                                        : $postLatest->getImage() }}"
                                    class="object-cover h-full duration-300 group-hover:transform group-hover:scale-110 w-full rounded-lg overflow-hidden" 
                                    alt="{{ $postLatest->name }}">
                                </a>
                            </div>
                        @endif

                        <ul class="xl:w-2/3 lg:w-3/4 w-full">
                            <li class="text-base w-full">
                                <a class="xl:w-60 2xl:w-72 lg:w-[68vw] md:w-[85vw] sm:w-[80vw] truncate block duration-300 hover:text-blue-500" href="{{ route('blog.show', ['slug' => $postLatest->slug]) }}">{{ $postLatest->title }}</a>
                            </li>
                            <li class="text-sm font-normal text-neutral-500">
                                <p>{{ $postLatest->publishedDate . ' | ' . $postLatest->viewed . ' ' . __('template.views') }}</p>
                            </li>
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    <!-- Latest Posts End -->

    <!-- Recend Viewed Products Start -->
        <div class="border p-3 flex-col gap-4 items-start font-medium xl:text-lg text-xl w-full h-fit rounded-lg hidden" id="recend-viwed-block">
            <h1>{{ __('template.recent_viewed_products') }}</h1>
            <div class="flex flex-col xl:gap-3 gap-4 w-full" id="recend-viwed-products"></div>
        </div>
    <!-- Recend Viewed Products End -->

    <!-- Search Start -->
        <div class="border w-full h-[125px] rounded-lg p-3 flex flex-col gap-3">
            <h1 class="xl:text-lg text-xl font-medium">{{ __('template.search') }}</h1>
            <form class="w-full h-fit" id="search-blogs-form">   
                <div class="relative w-full h-full">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none w-full h-full">
                        <i class="bi bi-search "></i>
                    </div>
                    <input type="text" 
                        name="blogs-search" 
                        id="blogs-search" 
                        class="block w-full h-full  ps-10 xl:py-0 py-3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-white focus:ring-blue-400 focus:border-blue-400" 
                        placeholder="{{ __('template.what_are_you_looking_for') }}" 
                        value="{{ request('search') }}"/>
                </div>
            </form>
        </div>
    <!-- Search End -->

    <!-- Archies Start -->
        @if (isset($archives) && $archives !== null)
            <div class="border w-full flex-col flex gap-3 p-3 h-fit rounded-lg z-0">
                <h1 class="xl:text-lg text-xl font-medium">{{ __('template.archives') }}</h1>
                <div>
                    <select name="archives" id="archives" class="nice-select w-full">
                        <option value="NONE" selected>{{ __('template.select_month') }}</option>
                        @foreach ($archives as $key => $qnty)
                            <option value="{{ $key }}" @if($key === request('archives')) selected @endif>{{ $key . ' (' . $qnty . ') '}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif
    <!-- Archies End -->

    <!-- Tags Start -->
        <div class="border w-full flex-col flex gap-3 p-3 h-fit rounded-lg">
            <h1 class="xl:text-lg text-xl font-medium">{{ __('template.tags') }}</h1>
            <div class="flex flex-wrap gap-2">
                @foreach ($tags as $tag)
                    @if (Route::currentRouteName() !== 'blog.show')
                        @php
                            $isChecked = in_array($tag, explode(',', request('tags')));
                        @endphp
                        <label 
                            x-data="{ check: {{ $isChecked ? 'true' : 'false' }} }"
                            x-bind:class="check ? 'bg-blue-500 text-white border-blue-200' : 'bg-white text-neutral-500 border-neutral-200'"
                            class="border-2 rounded-2xl w-fit px-[17px] py-2 flex items-center gap-2 xl:hover:text-white xl:hover:!bg-blue-500 xl:hover:border-blue-500 cursor-pointer">
                            <input type="checkbox" x-model="check" class="hidden">
                            <span>{{ $tag }}</span>
                        </label>
                    @else
                        <div class="border-2 rounded-2xl border-neutral-200 text-neutral-500 w-fit px-[17px] lg:text-base py-2">
                            <h1>{{ $tag }}</h1>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    <!-- Tags End -->
</div>

<script type="module">
    $(document).ready(function () {
        /*
        * Load Viwed Products
        */
        let data = JSON.parse(localStorage.getItem('viewed_items'));
        
        if(data !== null || @json(auth()->check())){
            $.ajax({
                url: `/viewed-items`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                data: {
                    'viewed': data
                },
        
                success: function (response) {
                    $('#recend-viwed-block').removeClass('hidden').addClass('flex');

                    if(response.status === 200){
                        console.log(data)
                        console.log(response)
    
                        if(response.is_auth === true){
                            localStorage.removeItem('viewed_items');
                        }
    
                        response.right_layout_formated_viwed_products.sort((b, a) => new Date(a.date) - new Date(b.date)).slice(0,3).forEach(el => {
                            $('#recend-viwed-products').append(`
                            <div class="flex group h-fit lg:flex-row flex-col gap-3 items-center lg:w-auto w-full">
                                <div class="xl:w-32 xl:h-20 lg:w-40 lg:h-24 md:h-auto  w-full rounded-lg overflow-hidden">
                                    <a href="${ el.link }" class="h-full w-full overflow-hidden">
                                        <img src="${ el.image }"
                                        class="object-cover h-full duration-300 group-hover:transform group-hover:scale-110 w-full rounded-lg overflow-hidden" 
                                        alt="">
                                    </a>
                                </div>

                                <ul class="xl:w-2/3 lg:w-3/4 w-full mt-2">
                                    <li class="text-xl w-full">
                                        <a href="${ el.link }" class="xl:w-60 2xl:w-72 lg:w-[68vw] md:w-[85vw] sm:w-[80vw] truncate block duration-300 hover:text-blue-500" href="">${ el.name }</a>
                                    </li>
                                    <li class="text-lg font-medium flex items-center gap-3">
                                        <del class="text-base text-neutral-300">${ el.on_sale ? el.price_default : '' }</del>
                                        <h1>${ el.on_sale ? el.price_on_sale : el.price_default}</h1>
                                    </li>
                                </ul>
                            </div>`)
                        });

                    }
                },
        
                error:function(xhr, status, error) {
                    console.error('Error:', xhr, status, error);
                }
            })
        } else {
            $('#recend-viwed-block').removeClass('flex').addClass('hidden');
        }

        let timeOut;
        const skeleton = `
            <div role="status" class="w-full p-4 border border-gray-200 rounded-sm shadow-sm animate-pulse md:p-6 dark:border-gray-700">
                <div class="flex items-center justify-center h-48 mb-4 bg-gray-300 rounded-sm dark:bg-gray-700">
                    <svg class="w-10 h-10 text-gray-200 dark:text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 20">
                        <path d="M14.066 0H7v5a2 2 0 0 1-2 2H0v11a1.97 1.97 0 0 0 1.934 2h12.132A1.97 1.97 0 0 0 16 18V2a1.97 1.97 0 0 0-1.934-2ZM10.5 6a1.5 1.5 0 1 1 0 2.999A1.5 1.5 0 0 1 10.5 6Zm2.221 10.515a1 1 0 0 1-.858.485h-8a1 1 0 0 1-.9-1.43L5.6 10.039a.978.978 0 0 1 .936-.57 1 1 0 0 1 .9.632l1.181 2.981.541-1a.945.945 0 0 1 .883-.522 1 1 0 0 1 .879.529l1.832 3.438a1 1 0 0 1-.031.988Z"/>
                        <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.98 2.98 0 0 0 .13 5H5Z"/>
                    </svg>
                </div>
                <div class="h-2.5 bg-gray-200 rounded-full dark:bg-gray-700 w-48 mb-4"></div>
                <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 mb-2.5"></div>
                <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 mb-2.5"></div>
                <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700"></div>
                <div class="flex items-center mt-4">
                <svg class="w-10 h-10 me-3 text-gray-200 dark:text-gray-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                    </svg>
                    <div>
                        <div class="h-2.5 bg-gray-200 rounded-full dark:bg-gray-700 w-32 mb-2"></div>
                        <div class="w-48 h-2 bg-gray-200 rounded-full dark:bg-gray-700"></div>
                    </div>
                </div>
                <span class="sr-only">Loading...</span>
            </div>
        `;
        
        /**
        * Loaded blogs with filters 
        */
        getBlogData();


        /**
         * Archives filters
        */
        $('#archives').on('change', function () {
            clearTimeout(timeOut); 
            let selectedArchive = $(this).val(); // Получаем выбранное значение
            console.log(selectedArchive)
            timeOut = setTimeout(() => {
                singleParamsUrl(selectedArchive, 'archives')
                
                // Get Data
                getBlogData();
            })

        });

        
        /**
         * Search filters
        */
        $('#search-blogs-form').submit(function(e){
            e.preventDefault()
            
            let dataSearch = $(this).find('input[name="blogs-search"]').val();

            timeOut = setTimeout(() => {
                singleParamsUrl(dataSearch, 'search', true)
                
                // Get Data
                getBlogData();
            })
            console.log(dataSearch)
        })

        /**
         * Tags filters
        */
        $('input[type="checkbox"]').on('click', function () {
            clearTimeout(timeOut); 

            timeOut = setTimeout(() => {
                let activeTags = [];
                // Собираем все теги с синим фоном
                $('label.bg-blue-500').each(function () {
                    let tag = $(this).find('span').text().trim();
                    activeTags.push(tag);
                });

                // Создаем URL
                let url = new URL(window.location.href);

                // Удаляем старый параметр `tags`
                url.searchParams.delete('tags');

                // Добавляем новый параметр, объединяя теги через запятую
                if (activeTags.length > 0) {
                    url.searchParams.set('tags', activeTags.join(',')); 
                }
                history.pushState({}, '', url.toString());

                // Get Data
                getBlogData();
            }, 1000);
        });

        /**
         * Helpers
        */
        function getBlogData(){
            let url = new URL(window.location.href);

            $.ajax({
                url: `/blog/filter-comment?` + url.searchParams.toString(),
                method: 'GET',
                beforeSend: function () {
                    $('#blogs-posts').html(''); 
                    for (let i = 0; i < 3; i++) {
                        $('#blogs-posts').append(skeleton); 
                        
                    }
                    $('#blogs-posts').append();
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if(response.status === 200){
                        console.log(response)
                        $('#blogs-posts').html(response.html); 
                    }

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

                },

                error:function(xhr, status, error) {
                    console.error('Error:', xhr, status, error);
                }
            })
        }

        
        function singleParamsUrl(value, nameParams, is_search = false){
            var currentUrl = new URL(window.location.href);
                
            if(is_search){
                ['tags', 'archives'].forEach(urlParams => {
                    currentUrl.searchParams.delete(urlParams)
                });
            }

            if (value !== "NONE" && value !== '' && value !== null  ) {
                currentUrl.searchParams.delete(nameParams); 
                currentUrl.searchParams.set(nameParams, value); 

            } else {
                currentUrl.searchParams.delete(nameParams);
            }
            
            history.pushState({}, '', currentUrl.toString());

            
        }
    });
</script>