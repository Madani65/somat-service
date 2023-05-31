<?php

namespace App\Http\Controllers;

use App\Helpers\api;
use App\Http\Resources\PaymentMethodResponse;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class PaymentMethodController extends Controller
{
    public function upsert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.idPayMethod" => "nullable|exists:payment_methods,id",
            "data.idEntity" => "required",
            "data.name" => "required|max:20",
            "data.type" => "required|in:1,2",
            "data.value" => "required|numeric|min:0",
        ], [
            "required" => "Field ini wajib kamu isi",
        ]);

        if ($validator->fails()) {
            return api::sendResponse(
                code: '105',
                error: $validator->errors()
            );
        }

        Log::info("Start PaymentMethodController->upsert()", ["request" => $request->all()]);
        try {
            $idPayMethod = $request->input("data.idPayMethod");
            $idEntity = $request->input("data.idEntity");
            $name = $request->input("data.name");
            $type = $request->input("data.type");
            $value = $request->input("data.value");

            if ($idPayMethod) {
                $payMethod = PaymentMethod::where("id", $idPayMethod)->where("id_entity", $idEntity)->first();
                if (!$payMethod)
                    return api::sendResponse(code: '105', desc: "Kamu tidak memiliki akses mengubah data ini.");
            } else {
                $payMethod = new PaymentMethod();
                $payMethod->id_entity = $idEntity;
            }

            $payMethod->name = $name;
            $payMethod->type = $type;
            $payMethod->value = $value;
            $payMethod->save();

            $result = new PaymentMethodResponse($payMethod);
            $response = api::sendResponse(data: $result);
            Log::info("End PaymentMethodController->upsert()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on PaymentMethodController->upsert() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }

    public function get(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.idEntity" => "required",
        ], [
            "required" => "Field ini wajib kamu isi",
        ]);

        if ($validator->fails()) {
            return api::sendResponse(
                code: '105',
                error: $validator->errors()
            );
        }

        Log::info("Start PaymentMethodController->get()", ["request" => $request->all()]);
        try {
            $idEntity = $request->input("data.idEntity");
            $keyPayMethodName = $request->input("keywords.payMethodName");

            $payMethod = PaymentMethod::where("id_entity", $idEntity);

            if ($keyPayMethodName)
                $payMethod->where("name", "like", "%". $keyPayMethodName ."%");

            $payMethod = $payMethod->get();

            $result = PaymentMethodResponse::collection($payMethod);
            $response = api::sendResponse(data: $result);
            Log::info("End PaymentMethodController->get()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on PaymentMethodController->get() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.idPayMethod" => "required|exists:payment_methods,id",
            "data.idEntity" => "required",
        ], []);

        if ($validator->fails()) {
            return api::sendResponse(
                code: '105',
                error: $validator->errors()
            );
        }

        Log::info("Start PaymentMethodController->delete()", ["request" => $request->all()]);
        try {
            $idPayMethod = $request->input("data.idPayMethod");
            $idEntity = $request->input("data.idEntity");

            $payMethod = PaymentMethod::where("id", $idPayMethod)->where("id_entity", $idEntity)->first();
            if (!$payMethod)
                return api::sendResponse(code: '105', desc: "Kamu tidak memiliki akses menghapus data ini.");
            $payMethod->delete();

            $response = api::sendResponse(desc: 'Data berhasil di hapus');
            Log::info("End PaymentMethodController->delete()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on PaymentMethodController->delete() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }

    public function setdefault(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.idPayMethod" => "required|exists:payment_methods,id",
            "data.idEntity" => "required",
        ], []);

        if ($validator->fails()) {
            return api::sendResponse(
                code: '105',
                error: $validator->errors()
            );
        }

        Log::info("Start PaymentMethodController->setdefault()", ["request" => $request->all()]);
        try {
            $idPayMethod = $request->input("data.idPayMethod");
            $idEntity = $request->input("data.idEntity");

            $payMethod = PaymentMethod::where("id", $idPayMethod)->where("id_entity", $idEntity)->first();
            if (!$payMethod)
                return api::sendResponse(code: '105', desc: "Kamu tidak memiliki akses mengubah data ini.");

            if(!$payMethod->is_default)
                PaymentMethod::where("id_entity", $idEntity)->update(["is_default" => 0]);

            $payMethod->is_default = !$payMethod->is_default;                
            $payMethod->save();

            $response = api::sendResponse(desc: 'Data berhasil di update');
            Log::info("End PaymentMethodController->setdefault()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on PaymentMethodController->setdefault() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }
}
