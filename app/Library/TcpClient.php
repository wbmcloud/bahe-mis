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

    public function callTcpService($pack)
    {
        $res = '';

        $factory = new Factory();
        $socket = $factory->createClient($this->getTcpAddress());
        $socket->write($pack);
        while (($data = $socket->read(8192))) {
            $res .= $data;
        }
        $socket->close();

        return $res;
    }

    private function getTcpAddress()
    {
        $idip = Config::get('services.idip');
        return $idip['schema'] . '://' . $idip['host'] . ':' . $idip['port'];
    }
}