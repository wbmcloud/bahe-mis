<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/6/29
 * Time: 下午11:52
 */

namespace App\Library;

class BContext
{
    /**
     * @var null|int
     */
    private static $request_id = null;

    private static $request_time = null;


    private function __construct()
    {

    }

    private function __clone()
    {

    }

    /**
     * 初始化一些请求信息,全文使用
     */
    public static function init()
    {
        if (self::$request_id === null) {
            if (isset($_GET['request_id']) && !empty($_GET['request_id']) && strlen($_GET['request_id']) < 20) {
                self::$request_id = $_GET['request_id'];
            } else {
                self::$request_id = date("YmdHis") . rand(10000, 99999);
            }
        }
        if (self::$request_time === null) {
            self::$request_time = microtime(true);
        }
    }

    /**
     * 获取请求ID,每个HTTP请求都会自动生成一个请求ID,其生命周期为HTTP请求周期
     * @return null
     */
    public static function getRequestId()
    {
        return self::$request_id;
    }

    /**
     * 获取请求时间
     * @return null
     */
    public static function getRequestTime()
    {
        return self::$request_time;
    }

}