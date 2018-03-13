<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/12
 * Time: 上午10:49
 */

namespace App\Logic;

use App\Models\GameServer;
use App\Models\GeneralAgents;

class CityLogic extends BaseLogic
{
    /**
     * @param $city_id
     * @return array
     */
    public static function getCityInfo($city_id)
    {
        $server = GameServer::where([
            'city_id' => $city_id
        ])->first();

        return !empty($server) ? $server->toArray() : [];
    }

    /**
     * @param $server_id
     * @return array
     */
    public static function getServerInfo($server_id)
    {
        $server = GameServer::where([
            'server_id' => $server_id
        ])->first();

        return !empty($server) ? $server->toArray() : [];
    }
}