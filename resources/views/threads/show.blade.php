@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="/css/vendor/jquery.atwho.css">
@endsection

@section('content')
    <thread-view :thread="{{ $thread }}" inline-template>
    <div class="container">
        <div class="row">
            <div class="col-md-8" v-cloak>
                @include('threads._question')

{{--                # when you remove reply the replies Count should go down--}}
{{--                <replies :data="{{ $thread->replies }}" @remove="repliesCount--" can-update="{{ Auth::user()->can('update', $thread) }}"></replies>--}}
                <replies @added="repliesCount++" @removed="repliesCount--"></replies>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <p>
                            This thread was published {{ $thread->created_at->diffForHumans() }} by
                            <a href="#">{{ $thread->creator->name }}</a> and currently
                            has <span v-text="repliesCount">{{ $thread->replies_count }}</span> {{ str_plural('comment', $thread->replies_count) }}
                        </p>

                        <p>
                            <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}" v-if="signedIn"></subscribe-button>

                            <button class="btn"
                                    :class="locked ? 'btn-danger' : 'btn-outline-danger'"
                                    v-if="authorize('isAdmin')"
                                    @click="toogleLock"
                                    v-text="locked ? 'Unlock' : 'Lock'">
                                Lock
                            </button>
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    </thread-view>

@endsection
