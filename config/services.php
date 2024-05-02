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
        'redirect' => 'http://localhost:8000/auth/facebook/callback',
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => 'http://localhost:8000/auth/google/callback',
    ],

    'fleetbase' => [
        'storefront_key' => env('FLEETBASE_STOREFRONT_KEY'),
    ],
];
