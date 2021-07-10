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

$router->get('', function () use ($router) {
    return [
    	"meta" => [
    		"message" => "welcome to api capeon",
    		"date" => date("d-m-Y"),
    		"time" => date("H:i:s"),
    	],
    	"data" => [],

    ];
});

$router->get('hash', 'GeneralController@hash');
$router->get('ping', function () use ($router) {
    return [
        "meta" => [
            "message" => "ping_success",
        ],
        "data" => [],

    ];
