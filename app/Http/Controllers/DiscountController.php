<?php

namespace App\Http\Controllers;

use App\Helpers\api;
use App\Http\Resources\DiscountResponse;
use App\Models\Discount;
use App\Models\ProductDiscount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class DiscountController extends Controller
{
    public function upsert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.idDiscount" => "nullable|exists:discounts,id",
            "data.idEntity" => "required",
            "data.name" => "required|max:30",
            "data.startDate" => "required|date_format:Y-m-d",
            "data.endDate" => "nullable|date_format:Y-m-d",
            "data.type" => "required|in:1,2",
            "data.value" => "required|numeric|min:0",
            "data.products" => "required|array",
            "data.products.*" => "exists:products,id",
        ], [
            "required" => "Field ini wajib kamu isi",
        ]);

        if ($validator->fails()) {
            return api::sendResponse(
                code: '105',
                error: $validator->errors()
            );
        }

        Log::info("Start DiscountController->upsert()", ["request" => $request->all()]);
        try {
            $idDiscount = $request->input("data.idDiscount");
            $idEntity = $request->input("data.idEntity");
            $name = $request->input("data.name");
            $startDate = $request->input("data.startDate");
            $endDate = $request->input("data.endDate");
            $type = $request->input("data.type");
            $value = $request->input("data.value");
            $products = $request->input("data.products");

            if ($idDiscount) {
                $disc = Discount::where("id", $idDiscount)->where("id_entity", $idEntity)->first();
                if (!$disc)
                    return api::sendResponse(code: '105', desc: "Kamu tidak memiliki akses mengubah data ini.");
            } else {
                $disc = new Discount();
                $disc->id_entity = $idEntity;
            }

            $disc->name = $name;
            $disc->start_date = $startDate;
            if ($endDate)
                $disc->end_date = $endDate;
            $disc->type = $type;
            $disc->value = $value;
            $disc->save();

            $prodMaps = ProductDiscount::where("id_discount", $disc->id)->get();

            $prodMaps->whereNotIn("id_product", $products)->map(function ($row) {
                $row->delete();
            });

            $prodMaps = $prodMaps->whereIn("id_product", $products);
            $newProdMaps = collect($products)->diff($prodMaps->pluck("id_product"));
            foreach ($newProdMaps as $idProdMap) {
                $prodMap = new ProductDiscount();
                $prodMap->id_discount = $disc->id;
                $prodMap->id_product = $idProdMap;
                $prodMap->save();
                $prodMaps->push($prodMap);
            }

            $disc->products_pivot = $prodMaps;

            $result = new DiscountResponse($disc);
            $response = api::sendResponse(data: $result);
            Log::info("End DiscountController->upsert()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on DiscountController->upsert() | " . $t->getMessage();
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

        Log::info("Start DiscountController->get()", ["request" => $request->all()]);
        try {
            $idEntity = $request->input("data.idEntity");
            $keyDiscountName = $request->input("keywords.discountName");

            $discount = Discount::where("id_entity", $idEntity);

            if ($keyDiscountName)
                $discount->where("name", "like", "%". $keyDiscountName ."%");

            $discount = $discount->get();

            $result = DiscountResponse::collection($discount);
            $response = api::sendResponse(data: $result);
            Log::info("End DiscountController->get()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on DiscountController->get() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.idDiscount" => "required|exists:discounts,id",
            "data.idEntity" => "required",
        ], []);

        if ($validator->fails()) {
            return api::sendResponse(
                code: '105',
                error: $validator->errors()
            );
        }

        Log::info("Start DiscountController->delete()", ["request" => $request->all()]);
        try {
            $idDiscount = $request->input("data.idDiscount");
            $idEntity = $request->input("data.idEntity");

            $discount = Discount::where("id", $idDiscount)->where("id_entity", $idEntity)->first();
            if (!$discount)
                return api::sendResponse(code: '105', desc: "Kamu tidak memiliki akses menghapus data ini.");
            $discount->delete();

            $response = api::sendResponse(desc: 'Data berhasil di hapus');
            Log::info("End DiscountController->delete()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on DiscountController->delete() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }
}
