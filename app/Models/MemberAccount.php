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
        $resp = api::apiRequest($param, "post", "member", config("service.member.url") . '/account/upsert', config("service.member.sigkey"));
        Log::info("MemberAccount.php", ["resp" => $resp]);
        $data = [];
        if($resp["statusCode"] === "000" ){
            $data = collect($resp["data"])->transform( function($row) {
                return [
                    "id_account" => $row["idAccount"],
                    "id_entity" => $row["idEntity"],
                    "email" => $row["account"]["email"],
                    "full_name" => $row["account"]["profile"]["general"]["fullName"],
                    "nick_name" => $row["account"]["profile"]["general"]["nickName"],
                    "gender" => $row["account"]["profile"]["general"]["gender"],
                    "phone" => $row["account"]["profile"]["general"]["phone"],
                    "place_of_birth" => $row["account"]["profile"]["general"]["placeOfBirth"],
                    "date_of_birth" => $row["account"]["profile"]["general"]["dateOfBirth"],
                    "id_card" => $row["account"]["profile"]["general"]["idCard"],
                    "family_id_card" => $row["account"]["profile"]["general"]["familyIdCard"],
                    "citizenship" => $row["account"]["profile"]["general"]["citizenship"],
                    "blood_type" => $row["account"]["profile"]["general"]["bloodType"],
                    "npwp" => $row["account"]["profile"]["general"]["npwp"],
                    "marital_status" => $row["account"]["profile"]["general"]["maritalStatus"],
                    "religion" => $row["account"]["profile"]["general"]["religion"],
                    "education" => $row["account"]["profile"]["general"]["education"],
                    "parent_name" => $row["account"]["profile"]["general"]["parentName"],
                    "documents" => $row["account"]["profile"]["general"]["documents"],
                    "detail" => $row["account"]["profile"]["address"]["idCard"]["detail"],
                    "as_idcard" => $row["account"]["profile"]["address"]["idCard"]["asDomicile"],
                    "as_domicile" => $row["account"]["profile"]["address"]["domicile"]["detail"],
                ];
            })->toArray();
        }
        return $data;
    }
}
