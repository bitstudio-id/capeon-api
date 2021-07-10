<?php
/**
 * User: Cacing
 * Date: 12/03/2019
 * Time: 1:15
 */
namespace App\Http\Middleware;
use App\Exceptions\BadRequestException;
use Closure;
use Illuminate\Support\Carbon;

class Timestamp {
    protected $auth;

    public function handle($request, Closure $next, $guard = null)
    {   

        $allowed = [
            "/",
            "/ping",
        ];

        if(env("API_CHECKING", true)) {
            if(strlen($request->header("x-timestamp")) == 0) {
                if(!in_array($request->getRequestUri(), $allowed)){
                    throw new BadRequestException("timestamp_not_provided");
                }
            } else {
                $timestamp = substr($request->header("x-timestamp"), 0, 10);

                $now = Carbon::now();
                $parse = Carbon::parse((int) $timestamp);

                if(!env("API_TESTING", false)) {
                    if ($now->diffInSeconds($parse) > 30) {
                        throw new \RuntimeException('service blocked! invalid timestamp sync');
                    }
                }
            }
        }

        return $next($request);
    }
}
