<x-app-layout>

    @section('title', $category->localized_name ?? __('template.shop'))

    <!-- BREADCRUMB AREA START -->
        @include('includes.layout.bread-crump', ['title' => __('template.shop'), 'category' => $category])
    <!-- BREADCRUMB AREA END -->

    <!-- PRODUCT DETAILS AREA START -->
        <div class="flex flex-col gap-5 items-center xl:container xl:!mx-auto mx-2">
            <!-- Products and Filters Start -->
                <div class="flex w-full gap-10">
                    <!-- Filter Block Start -->
                        <div class="2xl:w-[20%] xl:w-[25%] xl:block hidden">
                            @include('includes.products.filter.layout', ['filterType' => 'desktop-filters'])
                        </div>
                    <!-- Filter Block End -->

                    <!-- Products Block Start -->
                        <div class="flex flex-col gap-5 z-0 w-full">
                            <div class="flex xl:items-center gap-4 justify-end xl:flex-row flex-col w-full xl:w-auto">
                                @include('includes.results', [
                                    'on_page' => $products->count(),
                                    'total' => $products->total()
                                ])

                                @include('includes.products.filter.sort.default', [
                                    'sorting' => $sorting
                                ])
                                
                                <div class="xl:hidden block mx-4">
                                    <button                                 
                                        type="button" 
                                        id="menu" 
                                        data-drawer-target="filter_menu" 
                                        data-drawer-show="filter_menu" 
                                        data-drawer-placement="left" 
                                        aria-controls="filter_menu"
                                        class="duration-500 text-center text-base font-semibold xl:hover:bg-florarColor xl:hover:text-white rounded-full flex items-center justify-center p-2 w-9 h-9"
                                        >
                                        <i class="bi bi-funnel"></i>{{ __('template.filter') }}
                                    </button>
                                </div>
                            </div>
                            @include('includes.products.grid.default', [
                                'product' => $products    
                            ])
                        </div>
                    <!-- Products Block End -->
                </div>
            <!-- Products and Filters Ent -->

            <!-- Products Pages Start -->
                @if($products->hasPages() > 0)
                    <div class="inline lg:hidden">
                        {!! $products->appends(request()->query())->links('pagination.default') !!}
                    </div>
                    <div class="hidden lg:inline xl:hidden">
                        {!! $products->onEachSide(4)->appends(request()->query())->links('pagination.default') !!}
                    </div>
                    <div class="hidden xl:inline">
                        {!! $products->onEachSide(5)->appends(request()->query())->links('pagination.default') !!}
                    </div>
                @endif
            <!-- Products Pages End -->
        </div>
    <!-- PRODUCT DETAILS AREA START -->

    <!-- Menu Start -->
        <div id="filter_menu" class="fixed top-0 left-0 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-white lg:w-[500px] w-72 z-[9999]" tabindex="-1" aria-labelledby="drawer-right-label">
            <div class="flex justify-between items-center py-1.5 mb-3">
                <button x-on:click='body = !body' type="button" data-drawer-hide="filter_menu" aria-controls="filter_menu" class="bg-gray-200 rounded-full h-7 w-7 p-1.5 inline-flex items-center justify-center" >
                    <i class="bi bi-x"></i>
                </button>
            </div>
            
            <div class="flex flex-col gap-9 mt-5">
                @include('includes.products.filter.layout', ['filterType' => 'mobile-filters'])
                <!-- <button class="w-full font-semibold text-base h-10 rounded-lg text-white bg-blue-500">{{ __('template.view_all') }}</button> -->
            </div>
        </div>
    <!-- Menu End -->

    <script type="module">
    $(document).ready(function(){
        $('.range-price-container').each(function() {
            const container = $(this);
            const slider = container.find('.slider-range');
            const minInput = container.find('.price-min');
            const maxInput = container.find('.price-max');
            
            slider.slider({
                range: true,
                min: parseFloat("{{ $minPrice }}"),
                max: parseFloat("{{ $maxPrice }}"),
                values: [ parseFloat("{{ $minPriceChanged }}"), parseFloat("{{ $maxPriceChanged }}") ],
                slide: function(event, ui) {
                    minInput.val(ui.values[0]);
                    maxInput.val(ui.values[1]);
                    
                    // Используем объект URL для работы с параметрами
                    var url = new URL(window.location.href);
                    
                    // Устанавливаем новые значения min и max
                    url.searchParams.set('min', ui.values[0]);
                    url.searchParams.set('max', ui.values[1]);
                    
                    // Сбрасываем пагинацию на первую страницу при изменении фильтра
                    url.searchParams.delete('page');
                    
                    // Перенаправляем на новый URL
                    window.location.href = url.toString();
                }
            });
            console.log(slider);
            
            // Устанавливаем начальные значения в поля ввода
            minInput.val(slider.slider("values", 0));
            maxInput.val(slider.slider("values", 1));

            slider.on('touchstart touchmove', function(e) {
                e.stopPropagation();
            });
        });
    });
</script>

<script type="module">
    document.addEventListener('DOMContentLoaded', function() {
        // Найдем чекбоксы в обоих контейнерах
        const desktopCheckboxes = document.querySelectorAll('.desktop-filters .brand-checkbox');
        const mobileCheckboxes = document.querySelectorAll('.mobile-filters .brand-checkbox');
        
        /* console.log('Десктопные чекбоксы:', desktopCheckboxes.length);
        console.log('Мобильные чекбоксы:', mobileCheckboxes.length); */
        
        // 1. Синхронизируем чекбоксы между мобильной и десктопной версиями
        function syncCheckboxes(changedCheckbox, isDesktop) {
            const value = changedCheckbox.value;
            const isChecked = changedCheckbox.checked;
            
            // Если изменен десктопный, обновляем мобильный и наоборот
            if (isDesktop) {
                mobileCheckboxes.forEach(cb => {
                    if (cb.value === value) {
                        cb.checked = isChecked;
                    }
                });
            } else {
                desktopCheckboxes.forEach(cb => {
                    if (cb.value === value) {
                        cb.checked = isChecked;
                    }
                });
            }
        }
        
        // 2. Единая функция для применения фильтра
        function applyBrandFilter() {
            const url = new URL(window.location.href);
            url.searchParams.delete('brand');
            url.searchParams.delete('brand[]');
            
            // Выбираем набор чекбоксов, у которого есть элементы
            const checkboxes = desktopCheckboxes.length > 0 ? desktopCheckboxes : mobileCheckboxes;
            
            // Преобразуем NodeList в массив и затем применяем filter
            const selectedBrands = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
            
            if (selectedBrands.length > 0) {
                url.searchParams.set('brand', selectedBrands.join(','));
            }
            
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }
        
        // 3. Добавляем обработчики событий
        desktopCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                syncCheckboxes(this, true);
                applyBrandFilter();
            });
        });
        
        mobileCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                syncCheckboxes(this, false);
                applyBrandFilter();
            });
        });
    });
</script>
</x-app-layout>