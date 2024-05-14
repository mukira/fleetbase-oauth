<?php

use Fleetbase\FleetOps\Models\Contact;
use Fleetbase\Storefront\Http\Resources\Customer;
use Fleetbase\Storefront\Models\Network;
use Fleetbase\Storefront\Models\Store;
use Fleetbase\Storefront\Support\Storefront;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::prefix(config('storefront.api.routing.prefix', 'storefront'))->group(
    function () {

        Route::prefix('v1')
            ->middleware('web')
            ->namespace('v1')
            ->group(function ($router) {

                $router->get('/auth/facebook', function() {
                    return Socialite::driver('facebook')->redirect();
                });

                $router->get('/auth/google', function() {
                    return Socialite::driver('google')->redirect();
                });

                $router->get('/auth/facebook/callback', function() {

                    $facebookUser = Socialite::driver('facebook')->stateless()->user();

                    $key = config('services.fleetbase.storefront_key');

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
                    if (\Str::startsWith($key, 'store')) {
                        $about = $about = Store::select(['company_uuid'])->where('key', $key)->first();
                    } else {
                        $about = Network::select(['company_uuid'])->where('key', $key)->first();
                    }

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

                    $app_url = trim(config('services.fleetbase.app_redirect')) . '/' . $token->plainTextToken;

                    return response()->redirect($app_url);

                });

                $router->get('/auth/google/callback', function() {

                    $googleUser = Socialite::driver('google')->stateless()->user();

                    $key = config('services.fleetbase.storefront_key');

                    //
                    $user = Fleetbase\Models\User::firstOrCreate(
                        [
                            'email' => $googleUser->user['email'],
                        ],
                        [
                            'name' => $googleUser->user['name']
                        ]
                    );

                    // get the storefront or network login info
                    if (\Str::startsWith($key, 'store')) {
                        $about = $about = Store::select(['company_uuid'])->where('key', $key)->first();
                    } else {
                        $about = Network::select(['company_uuid'])->where('key', $key)->first();
                    }

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
                            'name'         => $googleUser->user['name'],
                            'phone'        => null,
                            'email'        => $googleUser->user['email'],
                            'type'         => 'customer',
                        ]
                    );

                    // get auth token
                    try {
                        $token = $user->createToken($contact->uuid);
                    } catch (Exception $e) {
                        return response()->errors($e->getMessage());
                    }

                    $app_url = trim(config('services.fleetbase.app_redirect')) . '/' . $token->plainTextToken;

                    return redirect($app_url);

                });

            });
    }
);

