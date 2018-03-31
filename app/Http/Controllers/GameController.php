<?php

namespace App\Http\Controllers;

use App\Common\Constants;
use App\Common\Utils;
use App\Exceptions\BaheException;
use App\Logic\CityLogic;
use App\Logic\GameLogic;
use App\Logic\UserLogic;
use App\Models\UserBindPlayer;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    /**
     * 角色列表
     */
    public function playerList()
    {
        $page_size = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;

        $game_logic = new GameLogic();

        return [
            'players' => $game_logic->getPlayerList($this->params, $page_size)
        ];
    }

    public function showBindPlayerForm()
    {
        $user = Auth::user();
        $cities = [];

        if ($user->hasRole(Constants::$admin_role)) {
            // 管理员和超级管理员
            $user_logic = new UserLogic();
            $cities     = $user_logic->getOpenCities();
        }

        return [
            'agent'  => $user,
            'cities' => $cities,
        ];
    }

    public function bindPlayer()
    {
        $city_id = $this->params['city'];
        $params['player_id'] = $this->params['player_id'];
        $login_user = Auth::user();
        $params['user_name'] = $login_user->user_name;

        $city_info = CityLogic::getCityInfo($city_id);
        if (empty($city_info)) {
            throw new BaheException(BaheException::CITY_NOT_VALID_CODE);
        }

        $params['gmt_server_ip'] = $city_info['gmt_server_ip'];
        $params['gmt_server_port'] = $city_info['gmt_server_port'];

        (new GameLogic())->sendGmtBindPlayer($params);

        // 保存绑定数据
        $user_bind_player = new UserBindPlayer();
        $user_bind_player->user_id = $login_user->id;
        $user_bind_player->user_name = $login_user->user_name;
        $user_bind_player->player_id = $this->params['player_id'];
        $user_bind_player->save();

        return Utils::renderSuccess();;
    }
}
