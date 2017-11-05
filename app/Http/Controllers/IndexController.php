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
            'today_consume_card' => $stat_logic->getTodayConsumeCard(),
            'today_recharge_card' => $stat_logic->getTodayRechargeCard(),
            'today_new_agents' => $stat_logic->getTodayNewAgents(),
            'total_agents' => $stat_logic->getTotalAgents(),
            'total_game_player' => $stat_logic->getTotalGamePlayer(),
        ];
    }
}