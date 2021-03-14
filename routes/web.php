<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$api = app('Dingo\Api\Routing\Router');

$router->get('/', function () use ($router) {
    return [
    	"meta" => [
    		"message" => "welcome to api capeon",
    		"date" => date("d-m-Y"),
    		"time" => date("H:i:s"),
    	],
    	"data" => [],

    ];
});

$api->version('v1', function ($api) {
	$api->group([
		'namespace' => 'App\Http\Controllers' ,
		'prefix' => 'auth',
	], function($api){
		$api->post('token', 'AuthController@token');
		$api->post('register', 'AuthController@register');
		$api->post('register-confirm', 'AuthController@registerConfirm');
	});

	$api->group([
		'namespace' => 'App\Http\Controllers' ,
		'prefix' => 'test',
	], function($api){
		$api->group([
			'middleware' => [
				"auth"
			],
		], function($api){
			$api->get('auth', 'TestController@auth');
		});

	});

	$api->group([
		'namespace' => 'App\Http\Controllers\Self' ,
		'prefix' => 'lapor',
	], function($api){
		$api->group([
			'middleware' => [
				"auth"
			],
		], function($api){
			$api->get('', 'LaporController@index');
			$api->post('', 'LaporController@store');
			$api->get('{id}', 'LaporController@show');
			$api->delete('{id}', 'LaporController@delete');
		});
	});
});