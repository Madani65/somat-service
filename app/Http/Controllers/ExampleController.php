<?php

namespace App\Http\Controllers;

use App\Helpers\api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ExampleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.field" => "required",
        ], [
            "required" => "Field ini wajib kamu isi",
        ]);

        if ($validator->fails()) {
            return api::sendResponse(
                code: '105',
                error: $validator->errors()
            );
        }

        Log::info("Start ExampleController->index()", ["request" => $request->all()]);
        try {
            $result = [];
            $response = api::sendResponse(data: $result);
            Log::info("End ExampleController->index()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on ExampleController->index() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }
}
