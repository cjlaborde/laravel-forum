<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class UserAvatarController extends Controller
{
    // store new user avatar
    public function store()
    {
        request()->validate([
            'avatar' => ['required', 'image']
        ]);
        auth()->user()->update([
            'avatar_path' => request()->file('avatar')->store('avatars', 'avatar')
        ]);

        return response([], 204);
    }
}
