<?php

namespace App\Http\Controllers;

use App\Helpers\api;
use App\Http\Resources\AccountRoleResponse;
use App\Models\AccountRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AccountRoleController extends Controller
{
    public function upsert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.idEntity" => "required",
            "data.idAccount" => "required",
            "data.idRoles" => "array",
            "data.idRoles.*" => "required|exists:roles,id"
        ], [
            "required" => "Field ini wajib kamu isi",
        ]);

        if ($validator->fails()) {
            return api::sendResponse(
                code: '105',
                error: $validator->errors()
            );
        }

        Log::info("Start AccountRoleController->upsert()", ["request" => $request->all()]);
        try {
            $idEntity = $request->input("data.idEntity");
            $idAccount = $request->input("data.idAccount");
            $idRoles = $request->input("data.idRoles");

            $roles = AccountRole::where("id_entity", $idEntity)->where("id_account", $idAccount)->get();

            $roles->whereNotIn("id_role", $idRoles)->map( function($row) {
                $row->delete();
            });

            $roles = $roles->whereIn("id_role", $idRoles);
            $newRoles = collect($idRoles)->diff($roles->pluck("id_role"));
            foreach($newRoles as $idRole) {
                $role = new AccountRole();
                $role->id_entity = $idEntity;
                $role->id_account = $idAccount;
                $role->id_role = $idRole;
                $role->save();
                $roles->push($role);
            }

            $result = AccountRoleResponse::collection($roles);
            $response = api::sendResponse(data: $result);
            Log::info("End AccountRoleController->upsert()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on AccountRoleController->upsert() | " . $t->getMessage();
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

        Log::info("Start AccountRoleController->get()", ["request" => $request->all()]);
        try {
			$idEntity = $request->input("data.idEntity");
			$idAccount = $request->input("data.idAccount", false);

			$roles = AccountRole::with("role")->where("id_entity", $idEntity);

			if($idAccount)
				if(is_array($idAccount))
					$roles->whereIn("id_account", $idAccount);
				else
					$roles->where("id_account", $idAccount);

            $result = AccountRoleResponse::collection($roles->get());
            $response = api::sendResponse(data: $result);
            Log::info("End AccountRoleController->get()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on AccountRoleController->get() | " . $t->getMessage();
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

        Log::info("Start AccountRoleController->delete()", ["request" => $request->all()]);
        try {
            $idEntity = $request->input("data.idEntity");
            $idAccount = $request->input("data.idAccount");

            AccountRole::where("id_entity", $idEntity)->where("id_account", $idAccount)->delete();

            $response = api::sendResponse(desc: 'Data berhasil di hapus');
            Log::info("End AccountRoleController->delete()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on AccountRoleController->delete() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }
}
