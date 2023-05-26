<?php

namespace App\Http\Controllers;

use App\Helpers\api;
use App\Http\Resources\ProductResponse;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ProductController extends Controller
{
    public function upsert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.idEntity" => "required",
            "data.idProduct" => "nullable|exists:products,id",
            "data.name" => "required|max:50",
            "data.idCategory" => "nullable|exists:product_categories,id",
            "data.isManufacture" => "required|boolean",
            "data.isPublish" => "required|boolean",
            "data.initPrice" => "required|numeric|min:0|not_in:0",
            "data.price" => "required|numeric|min:0|gt:data.initPrice",
            "data.sku" => "nullable|max:50",
            "data.uom" => "required|exists:unit_of_measures,code",
            "data.measureConv" => "required|numeric",
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

        Log::info("Start ProductController->upsert()", ["request" => $request->all()]);
        try {
            $idEntity = $request->input("data.idEntity");
            $idProduct = $request->input("data.idProduct", false);
            $name = $request->input("data.name");
            $idCategory = $request->input("data.idCategory");
            $isManufacture = $request->input("data.isManufacture");
            $isPublish = $request->input("data.isPublish");
            $price = $request->input("data.price");
            $initPrice = $request->input("data.initPrice");
            $sku = $request->input("data.sku");
            $uom = $request->input("data.uom");
            $measureConv = $request->input("data.measureConv");
            $docs = $request->file("data.documents", []);

            if ($idProduct) {
                $product = Product::where("id", $idProduct)->where("id_entity", $idEntity)->first();
                if (!$product)
                    return api::sendResponse(code: '105', desc: "Kamu tidak memiliki akses mengubah data ini.");
            } else {
                $product = new Product();
                $product->id_entity = $idEntity;
            }

            if ($idCategory) {
                $cat = ProductCategory::where("id", $idCategory)->where("id_entity", $idEntity)->first();
                if (!$cat)
                    return api::sendResponse(code: '105', desc: "Kategori produk yang kamu masukan tidak sesuai.");
            }

            $product->name = $name;
            $product->id_category = $idCategory;
            $product->is_manufacture = $isManufacture;
            $product->is_publish = $isPublish;
            $product->price = $price;
            $product->init_price = $initPrice;
            $product->sku = $sku;
            $product->uom = $uom;
            $product->measure_conv = $measureConv;

            $files = files_upload($docs, "product/entity_" . $idEntity);
            if ($files) {
                $documents = $product->documents ?: [];
                foreach ($files as $idx => $file) {
                    $documents[$idx] = $file;
                }
                $product->documents = $documents;
            }

            $product->save();

            $result = new ProductResponse($product);
            $response = api::sendResponse(data: $result);
            Log::info("End ProductController->upsert()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on ProductController->upsert() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }

    public function get(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.idEntity" => "required",
            "data.idCategory" => "nullable|exists:product_categories,id",
        ], [
            "required" => "Field ini wajib kamu isi",
        ]);

        if ($validator->fails()) {
            return api::sendResponse(
                code: '105',
                error: $validator->errors()
            );
        }

        Log::info("Start ProductController->get()", ["request" => $request->all()]);
        try {
            $idEntity = $request->input("data.idEntity");
            $idCategory = $request->input("data.idCategory", false);

            $product = Product::with("category", "unit_of_measure")->where("id_entity", $idEntity);

            if($idCategory)
                $product->where("id_category", $idCategory);

            $product = $product->get();

            $result = ProductResponse::collection($product);
            $response = api::sendResponse(data: $result);
            Log::info("End ProductController->get()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on ProductController->get() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.idProduct" => "required|exists:products,id",
            "data.idEntity" => "required",
        ], []);

        if ($validator->fails()) {
            return api::sendResponse(
                code: '105',
                error: $validator->errors()
            );
        }

        Log::info("Start ProductController->delete()", ["request" => $request->all()]);
        try {
            $idProduct = $request->input("data.idProduct");
            $idEntity = $request->input("data.idEntity");

            $product = Product::where("id", $idProduct)->where("id_entity", $idEntity)->first();
            if (!$product)
                return api::sendResponse(code: '105', desc: "Kamu tidak memiliki akses menghapus data ini.");
            $product->delete();

            $response = api::sendResponse(desc: 'Data berhasil di hapus');
            Log::info("End ProductController->delete()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on ProductController->delete() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }
}
