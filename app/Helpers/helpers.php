<?php

use App\Exceptions\BadRequestException;
use App\Exceptions\InvalidHashException;
use App\Exceptions\InvalidKeyEncryptionException;
use App\Models\AppKey;
use App\Models\Hash;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

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
                if($keyOnDb->app_key_aes_cbc == null) {
                    throw new InvalidKeyEncryptionException("no_app_key_aes_cbc");
                }

                if($keyOnDb->app_key_aes_cbc_iv == null) {
                    throw new InvalidKeyEncryptionException("no_app_key_aes_cbc_iv");
                }

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

                if($keyOnDb->app_key_aes_cbc == null) {
                    throw new InvalidKeyEncryptionException("no_app_key_aes_cbc");
                }

                if($keyOnDb->app_key_aes_cbc_iv == null) {
                    throw new InvalidKeyEncryptionException("no_app_key_aes_cbc_iv");
                }

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

if (!function_exists('urlGenerator')) {
    /**
     * @return \Laravel\Lumen\Routing\UrlGenerator
     */
    function urlGenerator() {
        return new \Laravel\Lumen\Routing\UrlGenerator(app());
    }
}

if (!function_exists('asset')) {
    /**
     * @param $path
     * @param bool $secured
     *
     * @return string
     */
    function asset($path, $secured = false) {
        return urlGenerator()->asset($path, $secured);
    }
}

if (!function_exists('store_image')) {
    function store_image($data, $path = "", $options = ["thumbnail" => false, "max_size" => 1280]) {

        if(!array_key_exists("tmp_name", $data)) {
            throw new BadRequestException("ionvalid data, no tmp_name key");
        }

        if(!array_key_exists("thumbnail", $options)) {
            $options["thumbnail"] = false;
        }

        if(!array_key_exists("max_size", $options)) {
            $options["max_size"] = 1280;
        }

        $return = [];

        $temp   = $data["tmp_name"];

        $now    = date("Ymdhis");

        $name   = $data['name'];
        $size   = $data['size'];
        $type   = $data['type'];

        $explode_name   = explode('.', $name);
        $extension      = $explode_name[count($explode_name) - 1];
        
        $file_name      = $now."-".Str::random(32).".".$extension;
        
        $dir = public_app_path("images");
        
        move_uploaded_file($temp, $dir."/".$file_name);
            
        $return["original"] = "images/".$file_name;
        $return["thumb_square"] = null;
        $return["thumb_landscape"] = null;
        
        // resize
        $imgResize = Image::make(public_app_path($return["original"]));

        if($imgResize->width() > $imgResize->height()) {
            if($imgResize->width() > $options["max_size"]) {
                $imgResize->resize($options["max_size"], null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
        }

        if($imgResize->height() > $imgResize->width()) {
            if($imgResize->height() > $options["max_size"]) {
                $imgResize->resize(null, $options["max_size"], function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
        }
        
        $imgResize->save(public_app_path($return["original"]), 75);

        // generate thumbnail
        if($options["thumbnail"]) {
            // thumbnail square
            $thumbSquare = Image::make(public_app_path($return["original"]))->fit(500, 500);
            
            $return["thumb_square"] = '/images/thumbnail/square/' . $file_name;
            
            $thumbSquarePath = public_app_path($return["thumb_square"]);
            $thumbSquareImage = Image::make($thumbSquare)->save($thumbSquarePath, 75);

            // thumbnail landscape
            $thumbLandscape = Image::make(public_app_path($return["original"]))->fit(500, 375);
            $return["thumb_landscape"] = '/images/thumbnail/landscape/' . $file_name;
            
            $thumbLandscapePath = public_app_path($return["thumb_landscape"]);
            $thumbLandscapeImage = Image::make($thumbLandscape)->save($thumbLandscapePath, 75);
        }

        return $return;
    }
}

