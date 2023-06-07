<?php
namespace App\Helpers;

use App\Models\ServiceLog;
use Illuminate\Support\Facades\Http;

class api
{
    public static function sendResponse(
        $reqId = 0,
        $httpCode = 200,
        $code = "000",
        $desc = null,
        $data = null,
        Array $resp = null, 
        $error = null,
        $pagination = null
    )
    {
        $rules = config("response.rules");
        $response = [
            "reqId" => $reqId,
            "statusCode" => strval($code),
            "statusDesc" => $desc ?? $rules[$code] ?? "Success",
        ];

        if($data) $response["data"] = $data;
        if($error) $response["error"] = $error;
        if($pagination) $response["pagination"] = $pagination;
        if($resp) $response = array_merge($response, $resp);

        return response()->json($response, $httpCode);
    }

    public static function switchResponse($response)
    {
        $httpStatus = array_key_exists("httpStatus", $response) ? $response["httpStatus"] : 200;
        $statusCode = array_key_exists("statusCode", $response) ? $response["statusCode"] : "000";

        $newResponse = [
            "reqId" => $response["reqId"],
            "statusCode" => $statusCode,
            "statusDesc" => $response["statusDesc"]
        ];

        if (array_key_exists("data", $response)) $newResponse["data"] = $response["data"];
        if (array_key_exists("error", $response)) $newResponse["error"] = $response["error"];
        if (array_key_exists("draw", $response)) $newResponse["draw"] = $response["draw"];
        if (array_key_exists("recordsTotal", $response)) $newResponse["recordsTotal"] = $response["recordsTotal"];
        if (array_key_exists("recordsFiltered", $response)) $newResponse["recordsFiltered"] = $response["recordsFiltered"];

        return response()->json($newResponse, 200);
    }

    public static function standartRequest($request, $endpoint = "", $service = "", $recorded = false)
    {
        $services = config("service");
        $endpoint = $services[$service]["url"] . $endpoint;
        $kunci = $services[$service]["sigkey"];

        $request->merge([
            "signature" => signature($request->all(), $kunci)
        ]);

        $response = Http::withHeaders($request->header())
            ->withOptions(["verify" => false])
            ->post($endpoint, $request->all())->json();

        if ($recorded) {
            $serviceLog = new ServiceLog();
            $serviceLog->method = $request->method();
            $serviceLog->service = $service;
            $serviceLog->req_data = json_encode($request->all());
            $serviceLog->res_data = json_encode($response);
            $serviceLog->other = json_encode(["endpoint" => $endpoint]);
            $serviceLog->save();
        }

        if (is_array($response)) {
            $response = array_merge($response, ["reqId" => $recorded ? $serviceLog->id : 0]);
        } else {
            $response["reqId"] = $recorded ? $serviceLog->id : null;
        }

        return $response;
    }

    public static function apiRequest($params, $method = "post", $service = "", $endpoint = "", $kunci = "", $recorded = false)
    {
        $request = request();
        $headers = [
            "Auth-Token" => $request->header("Auth-Token"),
        ];

        $response = Http::withHeaders($headers);

        $requestFile = array_form_files($params);
        if ($requestFile) {
            foreach ($requestFile as $key => $val) {
                $response = $response->attach($key, fopen(storage_path("app/" . $val), "r"));
            }
        }

        $requestNonFile = $requestFile ? array_form_data($params) : remove_uploaded_files($params);
        $requestNonFile["signature"] = signature($params, $kunci);

        $response = $response->withOptions(["verify" => false])
            ->{$method}($endpoint, $requestNonFile)->json();           

        if ($recorded) {
            $serviceLog = new ServiceLog();
            $serviceLog->method = $method;
            $serviceLog->service = $service;
            $serviceLog->req_data = json_encode($params);
            $serviceLog->res_data = json_encode($response);
            $serviceLog->other = json_encode(["endpoint" => $endpoint]);
            $serviceLog->save();
        }

        if (is_array($response)) {
            $response = array_merge($response, ["reqId" => $recorded ? $serviceLog->id : 0]);
        } else {
            $response["reqId"] = $recorded ? $serviceLog->id : null;
        }

        return $response;
    }
}