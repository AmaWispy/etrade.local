@foreach ($posts as $post)
    {{$post->link}}
    {{ $post->getThumb() }}
    {{$post->title}}
    {{$post->category->link}}
    {{ $post->category->name }}
    {{ $post->publishedDate }}
    {!! $post->excerpt !!}
    {{ __('template.read_more') }}
@endforeach