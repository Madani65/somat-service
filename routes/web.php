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
        $router->post("role/get", ["middleware" => [], "uses" => "RoleController@get"]);

        $router->post("account-role/upsert", ["middleware" => ["account-entity"], "uses" => "AccountRoleController@upsert"]);
        $router->post("account-role/get", ["middleware" => ["account-entity"], "uses" => "AccountRoleController@get"]);
        $router->post("account-role/delete", ["middleware" => ["account-entity"], "uses" => "AccountRoleController@delete"]);

        $router->post("account-entity-accesses/upsert", ["middleware" => ["account-entity"], "uses" => "AccEntAccessController@upsert"]);
        $router->post("account-entity-accesses/get", ["middleware" => ["account-entity"], "uses" => "AccEntAccessController@get"]);
        $router->post("account-entity-accesses/delete", ["middleware" => ["account-entity"], "uses" => "AccEntAccessController@delete"]);

        $router->post("currency/get", ["middleware" => [], "uses" => "CurrencyController@get"]);
        $router->post("currency/upsert", ["middleware" => [], "uses" => "CurrencyController@upsert"]);
        $router->post("currency/delete", ["middleware" => [], "uses" => "CurrencyController@delete"]);

        $router->post("entity-currencies/get", ["middleware" => [], "uses" => "EntityCurrencyController@get"]);
        $router->post("entity-currencies/map", ["middleware" => [], "uses" => "EntityCurrencyController@map"]);

        $router->post("partner-type/get", ["middleware" => [], "uses" => "PartnerTypeController@get"]);

        $router->post("business-partner/upsert", ["middleware" => ["account-entity"], "uses" => "BusinessPartnerController@upsert"]);
        $router->post("business-partner/get", ["middleware" => ["account-entity"], "uses" => "BusinessPartnerController@get"]);
        $router->post("business-partner/delete", ["middleware" => ["account-entity"], "uses" => "BusinessPartnerController@delete"]);
    });
});

require("dev.php");