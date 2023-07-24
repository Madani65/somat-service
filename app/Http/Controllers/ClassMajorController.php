<?php

namespace App\Http\Controllers;

use App\Helpers\api;
use App\Http\Resources\ClassMajorResponse;
use App\Models\ClassMajor;
use App\Models\SchoolLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller;
use Throwable;

class ClassMajorController extends Controller
{
    public function upsert (Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.classMajorId" => "nullable",
            "data.name" => "required",
            "data.schoolLevelId" => "required|exists:school_levels,id",
            "data.description" => "nullable",
            "data.activeFlag" => "required|in:1,0",
        ], [
            "required" => "Field ini belum kamu isi",
            "max" => "Data yang kamu masukan melebih batas maksimum",
            "numeric" => "Data yang kamu isi tidak sesuai"
        ]);

        if ($validator->fails()) {
            return api::sendResponse(
                code: '105',
                desc: $validator->errors()
            );
        }

        Log::info("Start ClassMajorController->upsert()", ["request" => $request->all()] );

        try {
            $classMajorId = $request->input('data.classMajorId');
            $name = $request->input('data.name');
            $schoolLevelId = $request->input('data.schoolLevelId');
            $description = $request->input('data.description');
            $activeFlag = $request->input('data.activeFlag');

            if ($classMajorId) {
                $classMajor = ClassMajor::where("id", $classMajorId)->where("id", $schoolLevelId)->first();
                if (!$classMajor)
                    return api::sendResponse(code: '105', desc: "Data tingkat sekolah yang kamu masukan tidak sesuai.");
            } else {
                $classMajor = new ClassMajor;
            }

            $classMajor->name = $name;
            $classMajor->school_level_id = $schoolLevelId;
            $classMajor->description = $description;
            $classMajor->active_flag = $activeFlag;
            $classMajor->save();

            $result = new ClassMajorResponse($classMajor);
            $response = Api::sendResponse(data: $result);
            Log::info("End ClassMajorController->upsert()", ["response" => $response]);
            return $response;

        } catch (Throwable $t) {
            $message = "Error on ClassMajorController->upsert() | " . $t->getMessage();
            $response = Api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }

    public function get (Request $request)
    {
        Log::info("Start ClassMajorController->get()", ["request" => $request->all()]);

        try {
            $classMajor = ClassMajor::get();
            $result = ClassMajorResponse::collection($classMajor);

            $response = Api::sendResponse(data: $result);
            Log::info("End ClassMajorController->get()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on ClassMajorController->get() | " . $t->getMessage();
            $response = Api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }

    public function delete (Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.idClassMajor" => "required|exists:class_majors,id"
        ], [
            "required" => "Data ini belum kamu isi"
        ]);

        if ($validator->fails()){
            return api::sendResponse(
                code: '105',
                desc: $validator->errors()
            );
        }

        Log::info("Start ClassController->delete()", ["request" => $request->all()]);

        try {
            $idClassMajor = $request->input('data.idClassMajor');

            $classMajor = ClassMajor::find($idClassMajor);
            $classMajor->delete();

            $response = api::sendResponse(desc: "Data ini berhasil dihapus");
            Log::info("End ClassController->delete()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on ClassController->delete() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
        
    }
}
