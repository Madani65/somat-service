<?php

namespace App\Http\Controllers;

use App\Helpers\api;
use App\Http\Resources\UnitOfMeasureResponse;
use App\Models\UnitOfMeasure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class UoMController extends Controller
{
    public function get(Request $request)
    {
        Log::info("Start UoMController->get()", ["request" => $request->all()]);
        try {
            $uom = UnitOfMeasure::orderBy("code")->get();
            $result = UnitOfMeasureResponse::collection($uom);
            $response = api::sendResponse(data: $result);
            Log::info("End UoMController->get()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on UoMController->get() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }
}
