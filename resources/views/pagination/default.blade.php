@if ($paginator->hasPages())
    <div class="flex justify-center mt-8">
        <nav aria-label="Pagination">
            <ul class="flex items-center lg:space-x-2 md:text-base text-sm">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li>
                        <span class="md:px-3 px-2.5 py-2 text-gray-500 cursor-not-allowed" aria-disabled="true">
                            <i class="icon-arrow-left"></i>
                        </span>
                    </li>
                @else
                    <li>
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="md:px-3 px-2.5 py-2 text-blue-600 hover:text-blue-800 rounded-lg">
                            <i class="icon-arrow-left"></i>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li>
                            <span class="md:px-3 px-2.5 py-2 text-gray-500" aria-disabled="true">{{ $element }}</span>
                        </li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li>
                                    <span class="md:px-3 px-2.5 py-2 bg-blue-600 text-white rounded-lg">{{ $page }}</span>
                                </li>
                            @else
                                <li>
                                    <a href="{{ $url }}" class="md:px-3 px-2.5 py-2 text-blue-600 hover:text-blue-800 rounded-lg">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li>
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="md:px-3 px-2.5 py-2 text-blue-600 hover:text-blue-800 rounded-lg">
                            <i class="icon-arrow-right"></i>
                        </a>
                    </li>
                @else
                    <li>
                        <span class="md:px-3 px-2.5 py-2 text-gray-500 cursor-not-allowed" aria-disabled="true">
                            <i class="icon-arrow-right"></i>
                        </span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@endif
