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

            $socket->write($pack);
            /*while (($data = $socket->read(8192, PHP_NORMAL_READ))) {
                    $res .= $data;
                }*/
            $res = $socket->read(8192);
        } catch (\Exception $e) {
            $socket->close();
            throw $e;
        }

        if (!$keep_alive && self::isAlive()) {
            $socket->close();
        }

        return $res;
    }

    private static function getTcpAddress()
    {
        $idip = Config::get('services.idip');
        return $idip['schema'] . '://' . $idip['host'] . ':' . $idip['port'];
    }

    public static function isAlive()
    {
        return (is_null(self::$socket) || (gettype(self::$socket->getResource()) !== 'resource')) ? false : true;
    }
}