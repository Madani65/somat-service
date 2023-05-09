<?php

namespace App\Models;

use App\Helpers\api;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Sushi\Sushi;

class MemberEntity extends Model
{
    use Sushi;

    protected $schema = [
        'idEntity' => 'integer',
        'idParent' => 'integer'
    ];

    public static $idEntity;

    public function getRows()
    {
        if(!static::$idEntity) return [];
        
        $param["reqType"] = "reqGetEntities";
        $param["reqTime"] = date('YmdHis');
        $param["data"]["idParent"] = static::$idEntity;
        $param["data"]["onlyOwner"] = 0;
        $param["data"]["onlyParent"] = 0;
        $param["data"]["showAddon"] = 0;
        $resp = api::apiRequest($param, "post", "alika", config("service.member.url") . '/entity/get', config("service.member.sigkey"));
        Log::info("memberEntity.php", ["resp" => $resp]);
        $data = [];
        if($resp["statusCode"] === "000" ){
            $data = collect($resp["data"])->transform( function($row) {
                return [
                    "id" => $row["idEntity"],
                    "id_parent" => $row["idParent"],
                    "name" => $row["entityName"],
                ];
            })->toArray();
        }
        return $data;
    }
}
