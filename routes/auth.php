<?php
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
	'middleware' => 'api.throttle', 
	'limit' => 60, 
	'expires' => 1
] ,function ($api) {
	$api->group([
		'namespace' => 'App\Http\Controllers' ,
		'prefix' => 'auth',
		'middleware' => [
			"key",
		],
	], function($api){
		$api->group([
			'middleware' => [
				"checksum",
				"hash"
			],
		], function($api){

			// $api->group([
			// 	'middleware' => [
			// 		"hash"
			// 	],
			// ], function($api){
			// });

			$api->post('token', 'AuthController@token');
			$api->post('register', 'AuthController@register');
			$api->post('register-confirm', 'AuthController@registerConfirm');
		});
	});
});