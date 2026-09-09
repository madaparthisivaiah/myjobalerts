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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],    

    'careerjet' => [
        'api_key' => env('CAREERJET_API_KEY'),
        'locale' => env('CAREERJET_LOCALE', 'en_IN'),
        'base_url' => env(
            'CAREERJET_BASE_URL',
            'https://search.api.careerjet.net'
        ),
    ],

    'whatjobs' => [
        'base_url' => env('WHATJOBS_BASE_URL', 'https://api.whatjobs.com'),
        'publisher_id' => env('WHATJOBS_PUBLISHER_ID'),
        'country' => env('WHATJOBS_COUNTRY', 'India'),
        'limit' => (int) env('WHATJOBS_LIMIT', 50),
        'sync_ip' => env('WHATJOBS_SYNC_IP'),
    ],

];

//'https://search.api.careerjet.net/v4/query'