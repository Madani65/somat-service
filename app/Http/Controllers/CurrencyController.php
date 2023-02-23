<?php

namespace App\Http\Controllers;

use App\Helpers\api;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class CurrencyController extends Controller
{
    public function get(Request $request)
    {
        Log::info("Start CurrencyController->get()", ["request" => $request->all()]);
        try {
            $cur = Currency::orderBy("code")->get();
            $result = $cur->transform(function($row){
                return [
                    "code" => $row->code,
                    "name" => $row->name,
                ];
            });
            $response = api::sendResponse(data: $result);
            Log::info("End CurrencyController->get()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on CurrencyController->get() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }

    public function upsert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.code" => "required|max:3",
            "data.name" => "required|max:30",
        ], [
            "data.email.unique" => "Email kamu sudah terdaftar",
        ]);

        if ($validator->fails()) {
            return api::sendResponse(
                code: '105',
                error: $validator->errors()
            );
        }

        Log::info("Start CurrencyController->upsert()", ["request" => $request->all()]);
        try {
            $code = $request->input("data.code");
            $name = $request->input("data.name");
            
            $cur = Currency::where("code", $code)->first();

            if(!$cur) $cur = new Currency();

            $cur->code = $code;
            $cur->name = $name;
            $cur->save();

            $result = [
                "code" => $code,
                "name" => $name
            ];
            $response = api::sendResponse(data: $result);
            Log::info("End CurrencyController->upsert()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on CurrencyController->upsert() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }

    public function delete(Request $request)
    {
        Log::info("Start CurrencyController->delete()", ["request" => $request->all()]);
        try {
            $cur = Currency::where("code", $request->input("data.code"))->first();
            if(!$cur)
                return api::sendResponse(code: '108');
            
            $cur->delete();

            $response = api::sendResponse();
            Log::info("End CurrencyController->delete()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on CurrencyController->delete() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }
}
