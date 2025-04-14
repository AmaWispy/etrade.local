<x-app-layout>

    @section('title', $page->title . ': ' . $project->title)

    <h1>{{$page->title}}</h1>

    <ul class="breadcrumb breadcrumb-finance">
        <li><a href="/">{{ __('template.home') }}</a></li>
        <li><a href="{{$page->link}}">{{ $page->title }}</a></li>
        <li class="active">{{ $project->title }}</li>
    </ul>
             
    <h1>{{$project->title}}</h1>

    {{ $project->publishedDate }}

    <img src="{{ url('storage/'.$project->image) }}" alt="{{$project->title}}">

    {!! $project->content !!}
                       
    @foreach($projects as $project)
        <a href="{{$project->link}}">{{$project->title}}</a>
    @endforeach        

</x-app-layout>