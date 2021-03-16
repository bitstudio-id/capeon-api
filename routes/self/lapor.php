<?php
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
	'middleware' => 'api.throttle', 
	'limit' => 60, 
	'expires' => 1
] ,function ($api) {
	$api->group([
		'namespace' => 'App\Http\Controllers\Self' ,
		'prefix' => 'lapor',
	], function($api){
		$api->group([
			'middleware' => [
				"key",
				"auth"
			],
		], function($api){
			$api->group([
				'middleware' => [
					"checksum"
				],
			], function($api){
				$api->post('', 'LaporController@store');
			});
			
			$api->get('', 'LaporController@index');
			$api->get('self', 'LaporController@self');
			$api->delete('{id}', 'LaporController@delete');
		});
	});
});