<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            $token = $request->header("Auth-Token");
            if ($token) {
                $data["reqType"] = "reqAuthToken";
                $data["reqTime"] = date('YmdHis');

                $kunci = config("signature.key");
                $signature = signature($data, $kunci);
                $data["signature"] = $signature;

                $endpoint = config("service.member.url") . "/auth-token";
                $headers = ["Auth-Token" => $token];
                $response = Http::withHeaders($headers)
                    ->withOptions(["verify" => false])->post($endpoint, $data)->json();

                if ($response["statusCode"] == "000") {
                    return $response["data"];
                }
            }
        });
    }
}
