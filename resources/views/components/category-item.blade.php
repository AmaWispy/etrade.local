@props(['category', 'allCategories'])

@php
    $children = $allCategories->where('parent_code', $category->code);
    $hasChildren = $children->count() > 0;
    $routeParams = $hasChildren ? ['category_parent' => $category->code] : ['category' => $category->code];
@endphp

<li class="text-neutral-400">
    <a href="{{ route('shop.home', $routeParams) }}" 
       class="{{ $hasChildren ? 'color-black' : 'hover:text-blue-500' }} transition-colors duration-200 hover-color-blue">
        {{ $category->name }}
    </a>
    @if($hasChildren)
        <ul class="pl-4 mt-2 space-y-2">
            @foreach($children as $child)
                <x-category-item :category="$child" :allCategories="$allCategories" />
            @endforeach
        </ul>
    @endif
</li> 