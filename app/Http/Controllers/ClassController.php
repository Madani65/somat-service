<?php

namespace App\Http\Controllers;

use App\Helpers\api;
use App\Http\Resources\ClassResponse;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller;
use Throwable;

class ClassController extends Controller
{
    public function upsert (Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.classId" => "nullable",
            "data.className" => "required",
            "data.classLevel" => "nullable",
            "data.classMajorId" => "required|exists:class_majors,id",
            "data.schoolYearId" => "required|exists:school_years,id",
            "data.classCategoryId" => "required|exists:lesson_categories,id"
        ], [
            "required" => "Field ini belum kamu isi",
        ]);

        if ($validator->fails()) {
            return api::sendResponse(
                code: '105',
                desc: $validator->errors()
            );
        }

        Log::info("Start ClassController->upsert()", ["request" => $request->all()]);

        try {
            $classId = $request->input('data.classId');
            $className = $request->input('data.className');
            $classLevel = $request->input('data.classLevel');
            $classMajorId = $request->input('data.classMajorId');
            $schoolYearId = $request->input('data.schoolYearId');
            $classCategoryId = $request->input('data.classCategoryId');

            if ($classId) {
                $class = Classes::where("id", $classId)->first();
            } else {
                $class = new Classes;
            }   

            $class->class_name = $className;
            $class->class_level = $classLevel;
            $class->class_major_id = $classMajorId;
            $class->school_year_id = $schoolYearId;
            $class->class_category_id = $classCategoryId;
            $class->save();

            $result = new ClassResponse($class);
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
        Log::info("Start ClassController->get()", ["request" => $request->all()]);

        try {
            $class = Classes::get();
            $result = ClassResponse::collection($class);

            $response = Api::sendResponse(data: $result);
            Log::info("End ClassController->get()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on ClassController->get() | " . $t->getMessage();
            $response = Api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }

    public function delete (Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.idClass" => "required|exists:classes,id"
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
            $idClass = $request->input('data.idClass');

            $class = Classes::find($idClass);
            $class->delete();

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
