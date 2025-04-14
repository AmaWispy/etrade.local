<x-app-layout>

    @section('title', $page->title)

    <h1>{{$page->title}}</h1>
                   
    <ol class="breadcrumb breadcrumb-finance">
        <li><a href="/">{{ __('template.home') }}</a></li>
        <li class="active">{{$page->title}}</li>
    </ol>

    @foreach($projects as $project)
                        
        <a href="{{$project->link}}">
            <img src="{{ url('storage/'.$project->image) }}" alt="{{$project->title}}">
           {{$project->title}}
        </a>
                                
        {!! $project->excerpt !!}

        {{ __('template.learn_more') }}
                                   
    @endforeach
            
</x-app-layout>