<x-app-layout>

    @section('title', __('template.blog') . ' - ' . $category->name)

    @section('meta')
        <meta name="description" content="{{strip_tags($category->description)}}">
        <!-- Facebook Open Graph Meta Tags -->
        <meta property="og:type" content="website">
        <meta property="og:title" content="{{$category->name}}">
        <meta property="og:description" content="{{strip_tags($category->description)}}">
        <meta property="og:url" content="{{config('app.url')}}{{$category->link}}">
    @endsection

    @section('microdata')
    <!-- Schema.org Microdata -->
    <script type="application/ld+json">
        {
            "@context": "http://schema.org",
            "@type": "Blog",
            "name": "{{$category->name}}",
            "description": "{{strip_tags($category->description)}}",
            "blogPosts": [
                @foreach($posts as $post)
                {
                    "@type": "BlogPosting",
                    "headline": "{{$post->title}}",
                    "description": "{{strip_tags($post->makeExcerpt($post->content, 150))}}",
                    "datePublished": "{{\Carbon\Carbon::parse($post->published_at)->format('Y-m-d\TH:i:sO')}}",
                    "dateModified": "{{\Carbon\Carbon::parse($post->updated_at)->format('Y-m-d\TH:i:sO')}}"
                }@if(!$loop->last),@endif
                @endforeach
            ]
        }
    </script>
    @endsection

    
    <h1>{{ $category->name }}</h1>
    <ul>
        <li><a href="{{route('blog.home')}}">{{__('template.blog')}}</a></li>
        <li>{{ $category->name }}</li>
    </ul>
                
    <form action="/search/posts" class="search-form" method="get" role="search">
                                    <input name="sq" value="" placeholder="{{ __('template.search') }} ..." class="search-field" type="search">   
                                    <button class="search-submit" type="submit"><i class="fa fa-search"></i></button>
    </form>
    <h3>{{ __('template.categories') }}</h3>
    <ul>
        @foreach($categories as $category)
            <li><a href="{{$category->link}}">{{$category->name}}</a></li>
        @endforeach
    </ul>
                           
    @foreach($posts as $post)         
        <a href="{{$post->link}}">
            <img src="{{ $post->getImage() }}" alt="{{$post->title}}" />
        </a>

        <ul>
            <li><a href="{{$post->category->link}}">{{$post->category->name}}</a></li>
            <li>{{ $post->publishedDate }}</li>
        </ul>
        <h3><a href="{{$post->link}}">{{$post->title}}</a></h3>
        {!! $post->makeExcerpt($post->content, 300) !!}
        <a href="{{$post->link}}">
            {{ __('template.continue_reading') }}
        </a>                   
    @endforeach

</x-app-layout>