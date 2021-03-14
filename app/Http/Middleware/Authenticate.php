<?php
/**
 * User: Cacing
 * Date: 12/03/2019
 * Time: 1:15
 */
namespace App\Http\Middleware;

use App\Exceptions\BadRequestException;
use App\Exceptions\UnAuthorizedException;
use App\Models\AppKey;
use App\Models\Token;
use Closure;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;

class Authenticate {
    protected $auth;

    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->header('authorization');

        $bearer =  'Bearer';

        if(!$token) {
            throw new UnAuthorizedException("token_not_provided");
        }

        $tokenExplode = explode(" ", $token);
        $token = $tokenExplode[1];

        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch(ExpiredException $e) {
            throw new UnAuthorizedException("token_expired");
        } catch(Exception $e) {
            throw new UnAuthorizedException("invalid_token");
        }

        // cek apakah token di blok atau tidak
        $hash = hash_hmac("sha256", $token, env("JWT_SECRET"));

        $tokenCheck = Token::where("token_value", $hash)
            ->where("token_allowed", 1)
            ->first();

        if($tokenCheck == null) {
            throw new UnAuthorizedException("token_not_allowed");
        }

        $app_key = AppKey::where("app_key_key", $request->header("X-App-Key"))
                            ->first();

        if($app_key->app_key_id != $tokenCheck->token_app_key_id) {
            throw new UnAuthorizedException("missmatch_token_and_app_id");
        }

        return $next($request);
    }
}
