<?php

namespace App\Http\Controllers;

use App\Helpers\api;
use App\Http\Resources\BusinessPartnerResponse;
use App\Models\BusinessPartner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class BusinessPartnerController extends Controller
{
    protected $auth;

    public function __construct()
    {
        $this->auth = Auth::user();
    }

    public function upsert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.idBusinessPartner" => "nullable|exists:business_partners,id",
            "data.partnerName" => "required|max:70",
            "data.idPartnerType" => "required|exists:partner_types,id",
            "data.asCustomer" => "required|boolean",
            "data.asSupplier" => "required|boolean",
            "data.idEntity" => "required",
            "data.email" => "nullable|email|max:50",
            "data.address" => "nullable|max:240",
            "data.gender" => "nullable|in:L,P",
            "data.phone" => "required|numeric|digits_between:9,15",
            "data.salesPerson" => "nullable|max:50",
            "data.salesPhone" => "nullable|max:20",
            "data.dateOfBirth" => "nullable|date_format:Y-m-d",
            "data.asDefault" => "nullable|boolean",
            "data.documents.photoProfile" => "nullable|mimes:jpeg,jpg,png,bmp|file|max:5120",
        ], [
            "required" => "Field ini wajib kamu isi",
        ]);

        if ($validator->fails()) {
            return api::sendResponse(
                code: '105',
                error: $validator->errors()
            );
        }

        Log::info("Start BusinessPartnerController->upsert()", ["request" => $request->all()]);
        try {
            $idBusinessPartner = $request->input("data.idBusinessPartner");
            $partnerName = $request->input("data.partnerName");
            $idPartnerType = $request->input("data.idPartnerType");
            $asCustomer = $request->input("data.asCustomer");
            $asSupplier = $request->input("data.asSupplier");
            $idEntity = $request->input("data.idEntity");
            $email = $request->input("data.email");
            $address = $request->input("data.address");
            $gender = $request->input("data.gender");
            $phone = $request->input("data.phone");
            $salesPerson = $request->input("data.salesPerson");
            $salesPhone = $request->input("data.salesPhone");
            $dateOfBirth = $request->input("data.dateOfBirth", null);
            $asDefault = $request->input("data.asDefault", 0);
            $docs = $request->file("data.documents", []);

            if (!$asCustomer && !$asSupplier)
                return api::sendResponse(code: '105', desc: "Kamu belum memilih terapkan mitra sebagai pelanggan atau supplier.");

            if ($idBusinessPartner) {
                $partner = BusinessPartner::where("id", $idBusinessPartner)->where("id_entity", $idEntity)->first();
                if (!$partner)
                    return api::sendResponse(code: '105', desc: "Kamu tidak memiliki akses mengubah data mitra pada entitas ini.");
            } else {
                $partner = new BusinessPartner();
                $partner->partner_num = initial($partnerName) . date('Ymd') . '-' . str_pad(rand(1, 999), 3, "0", STR_PAD_LEFT);
                $partner->id_entity = $idEntity;
                $partner->as_default = $asDefault ?: 0;
            }
            $partner->partner_name = $partnerName;
            $partner->id_partner_type = $idPartnerType;
            $partner->as_customer = $asCustomer;
            $partner->as_supplier = $asSupplier;
            $partner->email = $email;
            $partner->address = $address;
            $partner->gender = $gender;
            $partner->phone = $phone;
            $partner->sales_person = $salesPerson;
            $partner->sales_phone = $salesPhone;
            $partner->date_of_birth = $dateOfBirth ?: null;

            $files = files_upload($docs, "business-partner/entity_" . $idEntity);
            if ($files) {
                $documents = $partner->documents ?: [];
                foreach ($files as $idx => $file) {
                    $documents[$idx] = $file;
                }
                $partner->documents = $documents;
            }
            $partner->save();

            $result = new BusinessPartnerResponse($partner);
            $response = api::sendResponse(data: $result);
            Log::info("End BusinessPartnerController->upsert()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on BusinessPartnerController->upsert() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }

    public function get(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.idEntity" => "required"
        ], []);

        if ($validator->fails()) {
            return api::sendResponse(
                code: '105',
                error: $validator->errors()
            );
        }

        Log::info("Start BusinessPartnerController->get()", ["request" => $request->all()]);
        try {
            $idEntity = $request->input("data.idEntity", false);

            $partners = BusinessPartner::with("partner_type")
                ->where("id_entity", $idEntity);

            $partners = $partners->get();

            $result = BusinessPartnerResponse::collection($partners);

            $response = api::sendResponse(data: $result);
            Log::info("End BusinessPartnerController->get()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on BusinessPartnerController->get() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.idBusinessPartner" => "required|exists:business_partners,id",
            "data.idEntity" => "required",
            "data.forceDelete" => "nullable|boolean",
        ], []);

        if ($validator->fails()) {
            return api::sendResponse(
                code: '105',
                error: $validator->errors()
            );
        }

        Log::info("Start BusinessPartnerController->delete()", ["request" => $request->all()]);
        try {
            $idBusinessPartner = $request->input("data.idBusinessPartner");
            $idEntity = $request->input("data.idEntity");
            $forceDelete = $request->input("data.forceDelete", false);

            $partner = BusinessPartner::where("id", $idBusinessPartner)->where("id_entity", $idEntity)->first();
            if (!$partner)
                return api::sendResponse(code: '105', desc: "Kamu tidak memiliki akses menghapus data ini.");
            $partner->delete();

            $response = api::sendResponse(desc: 'Data berhasil di hapus');
            Log::info("End BusinessPartnerController->delete()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on BusinessPartnerController->delete() | " . $t->getMessage();
            $response = api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }
    }
}
