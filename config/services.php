<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    // 'google' => [
    //     'client_id' => '796523360716-tooabbvabef015j27q6hfbthom5fu5o1.apps.googleusercontent.com',
    //     'client_secret' => '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',
    //     'redirect' => 'https://test.scorp.co/auth/google/callback',
    // ],
    
     'google' => [
        'client_id' => '930366102613-ir658fhtcbsbhc4mgcqgq7l66uu2osf6.apps.googleusercontent.com',
        'client_secret' => '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',
        'redirect' => 'https://erp.scorp.co/auth/google/callback',
    ],
];
