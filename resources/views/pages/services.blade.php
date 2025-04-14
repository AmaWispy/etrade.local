<x-app-layout>

    @section('title', $page->title)

    <h2>{{$page->title}}</h2>
    <ol>
        <li><a href="/">{{ __('template.home') }}</a></li>
        <li class="active">{{$page->title}}</li>
    </ol>

    @foreach($services as $service)
        <a href="{{$service->link}}">
            <img src="{{ url('storage/'.$service->image) }}" alt="{{$service->title}}">
            {{$service->title}}
        </a>
        {!! $service->excerpt !!}
        <a href="{{$service->link}}">
            {{ __('template.learn_more') }}
        </a>
    @endforeach
</x-app-layout>