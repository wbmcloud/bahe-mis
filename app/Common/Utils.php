<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/13
 * Time: 下午10:22
 */
namespace App\Common;

use App\Exceptions\BaheException;
use Illuminate\Support\Facades\Request;

class Utils
{
    public static function sendJsonResponse($code, $msg = '', array $data = [])
    {
        $ret['code'] = $code;
        if (empty($msg)) {
            $ret['msg'] = BaheException::$error_msg[$code];;
        } else {
            $ret['msg'] = $msg;
        }
        $ret['data'] = (object)$data;
        return response()->json($ret);
    }

    public static function sendJsonSuccessResponse($data = [])
    {
        return self::sendJsonResponse(BaheException::SUCCESS_CODE, '', $data);
    }

    public static function getPathUri($request = null)
    {
        if (!is_null($request)) {
            return preg_replace('/\?.*/', '', $request->getRequestUri());
        }
        return preg_replace('/\?.*/', '', Request::getRequestUri());
    }

    /**
     * 获取内存使用情况
     * @param string $unit
     * @return string
     */
    public static function getMemoryUsage($unit = 'MB')
    {
        switch (strtoupper($unit)) {
            case 'B':
                $use = memory_get_usage(true);
                break;
            case 'KB':
                $use = memory_get_usage(true) / 1024;
                break;
            case 'GB':
                $use = memory_get_usage(true) / 1024 / 1024 / 1024;
                break;
            default:
                $use = memory_get_usage(true) / 1024 / 1024;
                break;
        }
        return $use . $unit;
    }


    public static function getCommissionRate($amount)
    {
        return round($amount / 2, 2);
    }

    public static function arrayUnique(array $arr)
    {
        $serialize_arr = array_map(function ($v) {
            if (is_array($v)) {
                $v = serialize($v);
            }
            return $v;
        }, $arr);
        $arr = array_map(function ($v) {
            return unserialize($v);
        }, array_unique($serialize_arr));

        return array_values($arr);
    }

    public static function renderSuccess()
    {
        return redirect(ParamsRules::IF_PROMPT)->with([
            'message' => BaheException::$error_msg[BaheException::SUCCESS_CODE],
            'jump_url' => ParamsRules::IF_DASHBOARD,
            'jump_time' => Constants::JUMP_TIME_INTERNAL,
        ]);
    }

    public static function renderError($message, $jump_url = null)
    {
        if (is_null($jump_url)) {
            $jump_url = url()->previous();
        }
        return redirect(ParamsRules::IF_PROMPT)->with([
            'message' => $message,
            'jump_url' => $jump_url,
            'jump_time' => Constants::JUMP_TIME_INTERNAL,
        ]);
    }
}