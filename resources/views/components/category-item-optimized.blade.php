@props(['category', 'children'])

@php
    $hasChildren = !empty($children);
    $routeParams = $hasChildren ? ['category_parent' => $category->code] : ['category' => $category->code];
@endphp

<div class="category-item">
    <a href="{{ route('shop.home', $routeParams) }}" 
       class="{{ $hasChildren ? 'font-medium text-black' : 'text-neutral-400 hover:text-blue-500' }} transition-colors duration-200">
        {{ $category->name }}
    </a>
    @if($hasChildren)
        <ul class="pl-4 mt-2 space-y-2">
            @foreach($children as $child)
                <li class="text-neutral-400">
                    <a href="{{ route('shop.home', ['category' => $child['category']->code]) }}" 
                       class="hover:text-blue-500 transition-colors duration-200">
                        {{ $child['category']->name }}
                    </a>
                    @if(!empty($child['children']))
                        <ul class="pl-4 mt-2 space-y-2">
                            @foreach($child['children'] as $subChild)
                                <li class="text-neutral-400">
                                    <a href="{{ route('shop.home', ['category' => $subChild->code]) }}" 
                                       class="hover:text-blue-500 transition-colors duration-200">
                                        {{ $subChild->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
</div> 