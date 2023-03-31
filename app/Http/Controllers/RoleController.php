<?php

namespace App\Http\Controllers;

use App\Helpers\api;
use App\Http\Resources\RoleResponse;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class RoleController extends Controller
{
    public function get(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.withOwner" => "nullable|boolean",
        ], []);

        if ($validator->fails()) {
            return api::sendResponse(
                code: '105',
                error: $validator->errors()
            );
        }

        Log::info("Start RoleController->get()", ["request" => $request->all()]);
        try {
            $withOwner = $request->input("data.withOwner", false);

            $cur = Role::get();

            if(!$withOwner) 
                $cur = $cur->where("id", ">", 1);

            $result = RoleResponse::collection($cur);
            $response = api::sendResponse(data: $result);
            Log::info("End RoleController->get()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on RoleController->get() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }
}
