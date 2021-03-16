<?php
/**
 * User: Cacing
 * Date: 12/03/2019
 * Time: 1:15
 */
namespace App\Http\Middleware;
use App\Exceptions\BadRequestException;
use Closure;

class Timestamp {
    protected $auth;

    public function handle($request, Closure $next, $guard = null)
    {
        // if (now()->diffInSeconds(Carbon::parse($timestamp)) > 30) {
        //     throw new \RuntimeException('Service blocked! Invalid Timestamp Synchronization');
        // }
        
        if(strlen($request->header("X-Timestamp")) == 0) {
            throw new BadRequestException("timestamp_not_provided");
        }

        return $next($request);
    }
}
