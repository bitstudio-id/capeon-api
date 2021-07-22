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

require __DIR__.'/auth.php';

$route_v1 = scandir(__DIR__."/self/v1");

$exclude = [
    ".",
    ".."
];

// include route on v1
foreach($route_v1 as $route) {
    if(!in_array($route, $exclude)) {
        require __DIR__.'/self/v1/'.$route;
    }
}

$router->get('', function () use ($router) {
    return [
    	"meta" => [
    		"message" => "welcome to api ".env("APP_NAME", "app"),
    		"date" => date("d-m-Y"),
    		"time" => date("H:i:s"),
    	],
    	"data" => [
            "developed_by" => [
                "@cacing69"
            ]
        ],

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
});
