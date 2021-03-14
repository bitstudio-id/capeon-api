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
		'middleware' => [
				"app.key",
			],
	], function($api){
		$api->post('token', 'AuthController@token');
		$api->post('register', 'AuthController@register');
		$api->post('register-confirm', 'AuthController@registerConfirm');
	});

	// test
	$api->group([
		'namespace' => 'App\Http\Controllers' ,
		'prefix' => 'test',
	], function($api){
		$api->group([
			'middleware' => [
				"app.key",
				"auth",
			],
		], function($api){
			$api->get('auth', 'TestController@auth');
		});

	});

	// lapor
	$api->group([
		'namespace' => 'App\Http\Controllers\Self' ,
		'prefix' => 'lapor',
	], function($api){
		$api->group([
			'middleware' => [
				"app.key",
				"auth"
			],
		], function($api){
			$api->get('', 'LaporController@index');
			$api->post('', 'LaporController@store');
			$api->delete('{id}', 'LaporController@delete');
		});
	});

	// media 
	$api->group([
		'namespace' => 'App\Http\Controllers\Self' ,
		'prefix' => 'media',
	], function($api){
		$api->group([
			'middleware' => [
				"app.key",
				"auth"
			],
		], function($api){
			$api->get('', 'MediaController@index');
		});
	});

	// bank 
	$api->group([
		'namespace' => 'App\Http\Controllers\Self' ,
		'prefix' => 'bank',
	], function($api){
		$api->group([
			'middleware' => [
				"app.key",
				"auth"
			],
		], function($api){
			$api->get('', 'BankController@index');
		});
	});
});