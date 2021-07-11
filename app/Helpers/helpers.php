<?php

use App\Exceptions\InvalidHashException;
use App\Exceptions\InvalidKeyEncryptionException;
use App\Models\AppKey;
use App\Models\Hash;

if ( ! function_exists('config_path'))
{
    /**
     * Get the configuration path.
     *
     * @param  string $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
    }
}

if ( ! function_exists('public_app_path'))
{
    function public_app_path($path = null)
    {
        return rtrim(app()->basePath('public/' . $path), '/');
    }
}

if ( ! function_exists('get_last_id'))
{
    function get_last_id($array, $key)
    {
        if(count($array) > 0) {
            return $array[count($array) - 1]->{$key};
        } else {    
            return null;
        }
    }
}

if (!function_exists('get_sql')) {
    function get_sql($model)
    {
        $replace = function ($sql, $bindings) {
            $needle = '?';
            foreach ($bindings as $replace) {
                $pos = strpos($sql, $needle);
                if ($pos !== false) {
                    if (gettype($replace) === "string") {
                        $replace = ' "' . addslashes($replace) . '" ';
                    }
                    $sql = substr_replace($sql, $replace, $pos, strlen($needle));
                }
            }
            return $sql;
        };

        $sql = $replace($model->toSql(), $model->getBindings());
        return $sql;
    }
}

if ( ! function_exists('string_value_to_int'))
{
    function string_value_to_int($value)
    {
        $tmp = [];

        if(is_array($value)) {
            foreach ($value as $key => $value) {
                $tmp[$key] = string_value_to_int($value);
            }
        } else {
            if(is_numeric($value)) {
                $tmp = $value + 0;
            } else {
                $tmp = $value;
            }
        }

        return $tmp;
    }
}

if (!function_exists('validate_hash')) {
    function validate_hash($request)
    {
        // dd(env("API_HASH", true));
        if(env("API_HASH", true)){
            if(strlen($request->header("x-hash")) > 0) {
                $hash = Hash::where("hash_value", $request->header("x-hash"))->first();
                if($hash != null) {
                    if(filter_var($hash->hash_valid, FILTER_VALIDATE_BOOLEAN)) {

                        // dd($request->getRequestUri(), $hash->hash_url);

                        if($request->getRequestUri() == $hash->hash_url) {
                            if($hash->hash_created_by == null) {
                                return $hash;
                            } else {
                                if(auth()->id() == $hash->hash_created_by) {
                                    return $hash;
                                } else {
                                    throw new InvalidHashException("illegal_hash_action");
                                }
                            }
                        } else {
                            throw new InvalidHashException("wrong_hash_url");
                        }
                    } else {
                        throw new InvalidHashException("hash_invalid");
                    }
                } else {
                    throw new InvalidHashException("hash_not_registered");
                }
            } else {
                throw new InvalidHashException("hash_not_provided");
            }
        } else {
            return new Hash();
        }
    }
}

if (!function_exists('commit_hash')) {
    function commit_hash($request)
    {
        if(env("API_HASH", true)){
            $hash = validate_hash($request);

            $data = [
                "hash_commit_at" => date("Y-m-d H:i:s"),
                "hash_commit_by" => auth()->id(),
                "hash_valid" => 0,
            ];

            // update where init, from and to where created by this user
            $updateHash = Hash::query();

            if($hash->hash_created_by != null) {

                $updateHash = $updateHash->where("hash_created_by", auth()->id())
                                    ->where("hash_valid", 1)
                                    ->where("hash_url", $hash->hash_url);
            } else {
                $updateHash = $updateHash->where("hash_id", $hash->hash_id);
            }

            $updateHash = $updateHash->update($data);
        }
    }
}

if (!function_exists('enc_cbc')) {
    function enc_cbc($string, $key)
    {
        if(env("API_ENCRYPT_DECRYPT", true)){
            $keyOnDb = AppKey::where("app_key_value", $key)
                                ->first();

            if($keyOnDb != null) {
                $_string = openssl_encrypt($string, 'aes-256-cbc', $keyOnDb->app_key_aes_cbc, 0, $keyOnDb->app_key_aes_cbc_iv);

                return $_string;
            } else {
                throw new InvalidKeyEncryptionException("app_key_not_found_to_encrypted");
            }
        } else {
            return $string;
        }
    }
}

if (!function_exists('dec_cbc')) {
    function dec_cbc($string, $key)
    {
        if(env("API_ENCRYPT_DECRYPT", true)){
            $keyOnDb = AppKey::where("app_key_value", $key)
                                ->first();

            if($keyOnDb != null) {
                $_string = openssl_decrypt($string, 'aes-256-cbc', $keyOnDb->app_key_aes_cbc, 0, $keyOnDb->app_key_aes_cbc_iv);

                if(!$_string) {
                    throw new InvalidKeyEncryptionException("failed_on_decrypt");
                }

                return $_string;
            } else {
                throw new InvalidKeyEncryptionException("app_key_not_found_to_encrypted");
            }
        } else {
            return $string;
        }
    }
}

