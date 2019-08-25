


REPLACED BY Reply.vue
BladeConverted To vue Lesson 36










{{--# every reply will have unique id--}}

{{--# add the attribute until everything has been loaded Cloack
So the replies don't Load on page till everything else in the page has been loaded
--}}
<reply :attributes="{{ $reply }}" inline-template v-cloak>
    <div id="reply-{{ $reply->id }}" class="card mb-3">
        <div class="card-header">
            <div class="level">
                <h5 class="flex">
                    <a href="{{ route('profile', $reply->owner)  }}">
                        {{ $reply->owner->name }}
                    </a> said {{ $reply->created_at->diffForHumans() }}...
                </h5>

                @if (Auth::check())
                <div>
                    <favorite :reply="{{ $reply }}"></favorite>
{{--                    <form method="POST" action="/replies/{{ $reply->id }}/favorites">--}}
{{--                        {{ csrf_field() }}--}}
{{--    --}}{{--                    disabled button             --}}
{{--                        <button type="submit" class="btn btn-default">--}}
{{--                            {{ $reply->favorites_count }} {{ str_plural('Favorite', $reply->favorites_count) }}--}}

{{--                            @if ($reply->isFavorited())--}}
{{--                                {{ method_field('DELETE') }}--}}
{{--                            @endif--}}
{{--                        </button>--}}
{{--                    </form>--}}
                </div>
                @endif
            </div>
        </div>

        <div class="card-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>

                <button class="btn btn-xs btn-primary" @click="update">Update</button>
                <button class="btn btn-xs btn-link" @click="editing = false">Cancel</button>
            </div>

            <div v-else v-text="body"></div>
        </div>

        @can ('update', $reply)
            <div class="card-footer level">
                <button class="btn-btn-xs mr-1" @click="editing = true">Edit</button>
                <button class="btn-btn-xs btn-danger mr-1" @click="destroy">Delete</button>
{{--                <form method="POST" action="/replies/{{ $reply->id }}">--}}
{{--                    {{ csrf_field() }}--}}
{{--                    {{ method_field('DELETE') }}--}}

{{--                    <button type="submit" class="btn btn-danger btn-xs">Delete</button>--}}
{{--                </form>--}}
            </div>
        @endcan
    </div>
</reply>
