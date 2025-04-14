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
                    <div class="flex lg:flex-row flex-col gap-3 items-center lg:w-auto w-full">
                        @if ($postLatest->getImage() !== null || $postLatest->template === 'video')
                            <div class="xl:w-32 xl:h-20 lg:w-40 lg:h-24 md:h-auto  w-full rounded-lg overflow-hidden">
                                <a href="{{ route('blog.show', ['slug' => $postLatest->slug]) }}" class="h-full w-full overflow-hidden">
                                    <img 
                                    src="{{ $postLatest->template === 'video' 
                                        ? 'https://img.youtube.com/vi/' . (isset(explode('/', parse_url($postLatest->url, PHP_URL_PATH))[2]) 
                                        ? explode('/', parse_url($postLatest->url, PHP_URL_PATH))[2] 
                                        : '') . '/hqdefault.jpg' 
                                        : $postLatest->getImage() }}"
                                    class="object-cover h-full w-full rounded-lg overflow-hidden" 
                                    alt="{{ $postLatest->name }}">
                                </a>
                            </div>
                        @endif

                        <ul class="xl:w-2/3 lg:w-3/4 w-full">
                            <li class="text-base w-full">
                                <a class="xl:w-60 2xl:w-72 lg:w-[68vw] md:w-[85vw] sm:w-[80vw] truncate block" href="{{ route('blog.show', ['slug' => $postLatest->slug]) }}">{{ $postLatest->title }}</a>
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
        <div class="border w-full h-[380px] rounded-lg">

        </div>
    <!-- Recend Viewed Products End -->

    <!-- Search Start -->
        <div class="border w-full h-[125px] rounded-lg p-3 flex flex-col gap-3">
            <h1 class="xl:text-lg text-xl font-medium">{{ __('template.search') }}</h1>
            <form class="w-full h-fit">   
                <div class="relative w-full h-full">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none w-full h-full">
                        <i class="bi bi-search "></i>
                    </div>
                    <input type="search" id="default-search" class="block w-full h-full  ps-10 xl:py-0 py-3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-white focus:ring-blue-400 focus:border-blue-400" placeholder="{{ __('template.what_are_you_looking_for') }}" required />
                </div>
            </form>
        </div>
    <!-- Search End -->

    <!-- Archies Start -->
        @if (isset($archives) && $archives !== null)
            <div class="border w-full flex-col flex gap-3 p-3 h-fit rounded-lg">
                <h1 class="xl:text-lg text-xl font-medium">{{ __('template.archives') }}</h1>
                <div>
                    <select name="archives" id="archives" class="nice-select w-full">
                        <option value="NONE" selected>{{ __('template.select_month') }}</option>
                        @foreach ($archives as $key => $qnty)
                            <option value="{{ $key }}">{{ $key . ' (' . $qnty . ') '}}</option>
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
                        <label 
                            x-data="{ check: false }"
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
        // Обработка изменения архива
        $('#archives').on('change', function () {
            var selectedArchive = $(this).val(); // Получаем выбранное значение

            if (selectedArchive !== "NONE") {
                var currentUrl = new URL(window.location.href);
                currentUrl.searchParams.delete('archives'); // Удаляем старый параметр
                currentUrl.searchParams.set('archives', selectedArchive); // Добавляем новый параметр

                history.pushState({}, '', currentUrl.toString()); // Изменяем URL без перезагрузки
            }
        });

        // Обработка клика по тегу
        let timeOut;

        $('input[type="checkbox"]').on('click', function () {
            clearTimeout(timeOut); 

            timeOut = setTimeout(() => {
                let activeTags = [];
                // Собираем все теги с синим фоном
                $('label.bg-blue-500').each(function () {
                    let tag = $(this).find('span').text().trim();
                    activeTags.push(tag);
                });

                // Формируем новый URL с параметрами `tags`
                let url = new URL(window.location.href);
                url.searchParams.delete('tags'); // Удаляем старые теги

                activeTags.forEach(tag => url.searchParams.append('tags', tag)); // Добавляем новые теги

                // Обновляем URL без перезагрузки
                history.pushState({}, '', url.toString());

            }, 1000);
        });
    });
</script>