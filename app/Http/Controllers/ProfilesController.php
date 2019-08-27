<?php

namespace App\Http\Controllers;

use App\User;
use App\Activity;

class ProfilesController extends Controller
{
    public function show(User $user)
    {
        // latest so that must recent activity as the top
        // see all activities in website profile page

        // MANIPULATE JSON To ShowDATE
        //        return $activities;

        return view('profiles.show', [
            'profileUser' => $user,
//            'activities' => $this->getActivity($user)
            'activities' => Activity::feed($user)
        ]);
    }
}
