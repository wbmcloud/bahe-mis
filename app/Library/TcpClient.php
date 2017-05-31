<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/13
 * Time: 下午10:14
 */
namespace App\Library;

use Illuminate\Support\Facades\Config;
use Socket\Raw\Factory;

class TcpClient
{
    public function __construct()
    {

    }

    public static function callTcpService($pack)
    {
        $factory = new Factory();
        $socket = $factory->createClient(self::getTcpAddress());

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

        $socket->close();

        return $res;
    }

    private static function getTcpAddress()
    {
        $idip = Config::get('services.idip');
        return $idip['schema'] . '://' . $idip['host'] . ':' . $idip['port'];
    }
}