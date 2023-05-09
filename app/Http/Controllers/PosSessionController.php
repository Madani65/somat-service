<?php

namespace App\Http\Controllers;

use App\Helpers\api;
use App\Http\Resources\PosSessionResponse;
use App\Http\Resources\TaxResponse;
use App\Models\MemberEntity;
use App\Models\PosSession;
use App\Models\PosSessionPayMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class PosSessionController extends Controller
{
    public function upsert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.idSession" => "nullable|exists:pos_sessions,id",
            "data.idEntity" => "required",
            "data.idEntityMap" => "required",
            "data.name" => "required|max:50",
            "data.notesFlag" => "required|in:0,1",
            "data.tablesFlag" => "required|in:0,1",
            "data.paymentMethods" => "required|array",
            "data.paymentMethods.*" => "required|exists:payment_methods,id",
            "data.casierPrintFlag" => "required|in:0,1",
            "data.kitchenPrintFlag" => "required|in:0,1",
            "data.customReceiptFlag" => "required|in:0,1",
            "data.customReceiptHeader" => "required_if:data.customReceiptFlag,1|max:30",
            "data.customReceiptFooter" => "required_if:data.customReceiptFlag,1|max:30",
            "data.autoprintBeforeFlag" => "required|in:0,1",
            "data.autoprintAfterFlag" => "required|in:0,1",
            "data.documents.image" => "nullable|mimes:jpeg,jpg,png,bmp|file|max:5120",
        ], [
            "required" => "Field ini wajib kamu isi",
        ]);

        if ($validator->fails()) {
            return api::sendResponse(
                code: '105',
                error: $validator->errors()
            );
        }

        Log::info("Start PosSessionController->upsert()", ["request" => $request->all()]);
        try {
            $idSession = $request->input("data.idSession");
            $idEntity = $request->input("data.idEntity");
            $idEntityMap = $request->input("data.idEntityMap");
            $name = $request->input("data.name");
            $notesFlag = $request->input("data.notesFlag", 0);
            $tablesFlag = $request->input("data.tablesFlag", 0);
            $casierPrintFlag = $request->input("data.casierPrintFlag", 0);
            $kitchenPrintFlag = $request->input("data.kitchenPrintFlag", 0);
            $customReceiptFlag = $request->input("data.customReceiptFlag", 0);
            $customReceiptHeader = $request->input("data.customReceiptHeader");
            $customReceiptFooter = $request->input("data.customReceiptFooter");
            $autoprintBeforeFlag = $request->input("data.autoprintBeforeFlag", 0);
            $autoprintAfterFlag = $request->input("data.autoprintAfterFlag", 0);
            $docs = $request->file("data.documents", []);
            $paymentMethods = $request->input("data.paymentMethods", []);

            if ($idSession) {
                $session = PosSession::where("id", $idSession)->where("id_entity", $idEntity)->first();
                if (!$session)
                    return api::sendResponse(code: '105', desc: "Kamu tidak memiliki akses mengubah data ini.");
            } else {
                $session = new PosSession();
                $session->id_entity = $idEntity;
            }

            MemberEntity::$idEntity = $idEntity;
            $session->id_entity_map = $idEntityMap;
            $session->name = $name;
            $session->notes_flag = $notesFlag;
            $session->tables_flag = $tablesFlag;
            $session->casier_print_flag = $casierPrintFlag;
            $session->kitchen_print_flag = $kitchenPrintFlag;
            $session->custom_receipt_flag = $customReceiptFlag;
            $session->custom_receipt_header = $customReceiptHeader;
            $session->custom_receipt_footer = $customReceiptFooter;
            $session->autoprint_before_flag = $autoprintBeforeFlag;
            $session->autoprint_after_flag = $autoprintAfterFlag;

            $files = files_upload($docs, "pos_session/entity_" . $idEntity);
            if ($files) {
                $documents = $session->documents ?: [];
                foreach ($files as $idx => $file) {
                    $documents[$idx] = $file;
                }
                $session->documents = $documents;
            }

            $session->save();

            $methods = PosSessionPayMethod::where("id_session", $session->id)->get();

            $methods->whereNotIn("id_payment_method", $paymentMethods)->map( function($row) {
                $row->delete();
            });

            $methods = $methods->whereIn("id_payment_method", $paymentMethods);
            $newMethods = collect($paymentMethods)->diff($methods->pluck("id_payment_method"));
            foreach($newMethods as $idPaymentMethods) {
                $method = new PosSessionPayMethod();
                $method->id_session = $session->id;
                $method->id_payment_method = $idPaymentMethods;
                $method->save();
                $methods->push($method);
            }

            $session->payment_methods_pivot = $methods;

            $result = new PosSessionResponse($session);
            $response = api::sendResponse(data: $result);
            Log::info("End PosSessionController->upsert()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on PosSessionController->upsert() | " . $t->getMessage();
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

        Log::info("Start PosSessionController->get()", ["request" => $request->all()]);
        try {
            $idEntity = $request->input("data.idEntity");

            MemberEntity::$idEntity = $idEntity;
            $session = PosSession::where("id_entity", $idEntity);
            $session = $session->get();

            $result = PosSessionResponse::collection($session);
            $response = api::sendResponse(data: $result);
            Log::info("End PosSessionController->get()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on PosSessionController->get() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.idSession" => "required|exists:pos_sessions,id",
            "data.idEntity" => "required",
        ], []);

        if ($validator->fails()) {
            return api::sendResponse(
                code: '105',
                error: $validator->errors()
            );
        }

        Log::info("Start PosSessionController->delete()", ["request" => $request->all()]);
        try {
            $idSession = $request->input("data.idSession");
            $idEntity = $request->input("data.idEntity");

            $session = PosSession::where("id", $idSession)->where("id_entity", $idEntity)->first();
            if (!$session)
                return api::sendResponse(code: '105', desc: "Kamu tidak memiliki akses menghapus data ini.");
            $session->delete();

            $response = api::sendResponse(desc: 'Data berhasil di hapus');
            Log::info("End PosSessionController->delete()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on PosSessionController->delete() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }
}
