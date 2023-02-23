<?php

use App\Models\Otp;
use Illuminate\Http\UploadedFile;

if (!function_exists("ksort_nested")) {
    function ksort_nested(Array $array)
    {
        if (isset($array['signature'])) unset($array['signature']);
        foreach ($array as $key => &$val) {
            if (is_array($val)) {
                $val = ksort_nested($val);
            }

            if ($val instanceof UploadedFile) {
                unset($array[$key]);
            }
        }
        ksort($array);
        $output = $array;
        return $output;
    }
}

if (!function_exists("array_concat")) {
    function array_concat($array)
    {
        $output = '';
        foreach ($array as $key => &$val) {
            if (is_array($val)) {
                $val = array_concat($val);
            }
            $output .= $val;
        }
        return $output;
    }
}

if (!function_exists("str_to_hex")) {
    function str_to_hex($string)
    {
        $hex = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $ord = ord($string[$i]);
            $hexCode = dechex($ord);
            $hex .= substr('0' . $hexCode, -2);
        }
        return strToUpper($hex);
    }
}

if (!function_exists("signature")) {
    function signature($array, $kunci)
    {
        unset($array['signature']);
        $output = ksort_nested($array);
        $output = array_concat($output);
        $output = hash_hmac('sha256', $output, str_to_hex($kunci));
        return strtoupper($output);
    }
}

if (!function_exists('dateParser')) {
    function dateParser($input, $formatInput = 'YmdHis', $formatOutput = 'Y-m-d H:i:s')
    {
        $dateTime = DateTime::createFromFormat($formatInput, $input);
        return $dateTime->format($formatOutput);
    }
}

if (!function_exists('get_otp')) {
    function get_otp($tokenId, $expTime)
    {
        $used = false;
        $otp = null;

        do {
            // Create a string of all alpha characters and randomly shuffle them
            $alpha   = str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZ', 4));

            // Create a string of all numeric characters and randomly shuffle them
            $numeric = str_shuffle(str_repeat('0123456789', 2));

            // Grab the 4 first alpha characters + the 2 first numeric characters
            $code = substr($alpha, 0, 4) . substr($numeric, 0, 2);

            // Shuffle the code to get the alpha and numeric in random positions
            $otp = str_shuffle($code);

            // Check if the OTP is already used
            $used = Otp::where(
                [
                    ["otp", "=", $otp],
                    ["exp_time", ">", date("Y-m-d H:i:s")],
                ]
            )->exists();
        } while ($used);

        $saveOtp = new Otp();
        $saveOtp->otp = $otp;
        $saveOtp->id_token = $tokenId;
        $saveOtp->exp_time = $expTime;
        $saveOtp->save();

        return ["code" => $otp, "expTime" => $saveOtp->exp_time];
    }
}