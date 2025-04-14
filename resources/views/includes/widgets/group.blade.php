{{$title}}
@foreach ($widgets as $widget)
    {{$widget->title_top}}
    {{$widget->title}}
    {{$widget->title_bottom}}
    {!! $widget->content !!}
@endforeach