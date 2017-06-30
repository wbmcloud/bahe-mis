<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/6/29
 * Time: 下午10:25
 */

namespace App\Library;

use App\Common\Utils;
use App\Exceptions\SlException;
use Illuminate\Support\Facades\Request;
use Monolog\Logger;
use Illuminate\Log\Writer;

class BLogger
{
    /**
     * 日志级别
     */
    const LOG_ERROR = 'error';

    const LOG_WARNING = 'warning';

    const LOG_INFO = 'info';

    const LOG_DEBUG = 'debug';

    const LOG_DB = 'db';

    /**
     * 支持的日志级别
     * @var array
     */
    private static $logger_level = [
        self::LOG_DEBUG,
        self::LOG_INFO,
        self::LOG_WARNING,
        self::LOG_ERROR,
        self::LOG_DB,
    ];

    private static $loggers = [];

    private function __construct()
    {

    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    /**
     * @param string $type
     * @return mixed
     */
    public static function getLogger($type = self::LOG_ERROR)
    {
        if (!isset(self::$loggers[$type]) || empty(self::$loggers[$type])) {
            if ($type === self::LOG_DB) {
                self::$loggers[$type] = new Writer(new Logger(self::LOG_INFO));
            } else {
                self::$loggers[$type] = new Writer(new Logger($type));
            }
            $log_file_path = storage_path() . '/logs/' . env('APP_NAME') . '-' . $type . '.log';
            self::$loggers[$type]->useDailyFiles($log_file_path);
        }

        return self::$loggers[$type];
    }

    /**
     * @param      $message
     * @param bool $is_serialize
     * @return mixed
     */
    public static function db($message, $is_serialize = true)
    {
        if (is_array($message) && $is_serialize) {
            $message = self::getDBLoggerMessage($message);
            $message = json_encode($message);
        }

        return self::getLogger(self::LOG_DB)->{self::LOG_INFO}($message);
    }

    /**
     * @param $name
     * @param $arguments [
     *                   $message,
     *                   $is_serialize = true
     *                   ]
     * @return mixed
     * @throws SlException
     */
    public static function __callStatic($name, $arguments)
    {
        if (method_exists(__CLASS__, $name)) {
            return self::{$name}($arguments);
        }
        if (in_array($name, self::$logger_level)) {
            if (is_array($arguments[0])) {
                if (!isset($arguments[1]) || (isset($arguments[1]) && $arguments)) {
                     $arguments[0] = json_encode(self::getLoggerMessage($arguments[0]),
                         JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                }
            }

            return call_user_func_array([self::getLogger($name), $name], $arguments);
        }

        throw new SlException(SlException::METHOD_NOT_EXIST_CODE);
    }

    /**
     * @param $message
     * @return array
     */
    protected static function getLoggerMessage($message)
    {
        return [
            'request_id' => BContext::getRequestId(),
            'project'    => env('APP_NAME'),
            'memory'     => Utils::getMemoryUsage(),
            'client_ip'  => isset($_REQUEST['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '',
            'consume'    => round(microtime(true) - BContext::getRequestTime(), 3),
            'input'      => [
                'url'      => Request::fullUrl(),
                'header'   => Request::header(),
                'request'  => Request::all(),
                'response' => $message,
            ]
        ];
    }

    /**
     * @param $message
     * @return array
     */
    protected static function getDBLoggerMessage($message)
    {
        return [
            'request_id' => BContext::getRequestId(),
            'sql' => $message,
        ];
    }

}