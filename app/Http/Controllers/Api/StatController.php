<?php

namespace App\Http\Controllers\Api;

use App\Common\Constants;
use App\Http\Controllers\Controller;
use App\Logic\StatLogic;

class StatController extends Controller
{
    /**
     * 代理数据统计
     */
    public function agent()
    {
        $stat_logic = new StatLogic();

        return $stat_logic->getStatAgentList(Constants::STAT_MAX_DAY);
    }

    /**
     * 流水统计
     */
    public function flow()
    {
        $city_id = $this->params['city_id'];
        $game_type = $this->params['game_type'];

        $stat_logic = new StatLogic();

        return $stat_logic->getStatFlowList($city_id, $game_type, Constants::STAT_MAX_DAY);
    }

    /**
     * 代理流水统计
     */
    public function agentFlow()
    {
        $city_id = $this->params['city_id'];

        $stat_logic = new StatLogic();

        return $stat_logic->getStatAgentFlowList($city_id, Constants::STAT_MAX_DAY);
    }

    /**
     * 局数统计
     */
    public function rounds()
    {
        $city_id = $this->params['city_id'];
        $game_type = $this->params['game_type'];

        $stat_logic = new StatLogic();

        return $stat_logic->getStatRoundsList($city_id, $game_type, Constants::STAT_MAX_DAY);
    }

    /**
     * 游戏DAU统计
     */
    public function dau()
    {
        $city_id = $this->params['city_id'];
        $game_type = $this->params['game_type'];

        $stat_logic = new StatLogic();

        return $stat_logic->getStatDauList($city_id, $game_type, Constants::STAT_MAX_DAY);
    }

    /**
     * 游戏DAU统计
     */
    public function wau()
    {
        $city_id = $this->params['city_id'];
        $game_type = $this->params['game_type'];

        $stat_logic = new StatLogic();

        return $stat_logic->getStatWauList($city_id, $game_type, Constants::STAT_MAX_DAY);
    }

    /**
     * 游戏DAU统计
     */
    public function mau()
    {
        $city_id = $this->params['city_id'];
        $game_type = $this->params['game_type'];

        $stat_logic = new StatLogic();

        return $stat_logic->getStatMauList($city_id, $game_type, Constants::STAT_MAX_DAY);
    }
}
