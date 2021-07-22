<?php
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
	'middleware' => 'api.throttle', 
	'limit' => 60, 
	'expires' => 1
] ,function ($api) {
	$api->group([
		'namespace' => 'App\Http\Controllers\Self' ,
		'prefix' => 'media',
	], function($api){
		$api->group([
			'middleware' => [
				"key",
				"auth"
			],
		], function($api){
			$api->group([
				'middleware' => [
					"checksum",
					"hash",
				],
			], function($api){

				$api->post('', 'MediaController@store');
			});
			
			$api->get('', 'MediaController@index');
		});
	});
});