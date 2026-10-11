<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'code_execution' => [
        'driver' => env('CODE_EXECUTION_DRIVER', 'piston'),
        'piston_url' => env('PISTON_API_URL', 'https://emkc.org/api/v2/piston/execute'),
        'judge0_url' => env('JUDGE0_API_URL', 'https://ce.judge0.com'),
        'judge0_api_key' => env('JUDGE0_API_KEY', null),
        'hackerearth_secret' => env('HACKEREARTH_CLIENT_SECRET', null),
        'timeout' => env('CODE_EXECUTION_TIMEOUT', 15),
    ],

];
