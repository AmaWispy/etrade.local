<x-app-layout>

    @section('title', $page->title . ': ' . $service->title)

    <h1>{{$page->title}}</h1>
                   
    <ol>
        <li><a href="/">{{ __('template.home') }}</a></li>
        <li><a href="{{$page->link}}">{{ $page->title }}</a></li>
        <li class="active">{{ $service->title }}</li>
    </ol>
            
    <h1>{{$service->title}}</h1>
                        
    {{ $service->publishedDate }}

    <img src="{{ url('storage/'.$service->image) }}" alt="{{$service->title}}">

    {!! $service->content !!}       
                
    @foreach($services as $service)
        <a href="{{$service->link}}">{{$service->title}}</a>
    @endforeach
                        
</x-app-layout>