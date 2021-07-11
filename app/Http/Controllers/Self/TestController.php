<?php

namespace App\Http\Controllers\Self;

use App\Http\Controllers\Controller;
use App\Models\User;
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

    public function assignRole()
    {
        // $user->hasRole('writer');
        // $role->givePermissionTo('edit articles');
        $user = User::find(1);
        $user->assignRole("root");

        $user_1 = User::find(22);
        $user_1->assignRole("general");

        $user_2 = User::find(25);
        $user_2->assignRole("general");

        $data = [
            "meta" => [],
            "data" => "role assigned"
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

    public function encryptDecrypt()
    {
        $string = "password^";

        // dd(md5(time()));

        // dd(hash('crc32', md5(time())).hash('crc32b', md5(time())));

        // $key    = hash('ripemd128', md5(time()));
        $key    = "ebe9ff56d213cc319b0adc2f983cdb1b";
        $iv = "0a990945ea0b58f1";

        // $iv2 = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));

        // $iv2_enc = base64_encode($iv2);

        // dd($iv2_enc, base64_decode($iv2_enc));
        // dd($iv2);

        // $encrypted_string=openssl_encrypt($string,"AES-256-ECB", $key, 0 , $iv);
        $encrypted_string=openssl_encrypt($string, 'aes-256-cbc', $key, 0, $iv);
        $decrypted_string=openssl_decrypt($encrypted_string,"aes-256-cbc", $key, 0 , $iv);

        dd($encrypted_string, $decrypted_string, $key, openssl_decrypt("HPrTERREW6z1vkwerv7d2w==", "aes-256-cbc", $key, 0, $iv));
    }

    public function encryptDecryptRsa()
    {
        $config = array(
            "digest_alg" => "sha512",
            "private_key_bits" => 4096,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        );

        // Create the private and public key
        $res = openssl_pkey_new($config);

        // Extract the private key from $res to $privKey
        openssl_pkey_export($res, $privKey);

        // Extract the public key from $res to $pubKey
        $pubKey = openssl_pkey_get_details($res);
        $pubKey = $pubKey["key"];

        $data = 'plaintext data goes here';

        // Encrypt the data to $encrypted using the public key
        openssl_public_encrypt($data, $encrypted, $pubKey);

        // Decrypt the data using the private key and store the results in $decrypted
        openssl_private_decrypt($encrypted, $decrypted, $privKey);

        echo $decrypted;
    }
}