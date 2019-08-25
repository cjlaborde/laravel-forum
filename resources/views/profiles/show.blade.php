@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
        <div class="pb-2 mt-4 mb-2 border-bottom">
{{--            # pass the sign in user as a prop that owns the profile--}}
            <avatar-form :user="{{ $profileUser }}"></avatar-form>
        </div>

        @forelse($activities as $date => $activity)
            <h3 class="page-header">{{ $date }}</h3>

            @foreach($activity as $record)
                @if (view()->exists("profiles.activities.{$record->type}"))
                    @include ("profiles.activities.{$record->type}", ['activity' => $record])
                @endif
            @endforeach
        @empty
            <p>There is no activity for this user yet</p>
        @endforelse

            </div>
        </div>
    </div>
@endsection
