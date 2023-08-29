<?php

namespace App\Models;

use App\Helpers\api;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Sushi\Sushi;

class MemberAccount extends Model
{
    use Sushi;

    protected $schema = [
        'id_account' => 'integer'
    ];

    public static $idAccount;

    public function getRows()
    {
        if(!static::$idAccount) return [];
        
        $param["reqType"] = "reqGetEmployee";
        $param["reqTime"] = date('YmdHis');
        $param["data"]["idAccount"] = static::$idAccount;
        $resp = api::apiRequest($param, "post", "member", config("service.member.url") . '/account/get', config("service.member.sigkey"));
        Log::info("MemberAccount.php", ["resp" => $resp]);
        $data = [];
        if($resp["statusCode"] === "000" ){
            $row = $resp["data"];
            // $data = collect($resp["data"])->transform( function($row) {
                $data[] = [
                    "id_account" => $row["idAccount"],
                    // "id_entity" => $row["idEntity"],
                    "email" => $row["email"],
                    "password" => $row["password"],
                    "full_name" => $row["profile"]["general"]["fullName"],
                    "nick_name" => $row["profile"]["general"]["nickName"],
                    "gender" => $row["profile"]["general"]["gender"],
                    "phone" => $row["profile"]["general"]["phone"],
                    "place_of_birth" => $row["profile"]["general"]["placeOfBirth"],
                    "date_of_birth" => $row["profile"]["general"]["dateOfBirth"],
                    "id_card" => $row["profile"]["general"]["idCard"],
                    "family_id_card" => $row["profile"]["general"]["familyIdCard"],
                    "citizenship" => $row["profile"]["general"]["citizenship"],
                    "blood_type" => $row["profile"]["general"]["bloodType"],
                    "npwp" => $row["profile"]["general"]["npwp"],
                    "marital_status" => $row["profile"]["general"]["maritalStatus"],
                    "religion" => $row["profile"]["general"]["religion"],
                    "education" => $row["profile"]["general"]["education"],
                    "parent_name" => $row["profile"]["general"]["parentName"],
                    "documents" => $row["profile"]["general"]["documents"],
                    "detail" => $row["profile"]["address"]["idCard"]["detail"],
                    "as_idcard" => $row["profile"]["address"]["idCard"]["asDomicile"],
                    "as_domicile" => $row["profile"]["address"]["domicile"]["detail"],
                ];
            // })->toArray();
        }
        return $data;
    }
}
