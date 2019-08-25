@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-md-8 ">
                    @include('threads._list')
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header bg-transparent">
                        Search
                    </div>

                    <div class="card-body">
                        <form method="GET" action="/threads/search">
                            <div class="form-group">
                                <input type="text" placeholder="Search for something..." name="q" class="form-control">
                            </div>

                            <div class="form-group">
                                <button class="btn btn-primary" type="submit">Search</button>
                            </div>
                        </form>
                    </div>
                </div>

                @if (count($trending))
                <div class="card mb-4">
                    <div class="card-header bg-transparent">
                        Trending Threads
                    </div>

                    <div class="card-body">
                        <ul class="list-group">
                            @foreach ($trending as $thread)
                                <li class="list-group-item">
                                    <a href="{{ url($thread->path) }}">
                                        {{ $thread->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
            </div>


            <div class="row ml-4 mt-5">
                {{ $threads->render() }}
            </div>

        </div>
    </div>


@endsection
