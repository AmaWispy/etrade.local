<x-app-layout>

    @section('title', __('template.search_results_query') . ': ' . $query)

    <!-- Section Header Title -->
    <section class="bg-grey padding-top-45 padding-bottom-45 clearfix">
        <div class="container">
            <div class="row">
                <div class="section-title">
                    <div class="col-md-12">
                        <h2>{{ __('template.search_results') }}</h2>
                    </div>
                </div>
            </div><!-- End Row -->
        </div><!-- End container -->
    </section><!--  End Section -->

    <!-- Section BreadCrumb -->
    <div class="no-padding border-bottom">
       <div class="container">
          <div class="row">
             <div class="col-md-12">
                 <ol class="breadcrumb breadcrumb-finance">
                    <li><a href="/"> <i class="fa fa-home" aria-hidden="true"></i> {{ __('template.home') }}</a></li>
                    <li class="active">{{ __('template.search_results_query') }}: "{{$query}}"</li>
                  </ol>
             </div><!--  End col -->
          </div> <!-- End Row -->
       </div><!-- End container -->
    </div><!--  End Section -->

    <div class="line"></div>

    <!--List Blog -->
    <div class="no-padding">
        <div class="container">
            <div class="row">
                <div id="primary" class="content-area col-md-9 no-padding-right">
                    <main id="main" class="site-main padding-top-50" >
                        @if(count($result) > 0)
                            @foreach($result as $post)
                            <article class="item-lastest-news itemBlogList clearfix">
                                <a href="{{$post->link}}" class="img-news-container ">
                                    <img src="{{ url('storage/'.$post->image) }}" class="img-responsive" alt="{{$post->title}}">
                                </a>
                                <div class="news-text-container">
                                    <a href="{{$post->link}}">
                                        <h3 class="title-news">{{ $post->title }}</h3>
                                    </a>
                                    <div class="latest-news-data">
                                        <!--span class="tags"><a href="blogDetail.html">Financial</a>, <a href="blogDetail.html"> Maketing</a></span-->
                                        <span class="dates">{{ $post->publishedDate }}</span>
                                    </div>
                                    {!! $post->excerpt !!}
                                    <a class="continueReading" href="{{$post->link}}">{{ __('template.continue_reading') }}</a>
                                </div> <!-- End Text box -->
                            </article><!-- End Arcicle -->
                            @endforeach   
                        </main> <!-- End Main -->
                        <div class="col-md-12 text-center clearfix">
                            {!! $result->links() !!}
                            <!--ul class="pagination pagination-finance">
                                <li><a class="current" href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">4</a></li>
                                <li><a href="#">5</a></li>
                                <li class="threedots">...</li>
                                    <li><a href="#">25</a></li>
                                <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
                            </ul-->
                        </div>
                    @else
                        <h3 class="title-news">{{ __('template.nothing_found') }}</h3>         
                    @endif
                </div>
                <div id="secondary" class="widget-area  col-md-3 padding-top-50" role="complementary">
                    <aside class="widget widget_search">
                        <form action="/search/posts" class="search-form" method="get" role="search">
                            <input name="sq" value="{{$query ?? ''}}" placeholder="{{ __('template.search') }} …" class="search-field" type="search">   
                            <button class="search-submit" type="submit"><i class="fa fa-search"></i></button>
                        </form>
                    </aside>
                    <aside id="categories-2" class="widget widget_categories">
                        <h3 class="widget-title" >{{ __('template.categories') }}</h3>

                        <ul>
                        @foreach($categories as $category)
                            <li><a href="{{$category->link}}">{{$category->name}}</a></li>
                            @endforeach
                        </ul> <!-- End Ul -->
                    </aside>
                </div>
            </div><!-- End Row -->
        </div><!-- End container -->
    </div><!--  End Section -->

</x-app-layout>