<?php

namespace App\Http\Controllers;

use App\Helpers\api;
use App\Http\Resources\PartnerTypeResponse;
use App\Models\PartnerType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class PartnerTypeController extends Controller
{
    public function get(Request $request)
    {
        Log::info("Start PartnerTypeController->get()", ["request" => $request->all()]);
        try {
            $type = PartnerType::get();
            $result = PartnerTypeResponse::collection($type);
            $response = api::sendResponse(data: $result);
            Log::info("End PartnerTypeController->get()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on PartnerTypeController->get() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }
}
