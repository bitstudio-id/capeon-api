<?php

namespace App\Providers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Model\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            $token = $request->header('authorization');

            $bearer =  'Bearer';

            $tokenExplode = explode(" ", $token);

            if(count($tokenExplode) > 1) {
                $token = $tokenExplode[1];

                try {
                    $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
                } catch(Exception $e) {
                    throw new Exception("invalid token provided");
                }

                $sub = $credentials->sub;

                // $u = db()->table("m_user")->where("user_id", $sub->id)->first();
                $u = User::find($sub->id);

                return $u;
            }
        });
    }
}
