<?php
/**
 * User: Cacing
 * Date: 12/03/2019
 * Time: 1:15
 */
namespace Middleware;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Model\Token;

class Authenticate
{
    protected $auth;

    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->header('authorization');

        $bearer =  'Bearer';

        if(!$token) {
            return failed("token not provided", 401, $request);
        }

        $tokenExplode = explode(" ", $token);
        $token = $tokenExplode[1];

        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch(ExpiredException $e) {
            return failed("token expired",401, $request);
        } catch(Exception $e) {
            return failed("invalid token",401, $request);
        }

        // cek apakah token di blok atau tidak
        $hash = hash_hmac("sha256", $token, env("JWT_SECRET"));

        $tokenCheck = Token::where("token_value", $hash)
            ->where("token_allowed", 1)
            ->first();

        if($tokenCheck == null) {
            return failed("token not allowed",401, $request);
        }

        if($request->header(X_APP_ID) != $tokenCheck->token_app_uuid) {
            return failed("missmatch token and app id",401, $request);
        }

        return $next($request);
    }
}
