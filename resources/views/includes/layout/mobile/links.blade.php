<!-- Mobile menu Links Start-->
    <ul>
        <!-- Useful Pages Links Start-->
            <div>
                <h1 class="font-medium text-2xl py-2">{{ __('template.useful_pages') }}</h1>
                <ul class="sm:text-sm lg:text-lg">
                    @foreach(\App\Models\Navigation\Item::where('menu_id', 3)->get() as $link)
                        @if ($link->is_active === 1 )
                            <li class="!mt-1">
                                <a href="{{ (App\Models\Page\Page::find($link->entity_id))->link }}">{{ $link->label}}</a>
                            </li>       
                        @endif
                    @endforeach  
                </ul>
            </div>
        <!-- Useful Pages Links Start-->

        <div class="border border-neutral-200 my-10"></div>

        <!-- Categorires Pages Links Start-->
            <div>
                <h1 class="font-medium text-2xl py-2">{{ __('template.categories') }}</h1>
                <ul class="sub-menu sm:text-sm lg:text-lg" style="display: block;">
                    @foreach(\App\Models\Shop\Category::getTree() as $category)
                        <li class="!mt-1">
                            <a href="{{$category['link']}}">
                                {{$category['name']}}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        <!-- Categorires Pages Links End-->
    </ul>
<!-- Mobile menu Links End-->