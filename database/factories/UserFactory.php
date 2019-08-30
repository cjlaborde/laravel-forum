<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Channel;
use App\Reply;
use App\User;
use App\Thread;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
        'confirmed' => true
    ];
});

$factory->state(App\User::class, 'unconfirmed', function () {
    return [
        'confirmed' => false
    ];
});

$factory->state(App\User::class, 'administrator', function () {
    return [
        // login to create an admin. John = Admin in User.php isAdmin function
//        'name' => 'John'
          'isAdmin' => true
        # 'is_admin = true
    ];
});




$factory->define(Thread::class, function (Faker $faker) {
    $title = $faker->sentence;

    return [
        # create user, grab the id of the user and associate the id with the thread as owner.
        'user_id' => function () {
            return factory('App\User')->create()->id;
        },
        'channel_id' => function() {
            return factory('App\Channel')->create()->id;
        },
        'title' => $title,
        'body' => $faker->paragraph,
        'visits' => 0,
        # str_slug convert spaces into dashes.
        'slug' => str_slug($title),
        'locked' => false
    ];
});

$factory->define(Channel::class, function (Faker $faker) {
    $name = $faker->unique()->word;

    return [
        'name' => $name, // servers admin
        'slug' => $name,
        'description' => $faker->sentence,
        'archived' => false,
        'color' => $faker->hexcolor
    ];
});

$factory->define(Reply::class, function (Faker $faker) {
    # Reply belongs to an user but also to a thread.
    return [
        # create user, grab the id of the user and associate the id with the thread as owner.
        'thread_id' => function () {
            return factory('App\Thread')->create()->id;
        },

        'user_id' => function () {
            return factory('App\User')->create()->id;
        },
        'body' => $faker->paragraph
    ];
});

$factory->define(DatabaseNotification::class, function (Faker $faker) {
    # Reply belongs to an user but also to a thread.
    return [
        # generate unique id using Illuminate/NotificationsSender.php uuid
        'id' => Str::uuid()->toString(),
        'type' => 'App\Notifications\ThreadWasUpdated',
        'notifiable_id' => function () {
        return auth()->id() ?: factory('App\User')->create()->id;
        },
        'notifiable_type' => 'App\User',
        'data' => ['foo' => 'bar']
    ];
});
