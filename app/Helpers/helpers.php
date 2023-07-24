<?php

use App\Models\Otp;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

if (!function_exists("ksort_nested")) {
    function ksort_nested(array $array)
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

if (!function_exists("get_uploaded_files")) {
    function get_uploaded_files($array, $output = [])
    {
        foreach ($array as $key => &$val) {
            if (is_array($val)) {
                $output[$key] = array_merge($output, get_uploaded_files($val, $output));
                if (empty($output[$key])) unset($output[$key]);
            }

            if ($val instanceof UploadedFile) {
                $output[$key] = $val;
            }
        }
        return $output;
    }
}

if (!function_exists("remove_uploaded_files")) {
    function remove_uploaded_files($array)
    {
        foreach ($array as $key => &$val) {
            if (is_array($val)) {
                $val = remove_uploaded_files($val);
                if (empty($val)) unset($array[$key]);
            }

            if ($val instanceof UploadedFile) {
                unset($array[$key]);
            }
        }
        $output = $array;
        return $output;
    }
}

if (!function_exists('file_renamer')) {
    function file_renamer($input)
    {
        $temp = explode(".", $input);
        $ext = end($temp);
        return str_replace("." . $ext, "-" . date("ymdhis") . "." . $ext, $input);
    }
}

if (!function_exists('array_flatten')) {
    function array_flatten($array, $prefix = "")
    {
        if (!is_array($array)) {
            return FALSE;
        }
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, array_flatten($value, $prefix . $key . "_"));
            } else {
                $result[$prefix . $key] = $value;
            }
        }
        return $result;
    }
}

if (!function_exists('array_form_data')) {
    function array_form_data($array, $pretend = "", $level = 0)
    {
        if (!is_array($array)) {
            return [];
        }
        $result = array();
        foreach ($array as $key => $value) {
            $idx = $level > 0 ? $pretend . "[" . $key . "]" : $pretend . $key;
            if ($value instanceof UploadedFile) {
                unset($array[$key]);
            } else {
                if (is_array($value)) {
                    $result = array_merge($result, array_form_data($value, $idx, $level + 1));
                } else {
                    $result[$idx] = $value;
                }
            }
        }
        return $result;
    }
}

if (!function_exists('array_form_files')) {
    function array_form_files($array, $pretend = "", $level = 0)
    {
        $path = "public/tmp";
        if (!is_array($array)) {
            return [];
        }
        $result = array();
        foreach ($array as $key => $value) {
            $idx = $level > 0 ? $pretend . "[" . $key . "]" : $pretend . $key;
            if ($value instanceof UploadedFile) {
                $result[$idx] = Storage::putFileAs($path, $value, file_renamer($value->getClientOriginalName()));
            } else {
                if (is_array($value)) {
                    $result = array_merge($result, array_form_files($value, $idx, $level + 1));
                } else {
                    unset($array[$key]);
                }
            }
        }
        return $result;
    }
}

if (!function_exists('files_upload')) {
    function files_upload($docs = [], $path = "files", $prefix = "file")
    {
        $path = "public/data/" . $path;
        $files = [];
        foreach ($docs as $doc => $file) {
            Log::info("file " . $doc, ["data" => $file]);
            $files[$doc]["path"] = $path;
            $files[$doc]["filename"] = $prefix . "-" . $doc . "-" . date("YmdHis") . str_pad(rand(1, 999), 3, "0", STR_PAD_LEFT) . "." . $file->getClientOriginalExtension();
            $files[$doc]["mime"] = $file->getClientMimeType();
            $uploadFile = Storage::putFileAs($path, $file, $files[$doc]["filename"]);
            if (in_array($files[$doc]["mime"], ["image/jpeg", "image/png"])) {
                image_resize(fullpath: "$path/" . $files[$doc]["filename"], pxSize: 500);
            }
        }
        return $files;
    }
}

if (!function_exists('image_resize')) {
    /* 
    PARAMETER
    $fullpath String    => Full path from storage_path to file
    $limit Int          => Limit file size in MB
    $pxSize Int          => Width/height file in px
    */
    function image_resize($fullpath, $limit = 1, $pxSize = 2000)
    {
        $img = Image::make(storage_path("app/" . $fullpath));
        if ($img->filesize() > ($limit * 1000000)) {
            $height = $img->height();
            $width = $img->width();
            if ($height == $width || $height < $width) {
                $img->resize($pxSize,  null,  function ($const) {
                    $const->aspectRatio();
                })->save(\storage_path("app/$fullpath"));
            } else {
                $img->resize(null,  $pxSize,  function ($const) {
                    $const->aspectRatio();
                })->save(\storage_path("app/$fullpath"));
            }
        }
    }
}

if (!function_exists('search_array_key')) {
    function search_array_key($key, $array = [])
    {
        $results = array();

        if (is_array($array)) {
            if (isset($array[$key]) && key($array) == $key)
                $results[] = $array[$key];

            foreach ($array as $sub_array)
                $results = array_merge($results, search_array_key($sub_array, $key));
        }

        return  $results;
    }
}

if (!function_exists('initial')) {
    function initial($name)
    {
        $words = explode(' ', $name);
        if (count($words) >= 2) {
            return mb_strtoupper(
                mb_substr($words[0], 0, 1, 'UTF-8') .
                    mb_substr(end($words), 0, 1, 'UTF-8'),
                'UTF-8'
            );
        }

        preg_match_all('#([A-Z]+)#', $name, $capitals);
        if (count($capitals[1]) >= 2) {
            return mb_substr(implode('', $capitals[1]), 0, 2, 'UTF-8');
        }
        return mb_strtoupper(mb_substr($name, 0, 2, 'UTF-8'), 'UTF-8');
    }
}
