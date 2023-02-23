<?php

namespace App\Http\Middleware;

use App\Helpers\api;
use Closure;

class SignatureMiddleware
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
        
        if (!$request->input('signature', false)) return api::sendResponse(httpCode: 403, code: 101, desc: "Signature is required!");

        $kunci = config("signature.key");
        $signature = signature($request->all(), $kunci);
        if ($request->input('signature') != $signature) {
            return api::sendResponse(httpCode: 403, code: 101, desc: "Your signature is invalid!");
        }

        $response = $next($request);

        // Post-Middleware Action

        return $response;
    }
}