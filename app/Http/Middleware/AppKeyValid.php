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
        $app_key = AppKey::where("app_key_key", $request->header("X-App-Key"))
                            ->first();

        if($app_key == null) {
            throw new BadRequestException("app_key_not_provided");
        }

        return $next($request);
    }
}
