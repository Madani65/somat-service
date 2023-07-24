<?php

namespace App\Http\Middleware;

use App\Helpers\api;
use Closure;
use Illuminate\Support\Facades\Log;

class RequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Pre-Middleware Action
        $reqType = $request->input('reqType', false);
        $reqTime = $request->input('reqTime', false);

        if (!$reqType) return api::sendResponse(httpCode: 403, code: 102, desc: "reqType is required");
        if (!$reqTime) return api::sendResponse(httpCode: 403, code: 102, desc: "reqTime is required");

        $requestList = config("request");

        if (!array_key_exists($reqType, $requestList)) return api::sendResponse(httpCode: 403, code: 102, desc: "reqType is invalid");

        $expTime = env("SERVICE_TIME_EXP", 0);
        $reqTime = strtotime(dateParser($reqTime));
        $now = time();

        if ($expTime && abs($now - $reqTime) > $expTime) return api::sendResponse(httpCode: 403, code: 102, desc: "reqTime is invalid");

        $response = $next($request);

        // Post-Middleware Action

        return $response;
    }
}
