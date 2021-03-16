<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use phpseclib3\Crypt\RSA;

class TestController extends Controller {
	public function auth() {
        $data = [
            "meta" => [],
            "data"      => auth()->user()
        ];

        return response()->json($data, 200);
    }

    public function phpinfo()
    {
    	phpinfo();
    }

    public function checksum(Request $request) {
    	$data = $request->all();

    	$parsing = string_value_to_int($data);

    	$config = [
    		'config' => 'C:\laragon\bin\php\php-7.4.15-Win32-vc15-x64\extras\ssl\openssl.cnf',
			"private_key_bits" => 2048,
		    'private_key_type' => OPENSSL_KEYTYPE_RSA,
		    "digest_alg" => 'sha512',
    	];

    	$key_pair = openssl_pkey_new($config);

		openssl_pkey_export($key_pair, $private_key, null, $config);

    	$public_key = openssl_pkey_get_details($key_pair)['key'];


    	// encrypted
    	$plain_text = 'Lorem ipsum dolor sit amet';

		$public_resource = openssl_pkey_get_public($public_key);
		openssl_public_encrypt(gzcompress($plain_text), $encrypted_result, $public_resource);
		openssl_free_key($public_resource);
    	

		// decrypted
		$private_resource = openssl_pkey_get_private($private_key);
		openssl_private_decrypt($encrypted_result, $decrypted_result, $private_resource);
		openssl_free_key($private_resource);

    	dd($plain_text, 
    		gzcompress($plain_text), 
    		$encrypted_result,
    		base64_encode($encrypted_result),
    		// decrypted process
    		base64_decode(base64_encode($encrypted_result)),
    		$decrypted_result, 
    		gzuncompress($decrypted_result),  

    	);

    	return $encode;

    	$checksum = md5(collect($request));
        return $checksum;
    }
}