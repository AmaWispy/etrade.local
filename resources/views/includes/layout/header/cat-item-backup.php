<nav class="2xl:flex hidden department-nav-menu">
                                    <ul class="nav-menu-list">
                                        @foreach($parentCategories as $category)
                                            @if($category->name == null)
                                                @continue
                                            @endif
                                            @php
                                                $categoryChildren = $childCategories->where('parent_code', $category->code);
                                                $hasChildren = $categoryChildren->count() > 0;
                                            @endphp
                                            <li>
                                                <a href="{{ route('shop.home', ['category' => $category->code]) }}" class="nav-link {{ $hasChildren ? 'has-megamenu' : '' }}">
                                                    <span class="menu-icon">
                                                        <i class="bi bi-box text-blue-400"></i>
                                                    </span>
                                                    <span class="menu-text">{{ $category->name }}</span>
                                                </a>
                                                @if($hasChildren)
                                                    <div class="department-megamenu">
                                                        <div class="department-megamenu-wrap">
                                                            <div class="department-submenu-wrap max-h-[70vh] overflow-y-auto scrollbar scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                                                                
                                                            @php
                                                                $chunks = $categoryChildren->chunk(ceil($categoryChildren->count() / 3));
                                                            @endphp
                                                            @foreach($chunks as $chunk)
                                                            <div class="department-submenu">
                                                                    
                                                                    <div class="submenu-column">
                                                                        <ul class="space-y-2">
                                                                            @foreach($chunk as $child)
                                                                                <x-category-item :category="$child" :allCategories="$childCategories" />
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>
                                                                
                                                            </div>
                                                            @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </nav>