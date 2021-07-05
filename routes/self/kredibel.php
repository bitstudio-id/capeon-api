<?php
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
	'middleware' => 'api.throttle', 
	'limit' => 60, 
	'expires' => 1
] ,function ($api) {
	$api->group([
		'namespace' => 'App\Http\Controllers\Self' ,
		'prefix' => 'kredibel',
	], function($api){
		$api->group([
			'middleware' => [
				"key",
				"auth"
			],
		], function($api){
			$api->get('', 'KredibelController@index');
		});
	});
});