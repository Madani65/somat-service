<?php

use App\Helpers\api;
use Illuminate\Http\Request;

$router->post("demo", ['middleware' => ['signature', 'xss-sanitizer'],function(Request $request){
    return "Demo Success";
}]);

$router->post("signature-generator", function (Request $request) {
  return api::sendResponse(data: signature($request->all(), config("signature.key")));
});

$router->post("signature-check", function(Request $request){
    $data = $request->all();
    $reqSign = $request->input("signature");
    $sign = signature($data, config("signature.key"));
    if($reqSign != $sign) {
        $data['validSignature'] = $sign;
        return api::sendResponse(httpCode: 403, code: 101, resp: [ "reqData" => $data], desc: "Your signature is invalid!");
    }
    return api::sendResponse(desc: "Your signature is valid");
});