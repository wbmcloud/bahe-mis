<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/13
 * Time: 下午10:22
 */
namespace App\Common;

use App\Exceptions\SlException;
use Illuminate\Support\Facades\Request;

class Utils
{
    public static function sendJsonResponse($code, $msg = '', array $data = [])
    {
        $ret['code'] = $code;
        if (empty($msg)) {
            $ret['msg'] = SlException::$error_msg[$code];;
        } else {
            $ret['msg'] = $msg;
        }
        $ret['data'] = (object)$data;
        return response()->json($ret);
    }

    public static function sendJsonSuccessResponse($data = [])
    {
        return self::sendJsonResponse(SlException::SUCCESS_CODE, '', $data);
    }

    public static function getPathUri()
    {
        return preg_replace('/\?.*/', '', Request::getRequestUri());
    }
}