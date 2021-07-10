<?php

namespace App\Http\Middleware;

use App\Exceptions\InvalidHashException;
use Closure;

class Hash
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(env("APP_HASH", true)){
            if(strlen($request->header("x-hash")) > 0){
                $hash = validate_hash($request);
                
                return $next($request);
            } else {
                throw new InvalidHashException("hash_not_provided");
            }
        } else {
            return $next($request);
        }
    }
}
