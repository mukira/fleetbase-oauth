<?php

use Fleetbase\FleetOps\Models\Contact;
use Fleetbase\Storefront\Http\Resources\Customer;
use Fleetbase\Storefront\Support\Storefront;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::prefix(config('storefront.api.routing.prefix', 'storefront'))->group(
    function () {

        Route::prefix('v1')
            ->middleware('storefront.api')
            ->namespace('v1')
            ->group(function ($router) {

                $router->get('/auth/facebook', function() {
                    return Socialite::driver('facebook')->redirect();
                });

                $router->get('/auth/google', function() {
                    return Socialite::driver('google')->redirect();
                });

                $router->get('/auth/facebook/callback', function() {

                    $facebookUser = Socialite::driver('facebook')->user();

                    //
                    $user = Fleetbase\Models\User::firstOrCreate(
                        [
                            'email' => $facebookUser->user['email'],
                        ],
                        [
                            'name' => $facebookUser->user['name']
                        ]
                    );

                    // get the storefront or network login info
                    $about = Storefront::about(['company_uuid']);

                    // get contact record
                    $contact = Contact::firstOrCreate(
                        [
                            'user_uuid'    => $user->uuid,
                            'company_uuid' => $about->company_uuid,
                            'type'         => 'customer',
                        ],
                        [
                            'user_uuid'    => $user->uuid,
                            'company_uuid' => $about->company_uuid,
                            'name'         => $facebookUser->user['name'],
                            'phone'        => null,
                            'email'        => $facebookUser->user['email'],
                            'type'         => 'customer',
                        ]
                    );

                    // get auth token
                    try {
                        $token = $user->createToken($contact->uuid);
                    } catch (Exception $e) {
                        return response()->errors($e->getMessage());
                    }

                    $contact->token = $token->plainTextToken;

                    \Log::info('FacebookUser', [ $user->user['name'], $token ]);

                    return new Customer($contact);

                });

                $router->get('/auth/google/callback', function() {
                    $user = Socialite::driver('google')->user();
                    \Log::info('GoogleUser', [ $user ]);
                });

            });
    }
);

