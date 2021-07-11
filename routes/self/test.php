<?php
$api->version('v1', [
	'middleware' => 'api.throttle', 
	'limit' => 60, 
	'expires' => 1
] ,function ($api) {
	$api->group([
		'namespace' => 'App\Http\Controllers\Self' ,
		'prefix' => 'test',
	], function($api){
		$api->group([
			'middleware' => [
				"key"
			],
		], function($api){
			$api->group([
				'middleware' => [
					"auth"
				],
			], function($api){
				$api->get('auth', 'TestController@auth');
			});

			$api->group([
				'middleware' => [
					"checksum"
				],
			], function($api){
				$api->post('checksum', 'TestController@checksum');
			});
			
		});
		$api->get('phpinfo', 'TestController@phpinfo');
		$api->get('assign-role', 'TestController@assignRole');
		$api->get('encrypt-decrypt', 'TestController@encryptDecrypt');
		$api->get('encrypt-decrypt-rsa', 'TestController@encryptDecryptRsa');
	});
});