{{--Editing the question--}}
<div class="card mb-4" v-if="editing">
    <div class="card-header bg-transparent">
        <div class="level">
            <input type="text" class="form-control" v-model="form.title">
        </div>
    </div>

    <div class="card-body">

        <div class="form-group">
            <wysiwyg v-model="form.body"></wysiwyg>
{{--            <textarea class="form-control" rows="10" v-model="form.body"></textarea>--}}
        </div>
    </div>

    <div class="card-footer">
        <button class="btn btn-xs level-item" v-show="! editing" @click="editing = true">Edit</button>
        <button class="btn btn-primary btn-xs level-item" @click="update">Update</button>
        <button class="btn btn-xs level-item" @click="resetForm">Cancel</button>
    </div>
</div>

{{-- Viewing the question. --}}
<div class="card mb-4" v-else>
    <div class="card-header bg-transparent">
        <div class="level">
            <img src="{{ $thread->creator->avatar_path }}"
                 alt="{{ $thread->creator->name }}"
                 width="50"
                 height="50"
                 class="mr-3">
            <span class="flex">
    {{--                        <a href="/profiles/{{ $thread->creator->name }}">{{ $thread->creator->name }}</a> posted:--}}
                <a href="{{ route('profile', $thread->creator) }}">

                    {{ $thread->creator->name }} ({{ $thread->creator->reputation }} XP)
                </a> posted: <span v-text="title"></span>
            </span>

        </div>
    </div>

    <div ref="question" class="card-body">
        <highlight :content="body"></highlight>
    </div>

    <div class="card-footer" v-if="authorize('owns', thread)">
        <div class="level">
        <button class="btn btn-xs" @click="editing = true">Edit</button>

        @can ('update', $thread)
            <form action="{{ $thread->path() }}" method="POST" class="ml-a">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button type="submit" class="btn btn-xs">Delete Thread</button>
            </form>
        @endcan

        </div>
    </div>
</div>
