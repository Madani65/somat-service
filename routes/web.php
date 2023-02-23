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

$router->get('/', function () use ($router) {
    return env("APP_NAME", $router->app->version());
});

$router->group(["middleware" => ["signature", "xss-sanitizer"]], function () use ($router) {
    $router->group(["middleware" => "auth"], function () use ($router) {
        $router->post("currency/get", ["middleware" => [], "uses" => "CurrencyController@get"]);
        $router->post("currency/upsert", ["middleware" => [], "uses" => "CurrencyController@upsert"]);
        $router->post("currency/delete", ["middleware" => [], "uses" => "CurrencyController@delete"]);

        $router->post("entity-currencies/get", ["middleware" => [], "uses" => "EntityCurrencyController@get"]);
        $router->post("entity-currencies/map", ["middleware" => [], "uses" => "EntityCurrencyController@map"]);
    });
});

require("dev.php");