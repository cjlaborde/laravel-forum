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
    ]
];