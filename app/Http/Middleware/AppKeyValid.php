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
use Closure;

class AppKeyValid {
    protected $auth;

    public function handle($request, Closure $next, $guard = null)
    {
        $app_key = AppKey::where("app_key_value", $request->header("x-app-key"))
                            ->first();

        if($app_key == null) {
            if(strlen($request->header("X-Checksum")) > 0) {
                throw new BadRequestException("invalid_app_key");
            } else {
                throw new BadRequestException("app_key_not_provided");
            }
        }

        return $next($request);
    }
}
