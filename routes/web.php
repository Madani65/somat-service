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

        $router->post("currency/get", ["middleware" => [], "uses" => "CurrencyController@get"]);
        $router->post("currency/upsert", ["middleware" => [], "uses" => "CurrencyController@upsert"]);
        $router->post("currency/delete", ["middleware" => [], "uses" => "CurrencyController@delete"]);

        $router->post("entity-currencies/get", ["middleware" => [], "uses" => "EntityCurrencyController@get"]);
        $router->post("entity-currencies/map", ["middleware" => [], "uses" => "EntityCurrencyController@map"]);

        $router->post("partner-type/get", ["middleware" => [], "uses" => "PartnerTypeController@get"]);

        $router->post("uom/get", ["middleware" => [], "uses" => "UoMController@get"]);

        $router->group(["middleware" => "account-entity"], function () use ($router) {
            $router->post("account-role/upsert", ["middleware" => [], "uses" => "AccountRoleController@upsert"]);
            $router->post("account-role/get", ["middleware" => [], "uses" => "AccountRoleController@get"]);
            $router->post("account-role/delete", ["middleware" => [], "uses" => "AccountRoleController@delete"]);

            $router->post("account-entity-accesses/upsert", ["middleware" => [], "uses" => "AccEntAccessController@upsert"]);
            $router->post("account-entity-accesses/get", ["middleware" => [], "uses" => "AccEntAccessController@get"]);
            $router->post("account-entity-accesses/delete", ["middleware" => [], "uses" => "AccEntAccessController@delete"]);

            $router->post("business-partner/upsert", ["middleware" => [], "uses" => "BusinessPartnerController@upsert"]);
            $router->post("business-partner/get", ["middleware" => [], "uses" => "BusinessPartnerController@get"]);
            $router->post("business-partner/delete", ["middleware" => [], "uses" => "BusinessPartnerController@delete"]);

            $router->post("product-category/get", ["middleware" => [], "uses" => "ProductCategoryController@get"]);
            $router->post("product-category/upsert", ["middleware" => [], "uses" => "ProductCategoryController@upsert"]);
            $router->post("product-category/delete", ["middleware" => [], "uses" => "ProductCategoryController@delete"]);

            $router->post("product/get", ["middleware" => [], "uses" => "ProductController@get"]);
            $router->post("product/upsert", ["middleware" => [], "uses" => "ProductController@upsert"]);
            $router->post("product/delete", ["middleware" => [], "uses" => "ProductController@delete"]);

            $router->post("payment-method/get", ["middleware" => [], "uses" => "PaymentMethodController@get"]);
            $router->post("payment-method/upsert", ["middleware" => [], "uses" => "PaymentMethodController@upsert"]);
            $router->post("payment-method/delete", ["middleware" => [], "uses" => "PaymentMethodController@delete"]);
            $router->post("payment-method/set-default", ["middleware" => [], "uses" => "PaymentMethodController@setdefault"]);

            $router->post("tax/get", ["middleware" => [], "uses" => "TaxController@get"]);
            $router->post("tax/upsert", ["middleware" => [], "uses" => "TaxController@upsert"]);
            $router->post("tax/delete", ["middleware" => [], "uses" => "TaxController@delete"]);
            $router->post("tax/set-autoadd", ["middleware" => [], "uses" => "TaxController@setautoadd"]);

            $router->post("pos-session/get", ["middleware" => [], "uses" => "PosSessionController@get"]);
            $router->post("pos-session/upsert", ["middleware" => [], "uses" => "PosSessionController@upsert"]);
            $router->post("pos-session/delete", ["middleware" => [], "uses" => "PosSessionController@delete"]);

            $router->post("discount/get", ["middleware" => [], "uses" => "DiscountController@get"]);
            $router->post("discount/upsert", ["middleware" => [], "uses" => "DiscountController@upsert"]);
            $router->post("discount/delete", ["middleware" => [], "uses" => "DiscountController@delete"]);
        });
    });
});

require("dev.php");
