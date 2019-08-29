<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Favorite;

class FavoritesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Reply $reply)
    {
        $reply->favorite();

        return back();
    }

    public function destroy(Reply $reply)
    {
        $reply->unfavorite();

//        $favorite->where('user_id', auth()->id())->delete();
//
//        return back();
    }
}
