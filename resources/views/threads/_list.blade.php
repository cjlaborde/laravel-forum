@forelse ($threads as $thread)

        <div class="card mb-4">
            <div class="card-header bg-transparent">
                <div class="level">
                    <div class="flex">
                        <h4>
                            {{--                                        <a href="/threads/{{ $thread->id }}">--}}
                            <a href="{{ $thread->path() }}">

                                @if ($thread->pinned)
                                    <i class="fas fa-thumbtack"></i>
                                @endif

                                @if (auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                                    <strong>
                                        {{ $thread->title }}
                                    </strong>
                                @else
                                    {{ $thread->title }}
                                @endif
                            </a>
                        </h4>

                        <h5>
                            Posted By: <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a>
                        </h5>
                    </div>

                    <a href="{{ $thread->path() }}">
                        {{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}
                    </a>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="body">{!! $thread->body !!}</div>
            </div>

            <div class="card-footer bg-transparent">
{{--                {{ $thread->visits()->count() }} Visits  # Redis--}}
                {{ $thread->visits }} Visits
            </div>

        </div>

@empty
    <p>There are no revelant results at this time.</p>
@endforelse
