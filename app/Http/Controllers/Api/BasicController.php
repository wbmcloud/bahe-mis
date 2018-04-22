<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/10
 * Time: 下午10:45
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Logic\AgentLogic;

class BasicController extends Controller
{
    public function cityConfig()
    {
        $city_id = $this->params['city_id'];
        $game_type = $this->params['game_type'];

        return [
            'city_id' => $city_id,
            'settings' => (new AgentLogic())->getOpenRoomSetting($city_id, $game_type)
        ];
    }
}