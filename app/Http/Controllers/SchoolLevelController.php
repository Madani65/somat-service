<?php

namespace App\Http\Controllers;

use App\Http\Resources\SchoolLevelResponse;
use App\Models\SchoolLevel;
use App\Helpers\api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Routing\Controller;
use Throwable;

class SchoolLevelController extends Controller
{
    public function get (Request $request)
    {
        Log::info("Start SchoolLevelController->get()", ["request" => $request->all()]);
        try {
            $schoolLevel = SchoolLevel::get();
            $result = SchoolLevelResponse::collection($schoolLevel);

            $response = api::sendResponse(data: $result);
            Log::info("End SchoolLevelController->get()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on SchoolLevelController->get() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }
}
