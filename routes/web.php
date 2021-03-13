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
	$api->post('auth/token', 'App\Http\Controllers\AuthController@token');
	$api->post('auth/register', 'App\Http\Controllers\AuthController@register');
	$api->post('auth/register-confirm', 'App\Http\Controllers\AuthController@registerConfirm');
});