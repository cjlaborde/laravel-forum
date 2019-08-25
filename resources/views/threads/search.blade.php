@extends('layouts.app')

@section('content')
    <div class="container">
            <ais-index
                    app-id="{{ config('scout.algolia.id') }}"
                    api-key="{{ config('scout.algolia.key') }}"
                    index-name="threads"
                    query="{{ request('q') }}"
            >
        <div class="row">
                <div class="col-md-8">
                    <ais-results>
                        <template scope="{ result }">
                            <li>
                                <a :href="result.path">
                                    <ais-highlight :result="result" attribute-name="title"></ais-highlight>
                                </a>
                            </li>
                        </template>
                    </ais-results>
                </div>

                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header bg-transparent">
                            Search
                        </div>
                        <div class="card-body">
                            <ais-search-box placeholder="Find thread..." :autofocus="true"></ais-search-box>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-transparent">
                            Filter By Channel
                        </div>
                        <div class="card-body">
                            <ais-refinement-list attribute-name="channel.name"></ais-refinement-list>
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
            </ais-index>
        </div>
    </div>

@endsection
