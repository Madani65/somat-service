<?php

namespace App\Http\Controllers;

use App\Helpers\api;
use App\Http\Resources\ProductCategoryResponse;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ProductCategoryController extends Controller
{
    public function upsert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.idProductCategory" => "nullable|exists:product_categories,id",
            "data.name" => "required|max:50",
            "data.idEntity" => "required",
            "data.idParent" => "nullable|exists:product_categories,id",
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

        Log::info("Start ProductCategoryController->upsert()", ["request" => $request->all()]);
        try {
            $idProductCategory = $request->input("data.idProductCategory", false);
            $name = $request->input("data.name");
            $idEntity = $request->input("data.idEntity");
            $idParent = $request->input("data.idParent", null);
            $docs = $request->file("data.documents", []);

            if ($idProductCategory) {
                $cat = ProductCategory::where("id", $idProductCategory)->where("id_entity", $idEntity)->first();
                if (!$cat)
                    return api::sendResponse(code: '105', desc: "Kamu tidak memiliki akses mengubah data ini.");
            } else {
                $cat = new ProductCategory();
            }

            if($idParent) {
                $parent = ProductCategory::where("id", $idParent)->where("id_entity", $idEntity)->whereNull("id_parent")->first();
                if (!$parent)
                    return api::sendResponse(code: '105', desc: "Data induk kategori yang kamu masukan tidak sesuai.");
            }

            $cat->name = $name;
            $cat->id_parent = $idParent ?: null;
            $cat->id_entity = $idEntity;

            $files = files_upload($docs, "product-category/entity_" . $idEntity);
            if ($files) {
                $documents = $cat->documents ?: [];
                foreach ($files as $idx => $file) {
                    $documents[$idx] = $file;
                }
                $cat->documents = $documents;
            }

            $cat->save();

            $result = new ProductCategoryResponse($cat);
            $response = api::sendResponse(data: $result);
            Log::info("End ProductCategoryController->upsert()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on ProductCategoryController->upsert() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }

    public function get(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.idEntity" => "required",
            "data.idParent" => "nullable|exists:product_categories,id",
            "data.isParent" => "nullable|boolean"
        ], []);

        if ($validator->fails()) {
            return api::sendResponse(
                code: '105',
                error: $validator->errors()
            );
        }

        Log::info("Start ProductCategoryController->get()", ["request" => $request->all()]);
        try {
            $idEntity = $request->input("data.idEntity");
            $idParent = $request->input("data.idParent", false);
            $isParent = $request->input("data.isParent", false);

            $cat = ProductCategory::with("parent", "child")->where("id_entity", $idEntity);

            if($isParent)
                $cat->whereNull("id_parent");
            
            if($idParent)
                $cat->where("id_parent", $idParent);

            $cat = $cat->get();

            $result = ProductCategoryResponse::collection($cat);
            $response = api::sendResponse(data: $result);
            Log::info("End ProductCategoryController->get()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on ProductCategoryController->get() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.idProductCategory" => "required|exists:product_categories,id",
            "data.idEntity" => "required",
        ], []);

        if ($validator->fails()) {
            return api::sendResponse(
                code: '105',
                error: $validator->errors()
            );
        }

        Log::info("Start ProductCategoryController->delete()", ["request" => $request->all()]);
        try {
            $idProductCategory = $request->input("data.idProductCategory");
            $idEntity = $request->input("data.idEntity");

            $cat = ProductCategory::where("id", $idProductCategory)->where("id_entity", $idEntity)->first();
            if (!$cat)
                return api::sendResponse(code: '105', desc: "Kamu tidak memiliki akses menghapus data ini.");
            $cat->delete();

            $response = api::sendResponse(desc: 'Data berhasil di hapus');
            Log::info("End ProductCategoryController->delete()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on ProductCategoryController->delete() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }
}
