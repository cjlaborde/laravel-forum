<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//dd(App::environment());
// Route::get('/', function () {
//    \Mail::to(App\User::first())->send(new \App\Mail\PleaseConfirmYourEmail());
//    return view('welcome');
//});

Route::redirect('', 'threads');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::view('scan', 'scan');

Route::get('threads', 'ThreadsController@index')->name('threads');
Route::get('threads/create', 'ThreadsController@create')->middleware('must-be-confirmed');
Route::get('threads/search', 'SearchController@show');
Route::get('threads/{channel}/{thread}', 'ThreadsController@show');
Route::patch('threads/{channel}/{thread}', 'ThreadsController@update');

Route::delete('threads/{channel}/{thread}', 'ThreadsController@destroy');
Route::post('threads', 'ThreadsController@store')->middleware('must-be-confirmed');
Route::get('threads/{channel}', 'ThreadsController@index')->name('channels');
Route::get('/threads/{channel}/{thread}/replies', 'RepliesController@index');
Route::post('/threads/{channel}/{thread}/replies', 'RepliesController@store');
Route::post('locked-threads/{thread}', 'LockedThreadsController@store')->name('locked-threads.store')->middleware('admin');
Route::delete('locked-threads/{thread}', 'LockedThreadsController@destroy')->name('locked-threads.destroy')->middleware('admin');

Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@store')->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@destroy')->middleware('auth');

Route::post('pinned-threads/{thread}', 'PinnedThreadsController@store')->name('pinned-threads.store')->middleware('admin');
Route::delete('pinned-threads/{thread}', 'PinnedThreadsController@destroy')->name('pinned-threads.destroy')->middleware('admin');

Route::patch('/replies/{reply}', 'RepliesController@update')->name('replies.update');
Route::delete('/replies/{reply}', 'RepliesController@destroy')->name('replies.destroy');

Route::post('/replies/{reply}/best', 'BestRepliesController@store')->name('best-replies.store');
Route::post('/replies/{reply}/favorites', 'FavoritesController@store')->name('replies.favorite');
Route::delete('/replies/{reply}/favorites', 'FavoritesController@destroy');

Route::get('/profiles/{user}', 'ProfilesController@show')->name('profile');
Route::get('/profiles/{user}/notifications', 'UserNotificationsController@index');
Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationsController@destroy');

Route::get('/register/confirm', 'Auth\RegisterConfirmationController@index')->name('register.confirm');

Route::get('/profiles/{user}/activity', 'ProfilesController@index')->name('activity');

Route::get('api/users', 'Api\UsersController@index')->name('api.users');
Route::post('api/users/{user}/avatar', 'Api\UserAvatarController@store')->middleware('auth')->name('avatar');
Route::get('api/channels', 'Api\ChannelsController@index');

Route::get('api/leaderboard', 'Api\LeaderboardController@index')->name('api.leaderboard.index');
Route::get('leaderboard', 'LeaderboardController@index')->name('leaderboard.index');

Route::group([
    'prefix' => 'admin',
    'middleware' => 'admin',
    'namespace' => 'Admin'
], function () {
    Route::get('/', 'DashboardController@index')->name('admin.dashboard.index');
    Route::post('/channels', 'ChannelsController@store')->name('admin.channels.store');
    Route::get('/channels', 'ChannelsController@index')->name('admin.channels.index');
    Route::get('/channels/create', 'ChannelsController@create')->name('admin.channels.create');
    Route::get('/channels/{channel}/edit', 'ChannelsController@edit')->name('admin.channels.edit');
    Route::patch('/channels/{channel}', 'ChannelsController@update')->name('admin.channels.update');
});
