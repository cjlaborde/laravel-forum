@extends('layouts.app')

@section('content')
        <div class="mb-8">
{{--            # pass the sign in user as a prop that owns the profile--}}
            <avatar-form :user="{{ $profileUser }}"></avatar-form>
        </div>

        <activities :user="{{ $profileUser }}"></activities>

@endsection
