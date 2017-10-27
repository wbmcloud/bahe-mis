<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/13
 * Time: 下午10:14
 */
namespace App\Library;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Socket\Raw\Factory;
use Socket\Raw\Socket;

class TcpClient
{
    /**
     * @var Socket
     */
    private static $socket = null;

    private function __construct()
    {

    }

    private function __clone()
    {

    }

    public static function getSocket()
    {
        if (self::$socket) {
            return self::$socket;
        }
        $factory = new Factory();
        self::$socket = $factory->createClient(self::getTcpAddress());
        return self::$socket;
    }

    public static function callTcpService($pack, $keep_alive = false)
    {
        try {
            $socket = self::getSocket();

            // 设置读写超时
            $socket->setOption(SOL_SOCKET, SO_SNDTIMEO, [
                "sec" => 5, // Timeout in seconds
                "usec" => 0  // I assume timeout in microseconds
            ]);

            $socket->setOption(SOL_SOCKET, SO_RCVTIMEO, [
                "sec" => 5, // Timeout in seconds
                "usec" => 0  // I assume timeout in microseconds
            ]);

            // 获取发送包体大小
            $format = sprintf('na%d', strlen($pack));
            $send_data = pack($format, strlen($pack), $pack);
            $socket->write($send_data);

            // 解析响应包
            $res = $socket->read(8192);
            $format =  sprintf('nbody_size/a%ddata', strlen($res) - 2);
            $unpack_data = unpack($format, $res);

            $response = $unpack_data['data'];
        } catch (\Exception $e) {
            $socket->close();
            throw $e;
        }

        if (!$keep_alive && self::isAlive()) {
            $socket->close();
            self::$socket = null;
        }

        return $response;
    }

    private static function getTcpAddress()
    {
        $gmt = Config::get('services.gmt');
        return $gmt['schema'] . '://' . $gmt['host'] . ':' . $gmt['port'];
    }

    public static function isAlive()
    {
        return (is_null(self::$socket) || (gettype(self::$socket->getResource()) !== 'resource')) ? false : true;
    }
}