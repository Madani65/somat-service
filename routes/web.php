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
$router->post("student/upsert", ["middleware" => [], "uses" => "StudentController@upsert"]);

$router->post("class/major/upsert", ["middleware" => [], "uses" => "ClassMajorController@upsert"]);
$router->post("class/major/get", ["middleware" => [], "uses" => "ClassMajorController@get"]);
$router->post("class/major/delete", ["middleware" => [], "uses" => "ClassMajorController@delete"]);
$router->post("school/level/get", ["middleware" => [], "uses" => "SchoolLevelController@get"]);

$router->post("class/upsert", ["middleware" => [], "uses" => "ClassController@upsert"]);
$router->post("class/get", ["middleware" => [], "uses" => "ClassController@get"]);
$router->post("class/delete", ["middleware" => [], "uses" => "ClassController@delete"]);
$router->post("school/year/get", ["middleware" => [], "uses" => "SchoolYearController@get"]);

$router->group(["middleware" => ["signature", "xss-sanitizer"]], function () use ($router) {

    // $router->group(["middleware" => "auth"], function () use ($router) {
    // });
});

require("dev.php");
