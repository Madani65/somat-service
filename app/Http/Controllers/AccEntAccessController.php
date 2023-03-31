<?php

namespace App\Http\Controllers;

use App\Helpers\api;
use App\Models\AccountEntityAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AccEntAccessController extends Controller
{
    public function upsert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.idAccount" => "required",
            "data.idEntity" => "required",
            "data.idEntityAccesses" => "required|array",
        ], [
            "required" => "Field ini wajib kamu isi",
        ]);

        if ($validator->fails()) {
            return api::sendResponse(
                code: '105',
                error: $validator->errors()
            );
        }

        Log::info("Start AccEntAccessController->upsert()", ["request" => $request->all()]);
        try {
            $idAccount = $request->input("data.idAccount");
            $idEntity = $request->input("data.idEntity");
            $idEntityAccesses = $request->input("data.idEntityAccesses");

            $accesses = AccountEntityAccess::where("id_entity_parent", $idEntity)->where("id_account", $idAccount)->get();

            $accesses->whereNotIn("id_entity", $idEntityAccesses)->map( function($row) {
                $row->delete();
            });

            $accesses = $accesses->whereIn("id_entity", $idEntityAccesses);
            $newAccess = collect($idEntityAccesses)->diff($accesses->pluck("id_entity"));
            foreach($newAccess as $idEntityAccess) {
                $access = new AccountEntityAccess();
                $access->id_account = $idAccount;
                $access->id_entity_parent = $idEntity;
                $access->id_entity = $idEntityAccess;
                $access->save();
                $accesses->push($access);
            }

            $result = $accesses->transform( function($row) {
                return [
                    "idAccount" => $row->id_account,
                    "idEntityParent" => $row->id_entity_parent,
                    "idEntityAccess" => $row->id_entity,
                ];
            });
            $response = api::sendResponse(data: $result);
            Log::info("End AccEntAccessController->upsert()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on AccEntAccessController->upsert() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }

    public function get(Request $request)
	{
		$validator = Validator::make($request->all(), [
            "data.idEntity" => "required",
            "data.idAccount" => "nullable",
        ], [
            "required" => "Field ini wajib kamu isi",
        ]);

        if ($validator->fails()) {
            return api::sendResponse(
                code: '105',
                error: $validator->errors()
            );
        }

        Log::info("Start AccEntAccessController->get()", ["request" => $request->all()]);
        try {
			$idEntity = $request->input("data.idEntity");
			$idAccount = $request->input("data.idAccount", false);

			$accesses = AccountEntityAccess::where("id_entity_parent", $idEntity);

			if($idAccount)
				if(is_array($idAccount))
					$accesses->whereIn("id_account", $idAccount);
				else
					$accesses->where("id_account", $idAccount);

            $accesses = $accesses->get();

            $result = $accesses->transform( function($row) {
                return [
                    "idAccount" => $row->id_account,
                    "idEntityParent" => $row->id_entity_parent,
                    "idEntityAccess" => $row->id_entity,
                ];
            });
            $response = api::sendResponse(data: $result);
            Log::info("End AccEntAccessController->get()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on AccEntAccessController->get() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
	}

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.idEntity" => "required",
            "data.idAccount" => "required"
        ], []);

        if ($validator->fails()) {
            return api::sendResponse(
                code: '105',
                error: $validator->errors()
            );
        }

        Log::info("Start AccEntAccessController->delete()", ["request" => $request->all()]);
        try {
            $idEntity = $request->input("data.idEntity");
            $idAccount = $request->input("data.idAccount");

            AccountEntityAccess::where("id_entity_parent", $idEntity)->where("id_account", $idAccount)->delete();

            $response = api::sendResponse(desc: 'Data berhasil di hapus');
            Log::info("End AccEntAccessController->delete()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on AccEntAccessController->delete() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }
}
