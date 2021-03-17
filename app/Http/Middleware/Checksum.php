<?php
/**
 * User: Cacing
 * Date: 12/03/2019
 * Time: 1:15
 */
namespace App\Http\Middleware;
use App\Exceptions\BadRequestException;
use App\Models\AppKey;
use Carbon\Carbon;
use Closure;

class Checksum {
    protected $auth;

    public function handle($request, Closure $next, $guard = null)
    {
        $app_key = AppKey::where("app_key_value", $request->header("x-app-key"))
                            ->first();

        $value_to_int = string_value_to_int($request->all());
        $value_json = json_encode($value_to_int);
        $hash_hmac_sha256 = hash_hmac("sha256", $value_json."|".$request->header("x-timestamp"), $app_key->app_key_checksum);

        // if($hash_hmac_sha256 != $request->header("x-checksum")) {
        //     if(strlen($request->header("x-checksum")) > 0){
        //         throw new BadRequestException("invalid_checksum");
        //     } else {
        //         throw new BadRequestException("checksum_not_provided");
        //     }
        // }

        return $next($request);
    }
}
