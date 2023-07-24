<?php

namespace App\Http\Controllers;

use App\Helpers\api;
use App\Models\MemberAccount;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller;
use Throwable;

class StudentController extends Controller
{
    public function upsert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "data.idStudent" => "nullable",
            "data.idEntity" => "required",
            "data.accountEmail" => "required|max:50|email:rfc,dns",
            "data.nisn" => "required",
            "data.nis" => "required",
            "data.certificateNumber" => "required",
            "data.skhun" => "required",
            "data.general.fullName" => "required|max:70",
            "data.general.nickName" => "nullable|max:30",
            "data.general.gender" => "required|in:L,P",
            "data.general.phone" => "required|numeric|digits_between:9,15",
            "data.general.placeOfBirth" => "required|max:50",
            "data.general.dateOfBirth" => "required|date_format:Y-m-d",
            "data.general.idCard" => "required|numeric|max_digits:30",
            "data.general.familyIdCard" => "required|numeric|max_digits:30",
            "data.general.citizenship" => "required|max:30",
            "data.general.bloodType" => "nullable|in:AB,A,B,O",
            "data.general.npwp" => "nullable|max:30",
            "data.general.maritalStatus" => "required|in:K,TK",
            "data.general.religion" => "required|in:ISLAM,KRISTEN,HINDU,BUDHA,KATHOLIK",
            "data.general.education" => "required|in:SD,SMP,SMA,D1,D3,S1,S2,S3",
            "data.general.parentName" => "required|max:70",
            "data.general.documents.photoSelfie" => "nullable|mimes:jpeg,jpg,png,bmp|file|max:5120",
            "data.general.documents.photoKtp" => "nullable|mimes:jpeg,jpg,png,bmp|file|max:5120",
            "data.general.documents.photoNpwp" => "nullable|mimes:jpeg,jpg,png,bmp|file|max:5120",
            "data.address.idCard.detail" => "required",
            "data.address.idCard.asDomicile" => "boolean",
            "data.address.domicile.detail" => "required_if:data.idCard.asDomicile,0"
        ], [
            "data.email.unique" => "Email kamu sudah terdaftar",
            "email" => "Email kamu tidak valid",
            "required" => "Data ini belum kamu isi",
            "required_if" => "Data ini wajib kamu isi",
            "max" => "Data yang kamu masukan melebih batas maksimum",
            "digits_between" => "Data yang kamu isi tidak sesuai",
            "numeric" => "Data yang kamu isi tidak sesuai"
        ]);

        if ($validator->fails()) {
            return api::sendResponse(
                code: '105',
                desc: $validator->errors()
            );
        }

        Log::info("Start UserProfileController->upsert()", ["request" => $request->all()]);

        try {
            $idStudent = $request->input('data.idStudent');
            $idEntity = $request->input('data.idEntity');
            $nisn = $request->input('data.nisn');
            $nis = $request->input('data.nis');
            $certificateNumber = $request->input('data.certificateNumber');
            $skhun = $request->input('data.skhun');
            // Account 
            $accountEmail = $request->input('data.accountEmail');
            // Account Profile
            $fullName = $request->input("data.general.fullName");
            $nickName = $request->input("data.general.nickName", null);
            $gender = $request->input("data.general.gender");
            $phone = $request->input("data.general.phone");
            $placeOfBirth = $request->input("data.general.placeOfBirth");
            $dateOfBirth = $request->input("data.general.dateOfBirth");
            $idCard = $request->input("data.general.idCard");
            $familyIdCard = $request->input("data.general.familyIdCard");
            $citizenship = $request->input("data.general.citizenship");
            $bloodType = $request->input("data.general.bloodType", null);
            $npwp = $request->input("data.general.npwp", null);
            $maritalStatus = $request->input("data.general.maritalStatus");
            $religion = $request->input("data.general.religion");
            $education = $request->input("data.general.education");
            $parentName = $request->input("data.general.parentName");
            $docs = $request->file("data.general.documents", []);
            // Account Address
            $idCardDetail = $request->input("data.address.idCard.detail");
            $asDomicile = $request->input("data.address.idCard.asDomicile");
            $domicileDetail = $request->input("data.address.domicile.detail");

            if ($idStudent) {
                $student = Student::find($idStudent);
            } else {
                $student = new Student();
            }

            $student->nisn = $nisn;
            $student->nis = $nis;
            $student->certificate_number = $certificateNumber;
            $student->skhun = $skhun;
            $student->effective_start_date = date('Y-m-d H:i:s');
            $student->effective_end_date = date('Y-m-d H:i:s');
            
            $param["reqType"] = "reqUpsertAccount";
            $param["reqTime"] = date('YmdHis');
            $param["data"]["idAccount"] = $student->id_account;
            $param["data"]["email"] = $accountEmail;
            $param["data"]["password"] = $dateOfBirth;
            $param["data"]["general"]["fullName"] = $fullName;
            $param["data"]["general"]["nickName"] = $nickName;
            $param["data"]["general"]["gender"] = $gender;
            $param["data"]["general"]["phone"] = $phone;
            $param["data"]["general"]["placeOfBirth"] = $placeOfBirth;
            $param["data"]["general"]["dateOfBirth"] = $dateOfBirth;
            $param["data"]["general"]["idCard"] = $idCard;
            $param["data"]["general"]["familyIdCard"]= $familyIdCard;
            $param["data"]["general"]["citizenship"] = $citizenship;
            $param["data"]["general"]["bloodType"] = $bloodType;
            $param["data"]["general"]["npwp"] = $npwp;
            $param["data"]["general"]["maritalStatus"] = $maritalStatus;
            $param["data"]["general"]["religion"] = $religion;
            $param["data"]["general"]["education"] = $education;
            $param["data"]["general"]["parentName"] = $parentName;
            $param["data"]["general"]["document"]["photoProfile"] = $docs;
            $param["data"]["address"]["idCard"]["detail"] = $idCardDetail;
            $param["data"]["address"]["idCard"]["asDomicile"] = $asDomicile;
            $param["data"]["address"]["domicile"]["detail"] = $domicileDetail;
            $resp = api::apiRequest($param, "post", "member", config("service.member.url") . '/account/upsert', config("service.member.sigkey"));
            Log::info("MemberAccount.php", ["resp" => $resp]);
            if ($resp["statusCode"] === "000" ) {
                $student->id_account = $resp["data"]["idAccount"];
                $student->save();
                $memberAccount = new MemberAccount();
                $memberAccount->id_account = $resp["data"]["idAccount"];
                $memberAccount->id_entity = $idEntity;
                $memberAccount->email = $accountEmail;
                $memberAccount->full_name = $fullName;
                $memberAccount->nick_name = $nickName;
                $memberAccount->gender = $gender;
                $memberAccount->phone = $phone;
                $memberAccount->place_of_birth = $placeOfBirth;
                $memberAccount->date_of_birth = $dateOfBirth;
                $memberAccount->id_card = $idCard;
                $memberAccount->family_id_card = $familyIdCard;
                $memberAccount->citizenship = $citizenship;
                $memberAccount->blood_type = $bloodType;
                $memberAccount->npwp = $npwp;
                $memberAccount->marital_status = $maritalStatus;
                $memberAccount->religion = $religion;
                $memberAccount->education = $education;
                $memberAccount->parent_name = $parentName;
                $memberAccount->documents = $docs;
                $memberAccount->detail = $idCardDetail;
                $memberAccount->as_idcard = $asDomicile;
                $memberAccount->as_domicile = $domicileDetail;
                $student->account = $memberAccount;
            }

            // $result = new AccountResponse();
            $response = api::sendResponse(data: $student);
            Log::info("End UserProfileController->upsert()", ["response" => $response]);
            return $response;
        } catch (Throwable $t) {
            $message = "Error on UserProfileController->upsert() | " . $t->getMessage();
            $response = Api::sendResponse(httpCode: 500, code: 500, desc: $message);
            Log::error($message, ["response" => $response, "trace" => $t->getTraceAsString()]);
            return $response;
        }

    }
}
