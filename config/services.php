<?php

/**
 * ------------------------------------------
 * Laravel Socialite Configuration
 * ------------------------------------------
 */

return [

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT'),
    ],

    'fleetbase' => [
        'storefront_key' => env('FLEETBASE_STOREFRONT_KEY'),
        'app_redirect' => env('FLEETBASE_APP_REDIRECT'),
    ],
];
