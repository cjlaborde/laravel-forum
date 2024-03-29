<?php

return [
    'recaptcha' => [
        'key' => env('RECAPTCHA_KEY'),
        'secret' => env('RECAPTCHA_SECRET')
    ],
    'administrators' => [
        'joe@gmail.com',
        'john@gmail.com'
        // Add the email addresses of users who should be administrators here.
    ],

    'reputation' => [
        'thread_published' => 10,
        'reply_posted' => 2,
        'best_reply_awarded' => 50,
        'reply_favorited' => 5
    ],

    'pagination' => [
        'perPage' => 25
    ]
];
