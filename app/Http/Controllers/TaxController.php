<?php

namespace App\Http\Controllers;

use App\Helpers\api;
use App\Http\Resources\TaxResponse;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class TaxController extends Controller
{
    public function upsert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.idTax" => "nullable|exists:taxes,id",
            "data.idEntity" => "required",
            "data.name" => "required|max:20",
            "data.type" => "required|in:1,2",
            "data.value" => "required|numeric|min:0",
            "data.isAutoadd" => "required|in:0,1",
        ], [
            "required" => "Field ini wajib kamu isi",
        ]);

        if ($validator->fails()) {
            return api::sendResponse(
                code: '105',
                error: $validator->errors()
            );
        }

        Log::info("Start TaxController->upsert()", ["request" => $request->all()]);
        try {
            $idTax = $request->input("data.idTax");
            $idEntity = $request->input("data.idEntity");
            $name = $request->input("data.name");
            $type = $request->input("data.type");
            $value = $request->input("data.value");
            $isAutoadd = $request->input("data.isAutoadd");

            if ($idTax) {
                $tax = Tax::where("id", $idTax)->where("id_entity", $idEntity)->first();
                if (!$tax)
                    return api::sendResponse(code: '105', desc: "Kamu tidak memiliki akses mengubah data ini.");
            } else {
                $tax = new Tax();
                $tax->id_entity = $idEntity;
            }

            $tax->name = $name;
            $tax->type = $type;
            $tax->value = $value;
            $tax->is_autoadd = $isAutoadd;
            $tax->save();

            $result = new TaxResponse($tax);
            $response = api::sendResponse(data: $result);
            Log::info("End TaxController->upsert()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on TaxController->upsert() | " . $t->getMessage();
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

        Log::info("Start TaxController->get()", ["request" => $request->all()]);
        try {
            $idEntity = $request->input("data.idEntity");
            $keyTaxName = $request->input("keywords.taxName");
            $pagination = $request->input("pagination", false);

            $tax = Tax::where("id_entity", $idEntity);

            if ($keyTaxName)
                $tax->where("name", "like", "%". $keyTaxName ."%");

            if (!$pagination)
                $tax = $tax->get();
            else{
                $request->replace(["page" => $pagination["currentPage"]]);
                $tax = $tax->paginate($pagination["perPage"]);
                $pagination = [
                    "currentPage" => $tax->currentPage(),
                    "length" => $tax->lastPage(),
                    "perPage" => $tax->perPage(),
                    "rowTotal" => $tax->total()
                ];
            }

            $result = TaxResponse::collection($tax);
            $response = api::sendResponse(data: $result, pagination: $pagination);
            Log::info("End TaxController->get()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on TaxController->get() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.idTax" => "required|exists:taxes,id",
            "data.idEntity" => "required",
        ], []);

        if ($validator->fails()) {
            return api::sendResponse(
                code: '105',
                error: $validator->errors()
            );
        }

        Log::info("Start TaxController->delete()", ["request" => $request->all()]);
        try {
            $idTax = $request->input("data.idTax");
            $idEntity = $request->input("data.idEntity");

            $tax = Tax::where("id", $idTax)->where("id_entity", $idEntity)->first();
            if (!$tax)
                return api::sendResponse(code: '105', desc: "Kamu tidak memiliki akses menghapus data ini.");
            $tax->delete();

            $response = api::sendResponse(desc: 'Data berhasil di hapus');
            Log::info("End TaxController->delete()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on TaxController->delete() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }

    public function setautoadd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.idTax" => "required|exists:taxes,id",
            "data.idEntity" => "required",
        ], []);

        if ($validator->fails()) {
            return api::sendResponse(
                code: '105',
                error: $validator->errors()
            );
        }

        Log::info("Start TaxController->setautoadd()", ["request" => $request->all()]);
        try {
            $idTax = $request->input("data.idTax");
            $idEntity = $request->input("data.idEntity");

            $tax = Tax::where("id", $idTax)->where("id_entity", $idEntity)->first();
            if (!$tax)
                return api::sendResponse(code: '105', desc: "Kamu tidak memiliki akses mengubah data ini.");

            $tax->is_autoadd = !$tax->is_autoadd;                
            $tax->save();

            $response = api::sendResponse(desc: 'Data berhasil di update');
            Log::info("End TaxController->setautoadd()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on TaxController->setautoadd() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }
}
