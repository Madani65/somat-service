<?php

namespace App\Http\Controllers;

use App\Http\Resources\SchoolYearResponse;
use App\Models\SchoolYear;
use App\Helpers\api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Routing\Controller;
use Throwable;

class SchoolYearController extends Controller
{
    public function get (Request $request)
    {
        Log::info("Start SchoolYearController->get()", ["request" => $request->all()]);
        try {
            $schoolLevel = SchoolYear::get();
            $result = SchoolYearResponse::collection($schoolLevel);

            $response = api::sendResponse(data: $result);
            Log::info("End SchoolYearController->get()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on SchoolYearController->get() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }
}
