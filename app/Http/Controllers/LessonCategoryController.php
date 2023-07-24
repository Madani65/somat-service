<?php

namespace App\Http\Controllers;

use App\Http\Resources\LessonCategoryResponse;
use App\Models\LessonCategory;
use Illuminate\Http\Request;
use App\Helpers\api;
use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Routing\Controller;
use Throwable;

class LessonCategoryController extends Controller
{
    public function get (Request $request)
    {
        Log::info("Start LessonCategoryController->get()", ["request" => $request->all()]);
        try {
            $schoolLevel = LessonCategory::get();
            $result = LessonCategoryResponse::collection($schoolLevel);

            $response = api::sendResponse(data: $result);
            Log::info("End LessonCategoryController->get()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on LessonCategoryController->get() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }
}
