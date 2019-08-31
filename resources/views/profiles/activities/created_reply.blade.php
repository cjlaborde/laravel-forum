@component('profiles.activities.activity')
    @slot('heading')
        {{ $profileUser->username }} replied to
        <a href="{{ $activity->subject->thread->path() }}">"{{ $activity->subject->thread->title }}"</a>
    @endslot

    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent

{{--@include (')--}}


{{--@include ("profiles.activities.{$activity->type}")--}}

{{--      # IF  REPLACED BY  VIEWS!                --}}
{{--                            @if ($activity->type == 'created_thread')--}}
{{--                                {{ $profileUser->name }} published a thread--}}
{{--                            @endif--}}

{{--                            @if ($activity->type == 'created_reply')--}}
{{--                                {{ $profileUser->name }} replied a thread--}}
{{--                            @endif--}}

{{--                            <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a> posted:--}}
{{--                            <a href="{{ $thread->path() }}">{{ $thread->title }}</a>--}}
