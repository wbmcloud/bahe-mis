<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/10
 * Time: 下午10:45
 */

namespace App\Http\Controllers;

use App\Logic\StatLogic;

class IndexController extends Controller
{
    public function index()
    {
        $stat_logic  = new StatLogic();

        return [
            'total_balance_card' => $stat_logic->getTotalBalanceCard(),
            'total_card' => $stat_logic->getTotalCard(),
            'today_user_recharge_card' => $stat_logic->getTodayUserRechargeCard(),
            'today_open_room_card' => $stat_logic->getTodayOpenRoomCard(),
            'today_recharge_card' => $stat_logic->getTodayRechargeCard(),
            'today_new_agents' => $stat_logic->getTodayNewAgents(),
            'total_agents' => $stat_logic->getTotalAgents(),
            'total_game_player' => $stat_logic->getTotalGamePlayer(),
            'total_give_card' => $stat_logic->getGiveTotalCard(),
            'today_give_card' => $stat_logic->getTodayGiveCard(),
            'today_active_agents' => $stat_logic->getTodayActiveAgents(),
        ];
    }
}