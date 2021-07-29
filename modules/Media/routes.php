<?php
/**
 * @OA\Tag(
 *     name="Media",
 *     description="Everything about Media",
 *     @OA\ExternalDocumentation(
 *         description="Find out more",
 *         url="http://swagger.io"
 *     )
 * )
 */
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
	'middleware' => 'api.throttle', 
	'limit' => 60, 
	'expires' => 1
] ,function ($api) {
	$api->group([
		'namespace' => 'Modules\Media\Http' ,
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