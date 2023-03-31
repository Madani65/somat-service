<?php

namespace App\Http\Controllers;

use App\Helpers\api;
use App\Models\EntityCurrency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class EntityCurrencyController extends Controller
{
    public function get(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.idEntity" => "nullable",
            "data.toArray" => "nullable|boolean"
        ], []);

        if ($validator->fails()) {
            return api::sendResponse(
                code: '105',
                error: $validator->errors()
            );
        }

        Log::info("Start EntityCurrencyController->get()", ["request" => $request->all()]);
        try {
            $idEntity = $request->input("data.idEntity", false);
            $toArray = $request->input("data.toArray", false);

            $map = EntityCurrency::with("currency");

            if ($idEntity)
                if (is_array($idEntity))
                    $map->whereIn("id_entity", $idEntity);
                else
                    $map->where("id_entity", $idEntity);

            $map = $map->get();

            $result = $map->transform(function ($row) {
                return [
                    "idEntity" => $row->id_entity,
                    "code" => $row->currency_code,
                    "name" => $row->currency?->name
                ];
            });

            if (!$toArray)
                $result = $result->first();

            $response = api::sendResponse(data: $result);
            Log::info("End EntityCurrencyController->get()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on EntityCurrencyController->get() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }

    public function map(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.idEntity" => "required",
            "data.codeCurrencies" => "required|array",
            "data.codeCurrencies.*" => "exists:currencies,code",
            "data.toArray" => "nullable|boolean"
        ], [
            "data.codeCurrencies.*.exists" => "Kode Mata Uang :input tidak di temukan"
        ]);

        if ($validator->fails()) {
            return api::sendResponse(
                code: '105',
                error: $validator->errors()
            );
        }

        Log::info("Start EntityCurrencyController->map()", ["request" => $request->all()]);
        try {
            $idEntity = $request->input("data.idEntity");
            $codeCurrencies = $request->input("data.codeCurrencies");
            $toArray = $request->input("data.toArray", false);

            $exists = EntityCurrency::where("id_entity", $idEntity)->get();

            $exists->map(function ($row) use ($codeCurrencies) {
                if (!in_array($row->currency_code, $codeCurrencies))
                    $row->delete();
            });

            $notExists = collect($codeCurrencies)->diff($exists->pluck("currency_code"));
            $notExists->map(function ($row) use ($idEntity) {
                $new = new EntityCurrency();
                $new->id_entity = $idEntity;
                $new->currency_code = $row;
                $new->save();
            });

            $map = EntityCurrency::with("currency")->where("id_entity", $idEntity)->get();

            $result = $map->transform(function ($row) {
                return [
                    "code" => $row->currency_code,
                    "name" => $row->currency?->name
                ];
            });

            if (!$toArray)
                $result = $result->first();

            $response = api::sendResponse(data: $result);
            Log::info("End EntityCurrencyController->map()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on EntityCurrencyController->map() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }
}
